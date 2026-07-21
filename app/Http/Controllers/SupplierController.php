<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return redirect()->route('supplier.index');
    }

    public function edit($id)
    {
        return redirect()->route('supplier.index');
    }

    public function store(Request $request)
    {
        if (in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg'])) {
            abort(403, 'Akses ditolak. Peran Kepala Dapur / Kepala SPPG tidak memiliki wewenang untuk tindakan ini.');
        }

        $request->validate([
            'nama_supplier' => 'required|string|max:255|unique:suppliers,nama_supplier',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'nama_supplier.unique' => 'Nama supplier sudah terdaftar.',
            'no_telp.max' => 'Nomor telepon maksimal 20 karakter.',
        ]);

        Supplier::create([
            'nama_supplier' => $request->nama_supplier,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        if (in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg'])) {
            abort(403, 'Akses ditolak. Peran Kepala Dapur / Kepala SPPG tidak memiliki wewenang untuk tindakan ini.');
        }

        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'nama_supplier' => 'required|string|max:255|unique:suppliers,nama_supplier,' . $id . ',id_supplier',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'nama_supplier.unique' => 'Nama supplier sudah terdaftar.',
            'no_telp.max' => 'Nomor telepon maksimal 20 karakter.',
        ]);

        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diubah!');
    }

    public function destroy($id)
    {
        if (in_array(strtolower(Auth::user()->role ?? ''), ['kepala dapur', 'kepala sppg'])) {
            abort(403, 'Akses ditolak. Peran Kepala Dapur / Kepala SPPG tidak memiliki wewenang untuk tindakan ini.');
        }

        $supplier = Supplier::findOrFail($id);

        if ($supplier->barangMasuks()->count() > 0) {
            return redirect()->route('supplier.index')->with('error', 'Supplier tidak dapat dihapus karena masih terhubung dengan transaksi barang masuk.');
        }

        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus!');
    }
}
