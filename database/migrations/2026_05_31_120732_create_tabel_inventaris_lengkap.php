<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Kategori
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori');
            $table->timestamps();
        });

        // 2. Tabel Barang
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('nama_barang');
            $table->string('satuan');
            $table->foreignId('id_kategori')->constrained('kategoris', 'id_kategori')->onDelete('cascade');
            $table->integer('stok_awal')->default(0);
            $table->timestamps();
        });

        // 3. Tabel Supplier
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('id_supplier');
            $table->string('nama_supplier');
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Barang Masuk (Ditambahkan kolom harga langsung)
        Schema::create('barang_masuks', function (Blueprint $table) {
            $table->id('id_masuk');
            $table->foreignId('id_barang')->constrained('barangs', 'id_barang')->onDelete('cascade');
            $table->foreignId('id_supplier')->constrained('suppliers', 'id_supplier')->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('harga')->default(0);
            $table->date('tanggal_masuk');
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->timestamps();
        });

        // 5. Tabel Barang Keluar
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id('id_keluar');
            $table->foreignId('id_barang')->constrained('barangs', 'id_barang')->onDelete('cascade');
            $table->integer('jumlah');
            $table->date('tanggal_keluar');
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang_keluars');
        Schema::dropIfExists('barang_masuks');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('barangs');
        Schema::dropIfExists('kategoris');
    }
};
