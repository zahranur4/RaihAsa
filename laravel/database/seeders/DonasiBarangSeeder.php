<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonasiBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get donatur profiles and panti profiles
        $donatur = DB::table('donatur_profiles')->pluck('id_donatur')->toArray();
        $panti = DB::table('panti_profiles')->pluck('id_panti')->toArray();

        if (empty($donatur) || empty($panti)) {
            $this->command->warn('No donatur or panti profiles found. Please run donatur and panti seeder first.');
            return;
        }

        $donations = [
            // Donatur 1 - Multiple donations
            [
                'id_donatur' => $donatur[0],
                'nama_barang' => 'Buku Pelajaran SD (50 buah)',
                'kategori' => 'Pendidikan',
                'foto' => null,
                'status' => 'delivered',
                'id_panti' => $panti[0] ?? $panti[array_rand($panti)],
                'created_at' => Carbon::create(2026, 1, 5, 10, 0, 0),
            ],
            [
                'id_donatur' => $donatur[0],
                'nama_barang' => 'Pakaian Anak (100 pcs)',
                'kategori' => 'Pakaian',
                'foto' => null,
                'status' => 'delivered',
                'id_panti' => $panti[1] ?? $panti[array_rand($panti)],
                'created_at' => Carbon::create(2026, 1, 8, 14, 0, 0),
            ],
            [
                'id_donatur' => $donatur[0],
                'nama_barang' => 'Vitamin & Suplemen',
                'kategori' => 'Kesehatan',
                'foto' => null,
                'status' => 'accepted',
                'id_panti' => $panti[0] ?? $panti[array_rand($panti)],
                'created_at' => Carbon::create(2026, 1, 10, 11, 0, 0),
            ],

            // Donatur 2 - Multiple donations
            [
                'id_donatur' => $donatur[1],
                'nama_barang' => 'Kasur & Bantal (10 set)',
                'kategori' => 'Perlengkapan',
                'foto' => null,
                'status' => 'delivered',
                'id_panti' => $panti[2] ?? $panti[array_rand($panti)],
                'created_at' => Carbon::create(2026, 1, 6, 9, 0, 0),
            ],
            [
                'id_donatur' => $donatur[1],
                'nama_barang' => 'Laptop Bekas (5 unit)',
                'kategori' => 'Teknologi',
                'foto' => null,
                'status' => 'pending',
                'id_panti' => $panti[0] ?? $panti[array_rand($panti)],
                'created_at' => Carbon::create(2026, 1, 12, 13, 0, 0),
            ],
            [
                'id_donatur' => $donatur[1],
                'nama_barang' => 'Peralatan Masak Lengkap',
                'kategori' => 'Dapur',
                'foto' => null,
                'status' => 'delivered',
                'id_panti' => $panti[1] ?? $panti[array_rand($panti)],
                'created_at' => Carbon::create(2026, 1, 7, 15, 0, 0),
            ],

            // Donatur 3 - One donation
            [
                'id_donatur' => $donatur[2],
                'nama_barang' => 'Mainan Edukatif (50 buah)',
                'kategori' => 'Mainan',
                'foto' => null,
                'status' => 'accepted',
                'id_panti' => $panti[2] ?? $panti[array_rand($panti)],
                'created_at' => Carbon::create(2026, 1, 9, 10, 0, 0),
            ],

            // Donatur with no donations - This user won't appear in this list
            // They can be identified by checking donatur_profiles that don't have entries in donasi_barang

            // Additional donations from other donors
            [
                'id_donatur' => $donatur[0],
                'nama_barang' => 'Sepatu Olahraga (30 pasang)',
                'kategori' => 'Pakaian',
                'foto' => null,
                'status' => 'pending',
                'id_panti' => $panti[2] ?? $panti[array_rand($panti)],
                'created_at' => Carbon::create(2026, 1, 14, 12, 0, 0),
            ],
            [
                'id_donatur' => $donatur[1],
                'nama_barang' => 'Alat Tulis & Buku (1000 item)',
                'kategori' => 'Pendidikan',
                'foto' => null,
                'status' => 'cancelled',
                'id_panti' => null, // Cancelled donation not sent to any panti
                'created_at' => Carbon::create(2026, 1, 11, 16, 0, 0),
            ],
        ];

        foreach ($donations as $donation) {
            DB::table('donasi_barang')->insert([
                'id_donatur' => $donation['id_donatur'],
                'nama_barang' => $donation['nama_barang'],
                'kategori' => $donation['kategori'],
                'foto' => $donation['foto'],
                'status' => $donation['status'],
                'id_panti' => $donation['id_panti'],
                'created_at' => $donation['created_at'],
                'updated_at' => $donation['created_at'],
            ]);
        }

        $this->command->info('Donasi Barang seeded successfully!');
        $this->command->line('Note: Donatur profiles without donations have not been seeded here.');
        $this->command->line('Users without donations can be identified separately.');
    }
}
