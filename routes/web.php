<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SubKategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Halaman Dashboard AdminLTE
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // CRUD Kategori
    Route::resource('kategori', KategoriController::class);
    Route::resource('sub-kategori', SubKategoriController::class);

    // CRUD Satuan Barang
    Route::resource('satuan', SatuanController::class);

    // CRUD Supplier
    Route::resource('supplier', SupplierController::class);

    // CRUD Barang
    Route::resource('barang', BarangController::class);

    // Transaksi Pembelian & Barang Masuk
    Route::resource('barang-masuk', BarangMasukController::class)->except(['create', 'edit']);
    Route::patch('barang-masuk/{id}/status', [BarangMasukController::class, 'updateStatus'])->name('barang-masuk.update-status');
    Route::get('pembelian', function () { return redirect()->route('barang-masuk.index'); })->name('pembelian.index');
    // Transaksi Pengeluaran & Barang Keluar
    Route::resource('barang-keluar', BarangKeluarController::class)->except(['create', 'edit']);
    Route::get('pengeluaran', function () { return redirect()->route('barang-keluar.index'); })->name('pengeluaran.index');

    // Laporan Stok
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'exportExcel'])->name('laporan.export');

    // Kelola User (CRUD)
    Route::resource('users', UserController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
