<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\Satuan;
use App\Models\Supplier;
use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //

        User::updateOrCreate(
            ['username' => 'admin@gmail.com'],
            [
                'nama' => 'Siti Rahma (Admin)',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ]
        );

        // 2. Akun Ahli Gizi
        User::updateOrCreate(
            ['username' => 'gizi@gmail.com'],
            [
                'nama' => 'Ahli Gizi SPPG',
                'password' => Hash::make('password'),
                'role' => 'Ahli Gizi',
            ]
        );

        // 3. Akun Kepala Dapur
        User::updateOrCreate(
            ['username' => 'kepala@gmail.com'],
            [
                'nama' => 'Kepala Dapur',
                'password' => Hash::make('password'),
                'role' => 'Kepala Dapur',
            ]
        );

        // 4. Satuan Dummy
        $kg = Satuan::create(['nama_satuan' => 'Kg', 'keterangan' => 'Kilogram']);
        $liter = Satuan::create(['nama_satuan' => 'Liter', 'keterangan' => 'Liter']);
        $pcs = Satuan::create(['nama_satuan' => 'Pcs', 'keterangan' => 'Pieces']);

        // 5. Kategori & Sub Kategori Dummy
        $karbo = Kategori::create(['kode_kategori' => 'KAT-01', 'nama_kategori' => 'Karbohidrat']);
        $subBeras = SubKategori::create([
            'id_kategori' => $karbo->id_kategori,
            'kode_subkategori' => 'SUB-01',
            'nama_subkategori' => 'Beras Dan Olahan Padi'
        ]);

        $protein = Kategori::create(['kode_kategori' => 'KAT-02', 'nama_kategori' => 'Protein']);
        $subDaging = SubKategori::create([
            'id_kategori' => $protein->id_kategori,
            'kode_subkategori' => 'SUB-02',
            'nama_subkategori' => 'Daging Ayam & Sapi'
        ]);

        // 6. Supplier Dummy
        $sup = Supplier::create([
            'nama_supplier' => 'PT Pangan Sejahtera',
            'alamat' => 'Jl. Merdeka No. 10',
            'no_telp' => '08123456789'
        ]);

        // 7. Barang Dummy
        Barang::create([
            'kode_barang' => 'BRG-001',
            'nama_barang' => 'Beras Premium Rajawali',
            'id_subkategori' => $subBeras->id_subkategori,
            'id_satuan' => $kg->id_satuan,
            'stok_minimum' => 50,
            'harga_terakhir' => 14000
        ]);

        Barang::create([
            'kode_barang' => 'BRG-002',
            'nama_barang' => 'Daging Ayam Fillet',
            'id_subkategori' => $subDaging->id_subkategori,
            'id_satuan' => $kg->id_satuan,
            'stok_minimum' => 20,
            'harga_terakhir' => 38000
        ]);
    }
}
