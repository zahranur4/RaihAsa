<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonaturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get regular users (not admin, not panti, not volunteer)
        $users = DB::table('users')
            ->where('is_admin', false)
            ->whereNotIn('email', ['admin@example.com'])
            ->whereNotIn('email', [
                'rina.volunteer@example.com',
                'doni.volunteer@example.com',
                'lena.volunteer@example.com',
                'ahmad.volunteer@example.com',
                'mega.volunteer@example.com'
            ])
            ->get();

        $donaturs = [
            [
                'id_user' => $users[0]->id ?? 2,
                'nama_lengkap' => 'Contoh Pengguna',
                'no_telp' => '081234567890',
                'alamat_jemput' => 'Jl. Contoh No.1, Kota Contoh',
            ],
            [
                'id_user' => $users[1]->id ?? 3,
                'nama_lengkap' => 'Budi Santoso',
                'no_telp' => '081234567891',
                'alamat_jemput' => 'Jl. Merdeka No.5, Jakarta Pusat',
            ],
            [
                'id_user' => $users[2]->id ?? 4,
                'nama_lengkap' => 'Siti Nurhaliza',
                'no_telp' => '081234567892',
                'alamat_jemput' => 'Jl. Sudirman No.10, Jakarta Selatan',
            ],
        ];

        foreach ($donaturs as $donatur) {
            DB::table('donatur_profiles')->insertOrIgnore([
                'id_user' => $donatur['id_user'],
                'nama_lengkap' => $donatur['nama_lengkap'],
                'no_telp' => $donatur['no_telp'],
                'alamat_jemput' => $donatur['alamat_jemput'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Donatur Profiles seeded successfully!');
    }
}
