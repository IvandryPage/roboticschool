<?php

namespace Database\Factories;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition(): array
    {
        return [
            // Auto-create a user if not provided — user_id is needed for relation tests
            'user_id'       => User::factory(),
            'pendaftaran_id'=> null,
            'tanggal_lahir' => fake()->optional()->date(),
            'jenis_kelamin' => fake()->optional()->randomElement(['L', 'P']),
            'alamat'        => fake()->optional()->address(),
        ];
    }
}
