<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\DonaturProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonaturProfileFactory extends Factory
{
    protected $model = DonaturProfile::class;

    public function definition()
    {
        return [
            'id_user' => User::factory(),
            'nama_lengkap' => $this->faker->name(),
            'no_telp' => $this->faker->phoneNumber(),
            'alamat_jemput' => $this->faker->address(),
        ];
    }
}
