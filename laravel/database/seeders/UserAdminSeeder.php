<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat akun user contoh jika belum ada
        DB::table('users')->updateOrInsert(
            ['email' => 'user@example.com'],
            [
                'nama' => 'Contoh Pengguna',
                'email' => 'user@example.com',
                'email_verified_at' => now(),
                'kata_sandi' => Hash::make('User1234'), // password: User1234 (memenuhi aturan)
                'nomor_telepon' => '081234567890',
                'alamat' => 'Jl. Contoh No.1, Kota Contoh',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Buat akun admin default di tabel admins (simpan juga email)
        DB::table('admins')->updateOrInsert(
            ['username' => 'admin'],
            [
                'email' => 'admin@example.com',
                'kata_sandi' => Hash::make('@dM1nR4ih4sa'), // password seperti diminta
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Buat akun pengguna admin untuk login (is_admin = true)
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'Administrator',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'kata_sandi' => Hash::make('@dM1nR4ih4sa'),
                'nomor_telepon' => null,
                'alamat' => null,
                'is_admin' => true,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
