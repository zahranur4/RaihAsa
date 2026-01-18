<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FoodRescueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get donatur profiles for FK constraint
        $donaturs = DB::table('donatur_profiles')->pluck('id_donatur')->toArray();
        $users = DB::table('users')->where('is_admin', false)->pluck('id')->toArray();

        if (empty($donaturs) || empty($users)) {
            $this->command->warn('No donatur profiles or users found. Please run donatur seeder first.');
            return;
        }

        $foods = [
            // Available Foods - Expiring Next Month (February 2026)
            [
                'id_donatur' => $donaturs[0] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Nasi Kotak (Soto Ayam)',
                'porsi' => 50,
                'waktu_dibuat' => Carbon::now(),
                'waktu_expired' => Carbon::create(2026, 2, 5, 12, 0, 0), // Feb 5, 2026
                'status' => 'available',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[1] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Ayam Goreng + Nasi',
                'porsi' => 30,
                'waktu_dibuat' => Carbon::now(),
                'waktu_expired' => Carbon::create(2026, 2, 10, 18, 0, 0), // Feb 10, 2026
                'status' => 'available',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[2] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Roti Tawar + Selai',
                'porsi' => 100,
                'waktu_dibuat' => Carbon::now(),
                'waktu_expired' => Carbon::create(2026, 3, 1, 10, 0, 0), // March 1, 2026
                'status' => 'available',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[0] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Mie Goreng Spesial',
                'porsi' => 40,
                'waktu_dibuat' => Carbon::now(),
                'waktu_expired' => Carbon::create(2026, 2, 15, 14, 0, 0), // Feb 15, 2026
                'status' => 'available',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[1] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Soto Ayam + Perkedel',
                'porsi' => 35,
                'waktu_dibuat' => Carbon::now(),
                'waktu_expired' => Carbon::create(2026, 3, 5, 16, 0, 0), // March 5, 2026
                'status' => 'available',
                'id_claimer' => null,
            ],

            // Claimed Foods
            [
                'id_donatur' => $donaturs[2] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Bakso Daging (Istimewa)',
                'porsi' => 60,
                'waktu_dibuat' => Carbon::create(2026, 1, 14, 8, 0, 0),
                'waktu_expired' => Carbon::create(2026, 1, 14, 18, 0, 0),
                'status' => 'claimed',
                'id_claimer' => $users[0] ?? $users[array_rand($users)],
            ],
            [
                'id_donatur' => $donaturs[0] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Lumpia Goreng (50 pcs)',
                'porsi' => 50,
                'waktu_dibuat' => Carbon::create(2026, 1, 13, 10, 0, 0),
                'waktu_expired' => Carbon::create(2026, 1, 13, 20, 0, 0),
                'status' => 'claimed',
                'id_claimer' => $users[1] ?? $users[array_rand($users)],
            ],
            [
                'id_donatur' => $donaturs[1] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Kopi + Roti (Paket Sarapan)',
                'porsi' => 80,
                'waktu_dibuat' => Carbon::create(2026, 1, 15, 6, 0, 0),
                'waktu_expired' => Carbon::create(2026, 1, 15, 12, 0, 0),
                'status' => 'claimed',
                'id_claimer' => $users[2] ?? $users[array_rand($users)],
            ],

            // Expired Foods - Already Expired (This Month - January 2026)
            [
                'id_donatur' => $donaturs[2] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Gado-gado (Tidak Terambil)',
                'porsi' => 25,
                'waktu_dibuat' => Carbon::create(2026, 1, 10, 7, 0, 0),
                'waktu_expired' => Carbon::create(2026, 1, 10, 18, 0, 0), // Jan 10 expired
                'status' => 'expired',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[0] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Sate Ayam (30 tusuk)',
                'porsi' => 30,
                'waktu_dibuat' => Carbon::create(2026, 1, 8, 12, 0, 0),
                'waktu_expired' => Carbon::create(2026, 1, 8, 20, 0, 0), // Jan 8 expired
                'status' => 'expired',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[1] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Perkedel Goreng (Piring)',
                'porsi' => 45,
                'waktu_dibuat' => Carbon::create(2026, 1, 12, 8, 0, 0),
                'waktu_expired' => Carbon::create(2026, 1, 12, 16, 0, 0), // Jan 12 expired
                'status' => 'expired',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[2] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Nasi Goreng Seafood',
                'porsi' => 40,
                'waktu_dibuat' => Carbon::create(2026, 1, 5, 11, 0, 0),
                'waktu_expired' => Carbon::create(2026, 1, 5, 21, 0, 0), // Jan 5 expired
                'status' => 'expired',
                'id_claimer' => null,
            ],
        ];

        foreach ($foods as $food) {
            DB::table('food_rescue')->insert([
                'id_donatur' => $food['id_donatur'],
                'nama_makanan' => $food['nama_makanan'],
                'porsi' => $food['porsi'],
                'waktu_dibuat' => $food['waktu_dibuat'],
                'waktu_expired' => $food['waktu_expired'],
                'status' => $food['status'],
                'id_claimer' => $food['id_claimer'],
                'created_at' => $food['waktu_dibuat'],
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Food Rescue seeded successfully!');
    }
}
