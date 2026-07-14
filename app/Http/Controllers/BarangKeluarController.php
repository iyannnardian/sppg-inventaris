<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\Auth;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::all();
        
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $query = BarangKeluar::with(['barang', 'user']);

        if ($tanggalAwal) {
            $query->where('tanggal_keluar', '>=', $tanggalAwal);
        }
        if ($tanggalAkhir) {
            $query->where('tanggal_keluar', '<=', $tanggalAkhir);
        }

        $transaksis = $query->orderBy('tanggal_keluar', 'desc')->orderBy('created_at', 'desc')->get();

        return view('barang-keluar.index', compact('barangs', 'transaksis', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk mencatat transaksi keluar.');
        }

        $request->validate([
            'id_barang' => 'required|exists:barangs,id_barang',
            'jumlah' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
        ], [
            'id_barang.required' => 'Barang wajib dipilih.',
            'id_barang.exists' => 'Barang tidak valid.',
            'jumlah.required' => 'Jumlah barang wajib diisi.',
            'jumlah.integer' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal 1.',
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date' => 'Format tanggal tidak valid.',
        ]);

        $barang = Barang::findOrFail($request->id_barang);

        if ($barang->stok < $request->jumlah) {
            return redirect()->route('barang-keluar.index')
                ->with('error', "Stok tidak mencukupi! Stok saat ini untuk {$barang->nama_barang} adalah {$barang->stok} {$barang->satuan}.")
                ->withInput();
        }

        BarangKeluar::create([
            'id_barang' => $request->id_barang,
            'jumlah' => $request->jumlah,
            'tanggal_keluar' => $request->tanggal_keluar,
            'id_user' => Auth::id(),
        ]);

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil dicatat!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk mengubah transaksi keluar.');
        }

        $keluar = BarangKeluar::findOrFail($id);
        $oldBarang = $keluar->barang;
        $oldJumlah = $keluar->jumlah;
        $oldIdBarang = $keluar->id_barang;

        $request->validate([
            'id_barang' => 'required|exists:barangs,id_barang',
            'jumlah' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
        ], [
            'id_barang.required' => 'Barang wajib dipilih.',
            'id_barang.exists' => 'Barang tidak valid.',
            'jumlah.required' => 'Jumlah barang wajib diisi.',
            'jumlah.integer' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal 1.',
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date' => 'Format tanggal tidak valid.',
        ]);

        if ($request->id_barang != $oldIdBarang) {
            $barangBaru = Barang::findOrFail($request->id_barang);
            if ($barangBaru->stok < $request->jumlah) {
                return redirect()->route('barang-keluar.index')->with('error', "Stok tidak mencukupi! Stok saat ini untuk {$barangBaru->nama_barang} adalah {$barangBaru->stok} {$barangBaru->satuan}.");
            }
        } else {
            $selisih = $request->jumlah - $oldJumlah;
            if ($selisih > 0 && ($oldBarang->stok - $selisih) < 0) {
                return redirect()->route('barang-keluar.index')->with('error', "Stok tidak mencukupi! Stok saat ini untuk {$oldBarang->nama_barang} adalah {$oldBarang->stok} {$oldBarang->satuan}.");
            }
        }

        $keluar->update([
            'id_barang' => $request->id_barang,
            'jumlah' => $request->jumlah,
            'tanggal_keluar' => $request->tanggal_keluar,
        ]);

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil diubah!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'kepala dapur') {
            abort(403, 'Akses ditolak. Peran Kepala Dapur tidak memiliki wewenang untuk menghapus transaksi keluar.');
        }

        $keluar = BarangKeluar::findOrFail($id);
        $keluar->delete();
        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil dihapus!');
    }
}
