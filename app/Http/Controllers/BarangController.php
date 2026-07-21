<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\SubKategori;
use App\Models\Satuan;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['subKategori.kategori', 'satuan']);

        if ($request->filled('id_subkategori') && $request->id_subkategori != 'all') {
            $query->where('id_subkategori', $request->id_subkategori);
        }

        $barangs = $query->get();
        $subKategoris = SubKategori::with('kategori')->get();
        $satuans = Satuan::all();
        
        return view('barang.index', compact('barangs', 'subKategoris', 'satuans'));
    }

    public function create()
    {
        return redirect()->route('barang.index');
    }

    public function edit($id)
    {
        return redirect()->route('barang.index');
    }

    public function store(Request $request)
    {
        if (in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg'])) {
            abort(403, 'Akses ditolak. Peran Kepala Dapur / Kepala SPPG tidak memiliki wewenang untuk tindakan ini.');
        }

        $request->validate([
            'kode_barang' => 'nullable|string|max:50|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'id_subkategori' => 'required|exists:sub_kategoris,id_subkategori',
            'id_satuan' => 'required|exists:satuans,id_satuan',
            'stok_minimum' => 'nullable|numeric|min:0',
            'harga_terakhir' => 'nullable|numeric|min:0',
        ], [
            'kode_barang.max' => 'Kode barang maksimal 50 karakter.',
            'kode_barang.unique' => 'Kode barang sudah terdaftar.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'id_subkategori.required' => 'Sub-Kategori wajib dipilih.',
            'id_subkategori.exists' => 'Sub-Kategori tidak valid.',
            'id_satuan.required' => 'Satuan barang wajib dipilih.',
            'id_satuan.exists' => 'Satuan barang tidak valid.',
        ]);

        Barang::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'id_subkategori' => $request->id_subkategori,
            'id_satuan' => $request->id_satuan,
            'stok_minimum' => $request->stok_minimum ?? 0,
            'harga_terakhir' => $request->harga_terakhir ?? 0,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        if (in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg'])) {
            abort(403, 'Akses ditolak. Peran Kepala Dapur / Kepala SPPG tidak memiliki wewenang untuk tindakan ini.');
        }

        $barang = Barang::findOrFail($id);

        $request->validate([
            'kode_barang' => 'nullable|string|max:50|unique:barangs,kode_barang,' . $id . ',id_barang',
            'nama_barang' => 'required|string|max:255',
            'id_subkategori' => 'required|exists:sub_kategoris,id_subkategori',
            'id_satuan' => 'required|exists:satuans,id_satuan',
            'stok_minimum' => 'nullable|numeric|min:0',
            'harga_terakhir' => 'nullable|numeric|min:0',
        ], [
            'kode_barang.max' => 'Kode barang maksimal 50 karakter.',
            'kode_barang.unique' => 'Kode barang sudah terdaftar.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'id_subkategori.required' => 'Sub-Kategori wajib dipilih.',
            'id_subkategori.exists' => 'Sub-Kategori tidak valid.',
            'id_satuan.required' => 'Satuan barang wajib dipilih.',
            'id_satuan.exists' => 'Satuan barang tidak valid.',
        ]);

        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'id_subkategori' => $request->id_subkategori,
            'id_satuan' => $request->id_satuan,
            'stok_minimum' => $request->stok_minimum ?? 0,
            'harga_terakhir' => $request->harga_terakhir ?? 0,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diubah!');
    }

    public function destroy($id)
    {
        if (in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg'])) {
            abort(403, 'Akses ditolak. Peran Kepala Dapur / Kepala SPPG tidak memiliki wewenang untuk tindakan ini.');
        }

        $barang = Barang::findOrFail($id);

        if ($barang->pembelianDetails()->count() > 0 || $barang->pengeluaranDetails()->count() > 0) {
            return redirect()->route('barang.index')->with('error', 'Barang tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
