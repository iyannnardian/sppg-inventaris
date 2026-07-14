<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::all();
        
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

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $query = BarangMasuk::with(['barang', 'supplier', 'user']);

        if ($tanggalAwal) {
            $query->where('tanggal_masuk', '>=', $tanggalAwal);
        }
        if ($tanggalAkhir) {
            $query->where('tanggal_masuk', '<=', $tanggalAkhir);
        }

        $transaksis = $query->orderBy('tanggal_masuk', 'desc')->orderBy('created_at', 'desc')->get();

        return view('barang-masuk.index', compact('barangs', 'suppliers', 'transaksis', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk mencatat transaksi masuk.');
        }

        $request->validate([
            'id_barang' => 'required|exists:barangs,id_barang',
            'id_supplier' => 'required|exists:suppliers,id_supplier',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
        ], [
            'id_barang.required' => 'Barang wajib dipilih.',
            'id_barang.exists' => 'Barang tidak valid.',
            'id_supplier.required' => 'Supplier wajib dipilih.',
            'id_supplier.exists' => 'Supplier tidak valid.',
            'jumlah.required' => 'Jumlah barang wajib diisi.',
            'jumlah.integer' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal 1.',
            'harga.required' => 'Harga beli satuan wajib diisi.',
            'harga.integer' => 'Harga beli satuan harus berupa angka.',
            'harga.min' => 'Harga beli satuan tidak boleh negatif.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Format tanggal tidak valid.',
        ]);

        BarangMasuk::create([
            'id_barang' => $request->id_barang,
            'id_supplier' => $request->id_supplier,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
            'tanggal_masuk' => $request->tanggal_masuk,
            'id_user' => Auth::id(),
        ]);

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil dicatat!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk mengubah transaksi masuk.');
        }

        $masuk = BarangMasuk::findOrFail($id);
        $oldBarang = $masuk->barang;
        $oldJumlah = $masuk->jumlah;
        $oldIdBarang = $masuk->id_barang;

        $request->validate([
            'id_barang' => 'required|exists:barangs,id_barang',
            'id_supplier' => 'required|exists:suppliers,id_supplier',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
        ], [
            'id_barang.required' => 'Barang wajib dipilih.',
            'id_barang.exists' => 'Barang tidak valid.',
            'id_supplier.required' => 'Supplier wajib dipilih.',
            'id_supplier.exists' => 'Supplier tidak valid.',
            'jumlah.required' => 'Jumlah barang wajib diisi.',
            'jumlah.integer' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal 1.',
            'harga.required' => 'Harga beli satuan wajib diisi.',
            'harga.integer' => 'Harga beli satuan harus berupa angka.',
            'harga.min' => 'Harga beli satuan tidak boleh negatif.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Format tanggal tidak valid.',
        ]);

        if ($request->id_barang != $oldIdBarang) {
            if (($oldBarang->stok - $oldJumlah) < 0) {
                return redirect()->route('barang-masuk.index')->with('error', "Gagal mengubah transaksi. Barang lama ({$oldBarang->nama_barang}) memiliki stok kritis, pengurangan ini akan membuat stok menjadi negatif.");
            }
        } else {
            $selisih = $oldJumlah - $request->jumlah;
            if ($selisih > 0 && ($oldBarang->stok - $selisih) < 0) {
                return redirect()->route('barang-masuk.index')->with('error', "Gagal mengubah transaksi. Pengurangan jumlah barang masuk ini akan membuat stok {$oldBarang->nama_barang} menjadi negatif.");
            }
        }

        $masuk->update([
            'id_barang' => $request->id_barang,
            'id_supplier' => $request->id_supplier,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
            'tanggal_masuk' => $request->tanggal_masuk,
        ]);

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil diubah!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk menghapus transaksi masuk.');
        }

        $masuk = BarangMasuk::findOrFail($id);
        $barang = $masuk->barang;
        if (($barang->stok - $masuk->jumlah) < 0) {
            return redirect()->route('barang-masuk.index')->with('error', 'Transaksi tidak dapat dihapus karena akan menyebabkan stok barang menjadi negatif.');
        }

        $masuk->delete();
        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil dihapus!');
    }
}
