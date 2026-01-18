<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gunakan seeder khusus untuk membuat akun dummy user & admin
        $this->call(UserAdminSeeder::class);

        // Seed donatur profiles
        $this->call(DonaturSeeder::class);

        // Seed panti profiles
        $this->call(PantiSeeder::class);

        // Seed wishlist items
        $this->call(WishlistSeeder::class);

        // Seed food rescue items
        $this->call(FoodRescueSeeder::class);

        // Seed donasi barang (items donated to orphanages)
        $this->call(DonasiBarangSeeder::class);

        // Jika ingin menambah user tambahan: User::factory(10)->create();
    }
}
