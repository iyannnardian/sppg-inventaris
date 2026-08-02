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
        if (strtolower(Auth::user()->role ?? '') === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk tindakan ini.');
        }

        $namaSatuan = trim($request->nama_satuan ?? '');
        $keterangan = $request->keterangan ? trim($request->keterangan) : null;

        $request->merge([
            'nama_satuan' => $namaSatuan,
            'keterangan' => $keterangan,
        ]);

        $request->validate([
            'nama_satuan' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama_satuan.required' => 'Kode satuan wajib diisi.',
            'nama_satuan.max' => 'Kode satuan maksimal 50 karakter.',
            'keterangan.max' => 'Nama satuan maksimal 255 karakter.',
        ]);

        // Cek duplikasi silang & case-insensitive untuk Kode Satuan
        $existingKode = Satuan::whereRaw('LOWER(nama_satuan) = ? OR LOWER(keterangan) = ?', [strtolower($namaSatuan), strtolower($namaSatuan)])->first();
        if ($existingKode) {
            return redirect()->back()->withInput()->withErrors(['nama_satuan' => 'Kode atau Nama Satuan "' . $namaSatuan . '" sudah terdaftar pada sistem.']);
        }

        // Cek duplikasi silang & case-insensitive untuk Nama Satuan
        if ($keterangan) {
            $existingKet = Satuan::whereRaw('LOWER(nama_satuan) = ? OR LOWER(keterangan) = ?', [strtolower($keterangan), strtolower($keterangan)])->first();
            if ($existingKet) {
                return redirect()->back()->withInput()->withErrors(['keterangan' => 'Nama atau Kode Satuan "' . $keterangan . '" sudah terdaftar pada sistem.']);
            }
        }

        Satuan::create([
            'nama_satuan' => $request->nama_satuan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('satuan.index')->with('success', 'Satuan barang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        if (strtolower(Auth::user()->role ?? '') === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk tindakan ini.');
        }

        $satuan = Satuan::findOrFail($id);

        $namaSatuan = trim($request->nama_satuan ?? '');
        $keterangan = $request->keterangan ? trim($request->keterangan) : null;

        $request->merge([
            'nama_satuan' => $namaSatuan,
            'keterangan' => $keterangan,
        ]);

        $request->validate([
            'nama_satuan' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama_satuan.required' => 'Kode satuan wajib diisi.',
            'nama_satuan.max' => 'Kode satuan maksimal 50 karakter.',
            'keterangan.max' => 'Nama satuan maksimal 255 karakter.',
        ]);

        // Cek duplikasi silang & case-insensitive untuk update Kode Satuan
        $existingKode = Satuan::where('id_satuan', '!=', $id)
            ->where(function ($q) use ($namaSatuan) {
                $q->whereRaw('LOWER(nama_satuan) = ?', [strtolower($namaSatuan)])
                  ->orWhereRaw('LOWER(keterangan) = ?', [strtolower($namaSatuan)]);
            })->first();
        if ($existingKode) {
            return redirect()->back()->withInput()->withErrors(['nama_satuan' => 'Kode atau Nama Satuan "' . $namaSatuan . '" sudah terdaftar pada sistem.']);
        }

        // Cek duplikasi silang & case-insensitive untuk update Nama Satuan
        if ($keterangan) {
            $existingKet = Satuan::where('id_satuan', '!=', $id)
                ->where(function ($q) use ($keterangan) {
                    $q->whereRaw('LOWER(nama_satuan) = ?', [strtolower($keterangan)])
                      ->orWhereRaw('LOWER(keterangan) = ?', [strtolower($keterangan)]);
                })->first();
            if ($existingKet) {
                return redirect()->back()->withInput()->withErrors(['keterangan' => 'Nama atau Kode Satuan "' . $keterangan . '" sudah terdaftar pada sistem.']);
            }
        }

        $satuan->update([
            'nama_satuan' => $request->nama_satuan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('satuan.index')->with('success', 'Satuan barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (strtolower(Auth::user()->role ?? '') === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk tindakan ini.');
        }

        $satuan = Satuan::findOrFail($id);

        // Cek apakah satuan sedang digunakan oleh data barang
        $terpakai = Barang::where('id_satuan', $satuan->id_satuan)->count();
        if ($terpakai > 0) {
            return redirect()->route('satuan.index')->with('error', 'Satuan "' . $satuan->nama_satuan . '" tidak dapat dihapus karena sedang digunakan oleh ' . $terpakai . ' data barang.');
        }

        $satuan->delete();

        return redirect()->route('satuan.index')->with('success', 'Satuan barang berhasil dihapus!');
    }
}
