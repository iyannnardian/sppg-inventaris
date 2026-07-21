<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Satuan;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::all();
        return view('satuan.index', compact('satuans'));
    }

    public function create()
    {
        return redirect()->route('satuan.index');
    }

    public function edit($id)
    {
        return redirect()->route('satuan.index');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk menambah satuan.');
        }

        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuans,nama_satuan',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama_satuan.required' => 'Nama satuan wajib diisi.',
            'nama_satuan.max' => 'Nama satuan maksimal 50 karakter.',
            'nama_satuan.unique' => 'Nama satuan sudah terdaftar.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ]);

        Satuan::create([
            'nama_satuan' => trim($request->nama_satuan),
            'keterangan' => $request->keterangan ? trim($request->keterangan) : null,
        ]);

        return redirect()->route('satuan.index')->with('success', 'Satuan barang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk mengubah satuan.');
        }

        $satuan = Satuan::findOrFail($id);

        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuans,nama_satuan,' . $id . ',id_satuan',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama_satuan.required' => 'Nama satuan wajib diisi.',
            'nama_satuan.max' => 'Nama satuan maksimal 50 karakter.',
            'nama_satuan.unique' => 'Nama satuan sudah terdaftar.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ]);

        $satuan->update([
            'nama_satuan' => trim($request->nama_satuan),
            'keterangan' => $request->keterangan ? trim($request->keterangan) : null,
        ]);

        return redirect()->route('satuan.index')->with('success', 'Satuan barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk menghapus satuan.');
        }

        $satuan = Satuan::findOrFail($id);

        // Cek apakah satuan sedang digunakan oleh data barang
        $terpakai = Barang::where('satuan', $satuan->nama_satuan)->count();
        if ($terpakai > 0) {
            return redirect()->route('satuan.index')->with('error', 'Satuan "' . $satuan->nama_satuan . '" tidak dapat dihapus karena sedang digunakan oleh ' . $terpakai . ' data barang.');
        }

        $satuan->delete();

        return redirect()->route('satuan.index')->with('success', 'Satuan barang berhasil dihapus!');
    }
}
