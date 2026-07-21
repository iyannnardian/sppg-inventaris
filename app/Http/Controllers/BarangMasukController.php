<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    private function checkAccess()
    {
        if (Auth::check() && in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg'])) {
            abort(403, 'Akses ditolak. Peran Kepala Dapur / Kepala SPPG tidak memiliki wewenang untuk transaksi ini.');
        }
    }

    public function index(Request $request)
    {
        // Ensure default suppliers exist
        if (Supplier::count() === 0) {
            Supplier::create([
                'nama_supplier' => 'PT. Distributor Sembako Utama',
                'alamat' => 'Jl. Raya Industri No. 10, Jakarta'
            ]);
            Supplier::create([
                'nama_supplier' => 'CV. Pangan Makmur Abadi',
                'alamat' => 'Jl. Kemitraan No. 25, Bandung'
            ]);
        }

        $suppliers = Supplier::all();
        $barangs = Barang::with(['satuan', 'subKategori.kategori'])->get();

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $statusFilter = $request->input('status');
        $supplierFilter = $request->input('id_supplier');

        $query = Pembelian::with(['supplier', 'details.barang.satuan']);

        if ($tanggalAwal) {
            $query->where('tgl_faktur', '>=', $tanggalAwal);
        }
        if ($tanggalAkhir) {
            $query->where('tgl_faktur', '<=', $tanggalAkhir);
        }
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        if ($supplierFilter && $supplierFilter !== 'all') {
            $query->where('id_supplier', $supplierFilter);
        }

        $transaksis = $query->orderBy('tgl_faktur', 'desc')
                            ->orderBy('id_pembelian', 'desc')
                            ->get();

        $autoNoFaktur = 'FKT-' . date('Ymd') . '-' . sprintf('%03d', Pembelian::count() + 1);

        return view('barang-masuk.index', compact(
            'barangs',
            'suppliers',
            'transaksis',
            'tanggalAwal',
            'tanggalAkhir',
            'statusFilter',
            'supplierFilter',
            'autoNoFaktur'
        ));
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'details.barang.satuan'])->findOrFail($id);
        return response()->json($pembelian);
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $request->validate([
            'no_faktur' => 'required|string|max:50|unique:pembelians,no_faktur',
            'id_supplier' => 'required|exists:suppliers,id_supplier',
            'tgl_faktur' => 'required|date',
            'tgl_terima' => 'required|date',
            'status' => 'required|in:Draft,Diterima,Batal',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barangs,id_barang',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga' => 'required|numeric|min:0',
        ], [
            'no_faktur.required' => 'Nomor Faktur wajib diisi.',
            'no_faktur.unique' => 'Nomor Faktur sudah terdaftar dalam sistem.',
            'id_supplier.required' => 'Supplier wajib dipilih.',
            'id_supplier.exists' => 'Supplier tidak valid.',
            'tgl_faktur.required' => 'Tanggal Faktur wajib diisi.',
            'tgl_terima.required' => 'Tanggal Terima wajib diisi.',
            'status.required' => 'Status pembelian wajib dipilih.',
            'items.required' => 'Minimal 1 barang harus dimasukkan ke dalam daftar pembelian.',
            'items.min' => 'Minimal 1 barang harus dimasukkan ke dalam daftar pembelian.',
            'items.*.id_barang.required' => 'Barang wajib dipilih.',
            'items.*.qty.required' => 'Jumlah barang (qty) wajib diisi.',
            'items.*.qty.min' => 'Jumlah barang minimal 0.01.',
            'items.*.harga.required' => 'Harga beli barang wajib diisi.',
        ]);

        $status = $request->input('status', 'Draft');

        DB::transaction(function () use ($request, $status) {
            $totalBelanja = 0;

            $pembelian = Pembelian::create([
                'no_faktur' => $request->no_faktur,
                'id_supplier' => $request->id_supplier,
                'tgl_faktur' => $request->tgl_faktur,
                'tgl_terima' => $request->tgl_terima,
                'status' => $status,
                'total_belanja' => 0,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['harga'];
                $totalBelanja += $subtotal;

                PembelianDetail::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'id_barang' => $item['id_barang'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $subtotal,
                ]);

                if ($status === 'Diterima') {
                    $barang = Barang::find($item['id_barang']);
                    if ($barang) {
                        $barang->update(['harga_terakhir' => $item['harga']]);
                    }
                }
            }

            $pembelian->update(['total_belanja' => $totalBelanja]);
        });

        $statusMsg = $status === 'Diterima' 
            ? 'dan stok barang otomatis bertambah (Barang Masuk)!' 
            : ($status === 'Draft' ? 'sebagai Draf!' : 'dengan status Dibatalkan!');

        return redirect()->route('barang-masuk.index')->with('success', "Transaksi Pembelian No. Faktur {$request->no_faktur} berhasil disimpan {$statusMsg}");
    }

    public function updateStatus(Request $request, $id)
    {
        $this->checkAccess();

        $request->validate([
            'status' => 'required|in:Draft,Diterima,Batal',
        ]);

        $pembelian = Pembelian::with('details.barang')->findOrFail($id);
        $oldStatus = $pembelian->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return redirect()->route('barang-masuk.index');
        }

        if ($oldStatus === 'Diterima' && in_array($newStatus, ['Draft', 'Batal'])) {
            foreach ($pembelian->details as $detail) {
                $barang = $detail->barang;
                if ($barang) {
                    $currentStok = $barang->stok;
                    if (($currentStok - $detail->qty) < 0) {
                        return redirect()->route('barang-masuk.index')->with('error', "Gagal mengubah status! Pengurangan stok barang '{$barang->nama_barang}' sebanyak {$detail->qty} akan menyebabkan stok menjadi negatif (stok saat ini: {$currentStok}).");
                    }
                }
            }
        }

        DB::transaction(function () use ($pembelian, $newStatus) {
            $pembelian->update(['status' => $newStatus]);

            if ($newStatus === 'Diterima') {
                foreach ($pembelian->details as $detail) {
                    if ($detail->barang) {
                        $detail->barang->update(['harga_terakhir' => $detail->harga]);
                    }
                }
            }
        });

        $msg = $newStatus === 'Diterima'
            ? "Status faktur {$pembelian->no_faktur} diubah menjadi DITERIMA. Stok barang berhasil ditambahkan ke persediaan!"
            : ($newStatus === 'Batal' 
                ? "Status faktur {$pembelian->no_faktur} diubah menjadi DIBATALKAN. Stok yang masuk sebelumnya telah ditarik." 
                : "Status faktur {$pembelian->no_faktur} diubah menjadi DRAF.");

        return redirect()->route('barang-masuk.index')->with('success', $msg);
    }

    public function update(Request $request, $id)
    {
        $this->checkAccess();

        $pembelian = Pembelian::with('details.barang')->findOrFail($id);

        $request->validate([
            'no_faktur' => 'required|string|max:50|unique:pembelians,no_faktur,' . $id . ',id_pembelian',
            'id_supplier' => 'required|exists:suppliers,id_supplier',
            'tgl_faktur' => 'required|date',
            'tgl_terima' => 'required|date',
            'status' => 'required|in:Draft,Diterima,Batal',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barangs,id_barang',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        if ($pembelian->status === 'Diterima') {
            foreach ($pembelian->details as $oldDetail) {
                $barang = $oldDetail->barang;
                if ($barang) {
                    $newQty = 0;
                    foreach ($request->items as $item) {
                        if ($item['id_barang'] == $oldDetail->id_barang) {
                            $newQty += $item['qty'];
                        }
                    }
                    $selisih = $oldDetail->qty - $newQty;
                    if ($selisih > 0 && ($barang->stok - $selisih) < 0) {
                        return redirect()->route('barang-masuk.index')->with('error', "Gagal memperbarui! Pengurangan jumlah barang '{$barang->nama_barang}' akan membuat stok menjadi negatif.");
                    }
                }
            }
        }

        DB::transaction(function () use ($pembelian, $request) {
            $pembelian->details()->delete();

            $totalBelanja = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['harga'];
                $totalBelanja += $subtotal;

                PembelianDetail::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'id_barang' => $item['id_barang'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $subtotal,
                ]);

                if ($request->status === 'Diterima') {
                    $barang = Barang::find($item['id_barang']);
                    if ($barang) {
                        $barang->update(['harga_terakhir' => $item['harga']]);
                    }
                }
            }

            $pembelian->update([
                'no_faktur' => $request->no_faktur,
                'id_supplier' => $request->id_supplier,
                'tgl_faktur' => $request->tgl_faktur,
                'tgl_terima' => $request->tgl_terima,
                'status' => $request->status,
                'total_belanja' => $totalBelanja,
            ]);
        });

        return redirect()->route('barang-masuk.index')->with('success', "Data transaksi pembelian No. Faktur {$pembelian->no_faktur} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $this->checkAccess();

        $pembelian = Pembelian::with('details.barang')->findOrFail($id);

        if ($pembelian->status === 'Diterima') {
            foreach ($pembelian->details as $detail) {
                $barang = $detail->barang;
                if ($barang && ($barang->stok - $detail->qty) < 0) {
                    return redirect()->route('barang-masuk.index')->with('error', "Gagal menghapus! Menghapus transaksi masuk ini akan membuat stok '{$barang->nama_barang}' menjadi negatif.");
                }
            }
        }

        $noFaktur = $pembelian->no_faktur;
        $pembelian->details()->delete();
        $pembelian->delete();

        return redirect()->route('barang-masuk.index')->with('success', "Transaksi Pembelian No. Faktur {$noFaktur} berhasil dihapus.");
    }
}
