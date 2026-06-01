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
        User::create([
            'name' => 'Admin SPPG',
            'email' => 'admin@sppg.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Akun Ahli Gizi
        User::create([
            'name' => 'Ahli Gizi SPPG',
            'email' => 'gizi@sppg.com',
            'password' => Hash::make('password'),
            'role' => 'ahli gizi',
        ]);

        // 3. Akun Kepala Dapur
        User::create([
            'name' => 'Kepala Dapur SPPG',
            'email' => 'dapur@sppg.com',
            'password' => Hash::make('password'),
            'role' => 'kepala dapur',
        ]);
    }
}
