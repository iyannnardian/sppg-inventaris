<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Siti Rahma (Admin)',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ]
        );

        // 2. Akun Ahli Gizi
        User::updateOrCreate(
            ['username' => 'gizi'],
            [
                'nama' => 'Ahli Gizi SPPG',
                'password' => Hash::make('password'),
                'role' => 'Ahli Gizi',
            ]
        );

        // 3. Akun Kepala Dapur
        User::updateOrCreate(
            ['username' => 'kepala'],
            [
                'nama' => 'Kepala Dapur',
                'password' => Hash::make('password'),
                'role' => 'Kepala Dapur',
            ]
        );
    }
}
