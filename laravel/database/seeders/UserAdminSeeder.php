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
        // ==================== ADMIN ACCOUNT ====================
        // Buat akun admin default di tabel admins
        DB::table('admins')->updateOrInsert(
            ['username' => 'admin'],
            [
                'email' => 'admin@example.com',
                'kata_sandi' => Hash::make('@dM1nR4ih4sa'), // password: @dM1nR4ih4sa
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
                'nomor_telepon' => '082112345678',
                'alamat' => 'Jl. Admin No.1, Jakarta',
                'is_admin' => true,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // ==================== REGULAR USERS (DONATUR) ====================
        // User 1 - Regular User
        DB::table('users')->updateOrInsert(
            ['email' => 'user@example.com'],
            [
                'nama' => 'Contoh Pengguna',
                'email' => 'user@example.com',
                'email_verified_at' => now(),
                'kata_sandi' => Hash::make('User1234'),
                'nomor_telepon' => '081234567890',
                'alamat' => 'Jl. Contoh No.1, Kota Contoh',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // User 2 - Regular User
        DB::table('users')->updateOrInsert(
            ['email' => 'budi.donatur@example.com'],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi.donatur@example.com',
                'email_verified_at' => now(),
                'kata_sandi' => Hash::make('Budi1234'),
                'nomor_telepon' => '081234567891',
                'alamat' => 'Jl. Merdeka No.5, Jakarta Pusat',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // User 3 - Regular User
        DB::table('users')->updateOrInsert(
            ['email' => 'siti.donatur@example.com'],
            [
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti.donatur@example.com',
                'email_verified_at' => now(),
                'kata_sandi' => Hash::make('Siti1234'),
                'nomor_telepon' => '081234567892',
                'alamat' => 'Jl. Sudirman No.10, Jakarta Selatan',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // ==================== VOLUNTEER USERS ====================
        // Volunteer 1 - Verified
        $volunteerId1 = DB::table('users')->insertGetId([
            'nama' => 'Rina Wijaya',
            'email' => 'rina.volunteer@example.com',
            'email_verified_at' => now(),
            'kata_sandi' => Hash::make('Rina1234'),
            'nomor_telepon' => '081234567893',
            'alamat' => 'Jl. Ahmad Yani No.15, Bandung',
            'is_admin' => false,
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('relawan_profiles')->insert([
            'id_user' => $volunteerId1,
            'nama_lengkap' => 'Rina Wijaya',
            'nik' => '3201234567890123',
            'skill' => 'Mengajar anak-anak, membaca, menulis',
            'kategori' => 'Kesehatan & Gizi',
            'ketersediaan' => 'Weekend (Pagi & Siang)',
            'status_verif' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Volunteer 2 - Pending Verification
        $volunteerId2 = DB::table('users')->insertGetId([
            'nama' => 'Doni Hermawan',
            'email' => 'doni.volunteer@example.com',
            'email_verified_at' => now(),
            'kata_sandi' => Hash::make('Doni1234'),
            'nomor_telepon' => '081234567894',
            'alamat' => 'Jl. Gatot Subroto No.20, Surabaya',
            'is_admin' => false,
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('relawan_profiles')->insert([
            'id_user' => $volunteerId2,
            'nama_lengkap' => 'Doni Hermawan',
            'nik' => '3501234567890124',
            'skill' => 'Masak, mengorganisir acara, fotografi',
            'kategori' => 'Kemanusiaan & Kebencanaan',
            'ketersediaan' => 'Weekday (Malam)',
            'status_verif' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Volunteer 3 - Verified
        $volunteerId3 = DB::table('users')->insertGetId([
            'nama' => 'Lena Kusuma',
            'email' => 'lena.volunteer@example.com',
            'email_verified_at' => now(),
            'kata_sandi' => Hash::make('Lena1234'),
            'nomor_telepon' => '081234567895',
            'alamat' => 'Jl. Diponegoro No.25, Medan',
            'is_admin' => false,
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('relawan_profiles')->insert([
            'id_user' => $volunteerId3,
            'nama_lengkap' => 'Lena Kusuma',
            'nik' => '2001234567890125',
            'skill' => 'Kesehatan, presentasi, komunikasi, pelatihan',
            'kategori' => 'Edukasi & Literasi',
            'ketersediaan' => 'Setiap hari',
            'status_verif' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Volunteer 4 - Pending Verification
        $volunteerId4 = DB::table('users')->insertGetId([
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad.volunteer@example.com',
            'email_verified_at' => now(),
            'kata_sandi' => Hash::make('Ahmad1234'),
            'nomor_telepon' => '081234567896',
            'alamat' => 'Jl. Imam Bonjol No.30, Yogyakarta',
            'is_admin' => false,
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('relawan_profiles')->insert([
            'id_user' => $volunteerId4,
            'nama_lengkap' => 'Ahmad Rizki',
            'nik' => '3401234567890126',
            'skill' => 'Desain grafis, kreatif, seni',
            'kategori' => 'Kreatif & Psikososial',
            'ketersediaan' => 'Weekend (Siang)',
            'status_verif' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Volunteer 5 - Rejected
        $volunteerId5 = DB::table('users')->insertGetId([
            'nama' => 'Mega Santoso',
            'email' => 'mega.volunteer@example.com',
            'email_verified_at' => now(),
            'kata_sandi' => Hash::make('Mega1234'),
            'nomor_telepon' => '081234567897',
            'alamat' => 'Jl. Hayam Wuruk No.35, Malang',
            'is_admin' => false,
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('relawan_profiles')->insert([
            'id_user' => $volunteerId5,
            'nama_lengkap' => 'Mega Santoso',
            'nik' => '6501234567890127',
            'skill' => 'Belum ada data',
            'kategori' => 'Dukungan Operasional & Lingkungan',
            'ketersediaan' => 'Belum diset',
            'status_verif' => 'rejected',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
