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
            // Available Foods (Fresh)
            [
                'id_donatur' => $donaturs[0] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Nasi Kotak (Soto Ayam)',
                'porsi' => 50,
                'waktu_dibuat' => Carbon::now()->subHours(2),
                'waktu_expired' => Carbon::now()->addHours(6),
                'status' => 'available',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[1] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Ayam Goreng + Nasi',
                'porsi' => 30,
                'waktu_dibuat' => Carbon::now()->subHours(1),
                'waktu_expired' => Carbon::now()->addHours(8),
                'status' => 'available',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[2] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Roti Tawar + Selai',
                'porsi' => 100,
                'waktu_dibuat' => Carbon::now()->subHours(3),
                'waktu_expired' => Carbon::now()->addHours(4),
                'status' => 'available',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[0] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Mie Goreng Spesial',
                'porsi' => 40,
                'waktu_dibuat' => Carbon::now()->subHours(1),
                'waktu_expired' => Carbon::now()->addHours(5),
                'status' => 'available',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[1] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Soto Ayam + Perkedel',
                'porsi' => 35,
                'waktu_dibuat' => Carbon::now()->subHours(4),
                'waktu_expired' => Carbon::now()->addHours(3),
                'status' => 'available',
                'id_claimer' => null,
            ],

            // Claimed Foods
            [
                'id_donatur' => $donaturs[2] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Bakso Daging (Istimewa)',
                'porsi' => 60,
                'waktu_dibuat' => Carbon::now()->subHours(5),
                'waktu_expired' => Carbon::now()->subHours(1),
                'status' => 'claimed',
                'id_claimer' => $users[0] ?? $users[array_rand($users)],
            ],
            [
                'id_donatur' => $donaturs[0] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Lumpia Goreng (50 pcs)',
                'porsi' => 50,
                'waktu_dibuat' => Carbon::now()->subHours(6),
                'waktu_expired' => Carbon::now()->subHours(2),
                'status' => 'claimed',
                'id_claimer' => $users[1] ?? $users[array_rand($users)],
            ],
            [
                'id_donatur' => $donaturs[1] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Kopi + Roti (Paket Sarapan)',
                'porsi' => 80,
                'waktu_dibuat' => Carbon::now()->subHours(3),
                'waktu_expired' => Carbon::now(),
                'status' => 'claimed',
                'id_claimer' => $users[2] ?? $users[array_rand($users)],
            ],

            // Expired Foods
            [
                'id_donatur' => $donaturs[2] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Gado-gado (Tidak Terambil)',
                'porsi' => 25,
                'waktu_dibuat' => Carbon::now()->subDays(1)->subHours(2),
                'waktu_expired' => Carbon::now()->subHours(8),
                'status' => 'expired',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[0] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Sate Ayam (30 tusuk)',
                'porsi' => 30,
                'waktu_dibuat' => Carbon::now()->subDays(1),
                'waktu_expired' => Carbon::now()->subHours(5),
                'status' => 'expired',
                'id_claimer' => null,
            ],
            [
                'id_donatur' => $donaturs[1] ?? $donaturs[array_rand($donaturs)],
                'nama_makanan' => 'Perkedel Goreng (Piring)',
                'porsi' => 45,
                'waktu_dibuat' => Carbon::now()->subDays(1)->subHours(5),
                'waktu_expired' => Carbon::now()->subHours(2),
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
