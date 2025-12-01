<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan model User diimpor
use Illuminate\Support\Facades\Hash; // Pastikan Hash diimpor

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. User Admin: Mengelola seluruh aspek aplikasi[cite: 21].
        User::create([
            'name' => 'Admin Stockify',
            'email' => 'admin@stockify.test',
            'password' => Hash::make('password'), // Gunakan password yang aman di lingkungan produksi
            'role' => 'admin',
        ]);

        // 2. User Manajer Gudang: Bertanggung jawab atas manajemen stok barang[cite: 26].
        User::create([
            'name' => 'Manajer Gudang',
            'email' => 'manager@stockify.test',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        // 3. User Staff Gudang: Membantu Manajer Gudang dalam operasional gudang[cite: 32].
        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@stockify.test',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        echo "Seeder pengguna (Admin, Manager, Staff) berhasil dijalankan.\n";
    }
}