<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pengeluaran;
use App\Models\PengeluaranDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    private function checkAccess()
    {
        if (Auth::check() && strtolower(Auth::user()->role ?? '') === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk mencatat transaksi pengeluaran.');
        }
    }

    public function index(Request $request)
    {
        $barangs = Barang::with(['satuan', 'subKategori.kategori'])->orderBy('kode_barang', 'asc')->get();

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $query = Pengeluaran::with(['user', 'details.barang.satuan']);

        if ($tanggalAwal) {
            $query->where('tgl_pengeluaran', '>=', $tanggalAwal);
        }
        if ($tanggalAkhir) {
            $query->where('tgl_pengeluaran', '<=', $tanggalAkhir);
        }

        $transaksis = $query->orderBy('tgl_pengeluaran', 'desc')
                            ->orderBy('id_pengeluaran', 'desc')
                            ->get();

        return view('barang-keluar.index', compact(
            'barangs',
            'transaksis',
            'tanggalAwal',
            'tanggalAkhir'
        ));
    }

    public function show($id)
    {
        $pengeluaran = Pengeluaran::with(['user', 'details.barang.satuan'])->findOrFail($id);
        return response()->json($pengeluaran);
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $request->validate([
            'tgl_pengeluaran' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barangs,id_barang',
            'items.*.qty' => 'required|numeric|min:0.01',
        ], [
            'tgl_pengeluaran.required' => 'Tanggal pengeluaran wajib diisi.',
            'items.required' => 'Minimal 1 barang harus dimasukkan dalam rincian pengeluaran.',
            'items.min' => 'Minimal 1 barang harus dimasukkan dalam rincian pengeluaran.',
            'items.*.id_barang.required' => 'Barang wajib dipilih.',
            'items.*.qty.required' => 'Jumlah barang (qty) wajib diisi.',
            'items.*.qty.min' => 'Jumlah barang minimal 0.01.',
        ]);

        // Validate stock availability for all items before saving
        foreach ($request->items as $item) {
            $barang = Barang::find($item['id_barang']);
            if ($barang) {
                if ($barang->stok < $item['qty']) {
                    return redirect()->route('barang-keluar.index')
                        ->with('error', "Gagal mencatat pengeluaran! Stok untuk barang '{$barang->nama_barang}' tidak mencukupi (stok saat ini: {$barang->stok}, diminta: {$item['qty']}).")
                        ->withInput();
                }
            }
        }

        DB::transaction(function () use ($request) {
            $pengeluaran = Pengeluaran::create([
                'tgl_pengeluaran' => $request->tgl_pengeluaran,
                'id_user' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                PengeluaranDetail::create([
                    'id_pengeluaran' => $pengeluaran->id_pengeluaran,
                    'id_barang' => $item['id_barang'],
                    'qty' => $item['qty'],
                ]);
            }
        });

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi pengeluaran barang berhasil dicatat dan stok barang otomatis berkurang!');
    }

    public function update(Request $request, $id)
    {
        $this->checkAccess();

        $pengeluaran = Pengeluaran::with('details.barang')->findOrFail($id);

        $request->validate([
            'tgl_pengeluaran' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barangs,id_barang',
            'items.*.qty' => 'required|numeric|min:0.01',
        ]);

        // Validate stock availability considering existing detail amounts
        foreach ($request->items as $item) {
            $barang = Barang::find($item['id_barang']);
            if ($barang) {
                $existingQtyInThisTrans = $pengeluaran->details->where('id_barang', $item['id_barang'])->sum('qty');
                $availableStokWithReturn = $barang->stok + $existingQtyInThisTrans;

                if ($availableStokWithReturn < $item['qty']) {
                    return redirect()->route('barang-keluar.index')
                        ->with('error', "Gagal memperbarui pengeluaran! Stok barang '{$barang->nama_barang}' tidak mencukupi (stok tersedia: {$availableStokWithReturn}, diminta: {$item['qty']}).");
                }
            }
        }

        DB::transaction(function () use ($pengeluaran, $request) {
            $pengeluaran->details()->delete();

            $pengeluaran->update([
                'tgl_pengeluaran' => $request->tgl_pengeluaran,
                'id_user' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                PengeluaranDetail::create([
                    'id_pengeluaran' => $pengeluaran->id_pengeluaran,
                    'id_barang' => $item['id_barang'],
                    'qty' => $item['qty'],
                ]);
            }
        });

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi pengeluaran barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->checkAccess();

        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->details()->delete();
        $pengeluaran->delete();

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi pengeluaran barang berhasil dihapus dan stok barang telah dikembalikan.');
    }
}
