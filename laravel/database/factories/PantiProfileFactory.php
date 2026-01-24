<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\PantiProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class PantiProfileFactory extends Factory
{
    protected $model = PantiProfile::class;

    public function definition()
    {
        return [
            'id_user' => User::factory(),
            'nama_panti' => $this->faker->company() . ' Asuhan',
            'alamat_lengkap' => $this->faker->address(),
            'kota' => $this->faker->city(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'no_sk' => $this->faker->numerify('SK-########'),
            'status_verif' => 'verified',
        ];
    }
}
