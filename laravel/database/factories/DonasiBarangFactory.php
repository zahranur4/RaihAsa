<?php

namespace Database\Factories;

use App\Models\DonaturProfile;
use App\Models\PantiProfile;
use App\Models\DonasiBarang;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonasiBarangFactory extends Factory
{
    protected $model = DonasiBarang::class;

    public function definition()
    {
        return [
            'id_donatur' => DonaturProfile::factory(),
            'nama_barang' => $this->faker->word(),
            'kategori' => $this->faker->randomElement(['Makanan', 'Pakaian', 'Alat Tulis', 'Peralatan', 'Lainnya']),
            'foto' => null,
            'status' => 'pending',
            'id_panti' => PantiProfile::factory(),
        ];
    }
}
