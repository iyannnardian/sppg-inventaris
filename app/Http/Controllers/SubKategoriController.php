<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubKategori;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class SubKategoriController extends Controller
{
    public function store(Request $request)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk menambah sub-kategori.');
        }

        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'kode_subkategori' => 'nullable|string|max:50|unique:sub_kategoris,kode_subkategori',
            'nama_subkategori' => 'required|string|max:255',
        ], [
            'id_kategori.required' => 'Kategori Induk wajib dipilih.',
            'id_kategori.exists' => 'Kategori Induk tidak valid.',
            'kode_subkategori.unique' => 'Kode sub-kategori sudah digunakan.',
            'nama_subkategori.required' => 'Nama sub-kategori wajib diisi.',
        ]);

        SubKategori::create([
            'id_kategori' => $request->id_kategori,
            'kode_subkategori' => $request->kode_subkategori,
            'nama_subkategori' => $request->nama_subkategori,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Sub-Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk mengubah sub-kategori.');
        }

        $subKategori = SubKategori::findOrFail($id);

        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'kode_subkategori' => 'nullable|string|max:50|unique:sub_kategoris,kode_subkategori,' . $id . ',id_subkategori',
            'nama_subkategori' => 'required|string|max:255',
        ], [
            'id_kategori.required' => 'Kategori Induk wajib dipilih.',
            'id_kategori.exists' => 'Kategori Induk tidak valid.',
            'kode_subkategori.unique' => 'Kode sub-kategori sudah digunakan.',
            'nama_subkategori.required' => 'Nama sub-kategori wajib diisi.',
        ]);

        $subKategori->update([
            'id_kategori' => $request->id_kategori,
            'kode_subkategori' => $request->kode_subkategori,
            'nama_subkategori' => $request->nama_subkategori,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Sub-Kategori berhasil diubah!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk menghapus sub-kategori.');
        }

        $subKategori = SubKategori::findOrFail($id);

        if ($subKategori->barangs()->count() > 0) {
            return redirect()->route('kategori.index')->with('error', 'Sub-Kategori tidak dapat dihapus karena masih digunakan oleh data barang.');
        }

        $subKategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Sub-Kategori berhasil dihapus!');
    }
}
