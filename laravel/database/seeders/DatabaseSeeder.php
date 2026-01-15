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

        // Seed panti profiles
        $this->call(PantiSeeder::class);

        // Seed wishlist items
        $this->call(WishlistSeeder::class);

        // Jika ingin menambah user tambahan: User::factory(10)->create();
    }
}
