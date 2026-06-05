<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition()
    {
        return [
            'user_id' => null,
            'pendaftaran_id' => null,
            'tanggal_lahir' => fake()->optional()->date(),
            'jenis_kelamin' => fake()->optional()->randomElement(['L', 'P']),
            'alamat' => fake()->optional()->address(),
        ];
    }
}
