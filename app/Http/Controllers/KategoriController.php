<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\SubKategori;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('subKategoris')->get();
        $subKategoris = SubKategori::with('kategori')->get();
        
        return view('kategori.index', compact('kategoris', 'subKategoris'));
    }

    public function create()
    {
        return redirect()->route('kategori.index');
    }

    public function edit($id)
    {
        return redirect()->route('kategori.index');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk menambah kategori.');
        }

        $request->validate([
            'kode_kategori' => 'nullable|string|max:50|unique:kategoris,kode_kategori',
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
        ], [
            'kode_kategori.unique' => 'Kode kategori sudah digunakan.',
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
        ]);

        Kategori::create([
            'kode_kategori' => $request->kode_kategori,
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori Utama berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk mengubah kategori.');
        }

        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'kode_kategori' => 'nullable|string|max:50|unique:kategoris,kode_kategori,' . $id . ',id_kategori',
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $id . ',id_kategori',
        ], [
            'kode_kategori.unique' => 'Kode kategori sudah digunakan.',
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
        ]);

        $kategori->update([
            'kode_kategori' => $request->kode_kategori,
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori Utama berhasil diubah!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk menghapus kategori.');
        }

        $kategori = Kategori::findOrFail($id);

        if ($kategori->subKategoris()->count() > 0) {
            return redirect()->route('kategori.index')->with('error', 'Kategori Utama tidak dapat dihapus karena masih memiliki Sub-Kategori.');
        }

        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori Utama berhasil dihapus!');
    }
}
