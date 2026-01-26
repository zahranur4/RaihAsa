<?php

namespace Database\Factories;

use App\Models\DonaturProfile;
use App\Models\FoodRescue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodRescueFactory extends Factory
{
    protected $model = FoodRescue::class;

    public function definition()
    {
        return [
            'id_donatur' => DonaturProfile::factory(),
            'nama_makanan' => $this->faker->word() . ' ' . $this->faker->word(),
            'porsi' => $this->faker->numberBetween(1, 50),
            'waktu_dibuat' => Carbon::now(),
            'waktu_expired' => Carbon::now()->addHours(24),
            'status' => 'available',
            'id_claimer' => null,
        ];
    }
}
