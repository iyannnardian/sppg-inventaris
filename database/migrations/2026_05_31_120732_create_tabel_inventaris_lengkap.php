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
            $table->string('kode_kategori', 20)->unique();
            $table->string('nama_kategori', 100);
            $table->timestamps();
        });

        // 2. Tabel Sub Kategori
        Schema::create('sub_kategoris', function (Blueprint $table) {
            $table->id('id_subkategori');
            $table->foreignId('id_kategori')->constrained('kategoris', 'id_kategori')->onDelete('cascade');
            $table->string('kode_subkategori', 20)->unique();
            $table->string('nama_subkategori', 100);
            $table->timestamps();
        });

        // 3. Tabel Satuan
        Schema::create('satuans', function (Blueprint $table) {
            $table->id('id_satuan');
            $table->string('nama_satuan', 50);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Supplier
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('id_supplier');
            $table->string('kode_supplier', 20)->unique();
            $table->string('nama_supplier', 100);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->timestamps();
        });

        // 5. Tabel Barang
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('kode_barang', 30)->unique();
            $table->string('nama_barang', 100);
            $table->foreignId('id_subkategori')->constrained('sub_kategoris', 'id_subkategori')->onDelete('cascade');
            $table->foreignId('id_satuan')->constrained('satuans', 'id_satuan')->onDelete('cascade');
            $table->decimal('stok_minimum', 18, 2)->default(0);
            $table->decimal('harga_terakhir', 18, 2)->default(0);
            $table->timestamps();
        });

        // 6. Tabel Pembelian
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->string('no_faktur', 30)->unique();
            $table->date('tgl_faktur');
            $table->date('tgl_terima');
            $table->foreignId('id_supplier')->constrained('suppliers', 'id_supplier')->onDelete('cascade');
            $table->decimal('total_belanja', 18, 2)->default(0);
            $table->enum('status', ['Draft', 'Diterima', 'Batal'])->default('Draft');
            $table->timestamps();
        });

        // 7. Tabel Pembelian Detail
        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_pembelian')->constrained('pembelians', 'id_pembelian')->onDelete('cascade');
            $table->foreignId('id_barang')->constrained('barangs', 'id_barang')->onDelete('cascade');
            $table->decimal('qty', 18, 2);
            $table->decimal('harga', 18, 2);
            $table->decimal('subtotal', 18, 2);
            $table->timestamps();
        });

        // 8. Tabel Pengeluaran
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id('id_pengeluaran');
            $table->date('tgl_pengeluaran');
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->timestamps();
        });

        // 9. Tabel Pengeluaran Detail
        Schema::create('pengeluaran_details', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_pengeluaran')->constrained('pengeluarans', 'id_pengeluaran')->onDelete('cascade');
            $table->foreignId('id_barang')->constrained('barangs', 'id_barang')->onDelete('cascade');
            $table->decimal('qty', 18, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran_details');
        Schema::dropIfExists('pengeluarans');
        Schema::dropIfExists('pembelian_details');
        Schema::dropIfExists('pembelians');
        Schema::dropIfExists('barangs');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('satuans');
        Schema::dropIfExists('sub_kategoris');
        Schema::dropIfExists('kategoris');
    }
};
