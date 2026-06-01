<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    // Halaman Dashboard AdminLTE
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // CRUD Kategori
    Route::resource('kategori', KategoriController::class);

    // CRUD Supplier
    Route::resource('supplier', SupplierController::class);

    // CRUD Barang
    Route::resource('barang', BarangController::class);

    // Transaksi (Masuk / Keluar)
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/masuk/create', [TransaksiController::class, 'createMasuk'])->name('transaksi.createMasuk');
    Route::get('/transaksi/keluar/create', [TransaksiController::class, 'createKeluar'])->name('transaksi.createKeluar');
    Route::get('/transaksi/masuk/{id}/edit', [TransaksiController::class, 'editMasuk'])->name('transaksi.editMasuk');
    Route::get('/transaksi/keluar/{id}/edit', [TransaksiController::class, 'editKeluar'])->name('transaksi.editKeluar');
    Route::post('/transaksi/masuk', [TransaksiController::class, 'storeMasuk'])->name('transaksi.storeMasuk');
    Route::post('/transaksi/keluar', [TransaksiController::class, 'storeKeluar'])->name('transaksi.storeKeluar');
    Route::put('/transaksi/masuk/{id}', [TransaksiController::class, 'updateMasuk'])->name('transaksi.updateMasuk');
    Route::put('/transaksi/keluar/{id}', [TransaksiController::class, 'updateKeluar'])->name('transaksi.updateKeluar');
    Route::delete('/transaksi/masuk/{id}', [TransaksiController::class, 'destroyMasuk'])->name('transaksi.destroyMasuk');
    Route::delete('/transaksi/keluar/{id}', [TransaksiController::class, 'destroyKeluar'])->name('transaksi.destroyKeluar');

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
