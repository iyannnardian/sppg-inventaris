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
        if (strtolower(Auth::user()->role ?? '') === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk tindakan ini.');
        }

        $request->merge([
            'kode_subkategori' => $request->kode_subkategori ? trim($request->kode_subkategori) : null,
            'nama_subkategori' => trim($request->nama_subkategori ?? ''),
        ]);

        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'kode_subkategori' => 'nullable|string|max:50|unique:sub_kategoris,kode_subkategori',
            'nama_subkategori' => 'required|string|max:255|unique:sub_kategoris,nama_subkategori',
        ], [
            'id_kategori.required' => 'Kategori Induk wajib dipilih.',
            'id_kategori.exists' => 'Kategori Induk tidak valid.',
            'kode_subkategori.unique' => 'Kode sub-kategori sudah digunakan.',
            'nama_subkategori.required' => 'Nama sub-kategori wajib diisi.',
            'nama_subkategori.unique' => 'Nama sub-kategori sudah terdaftar.',
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
        if (strtolower(Auth::user()->role ?? '') === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk tindakan ini.');
        }

        $subKategori = SubKategori::findOrFail($id);

        $request->merge([
            'kode_subkategori' => $request->kode_subkategori ? trim($request->kode_subkategori) : null,
            'nama_subkategori' => trim($request->nama_subkategori ?? ''),
        ]);

        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'kode_subkategori' => 'nullable|string|max:50|unique:sub_kategoris,kode_subkategori,' . $id . ',id_subkategori',
            'nama_subkategori' => 'required|string|max:255|unique:sub_kategoris,nama_subkategori,' . $id . ',id_subkategori',
        ], [
            'id_kategori.required' => 'Kategori Induk wajib dipilih.',
            'id_kategori.exists' => 'Kategori Induk tidak valid.',
            'kode_subkategori.unique' => 'Kode sub-kategori sudah digunakan.',
            'nama_subkategori.required' => 'Nama sub-kategori wajib diisi.',
            'nama_subkategori.unique' => 'Nama sub-kategori sudah terdaftar.',
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
        if (strtolower(Auth::user()->role ?? '') === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk tindakan ini.');
        }

        $subKategori = SubKategori::findOrFail($id);

        if ($subKategori->barangs()->count() > 0) {
            return redirect()->route('kategori.index')->with('error', 'Sub-Kategori tidak dapat dihapus karena masih digunakan oleh data barang.');
        }

        $subKategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Sub-Kategori berhasil dihapus!');
    }
}
