<?php

namespace Database\Factories;

use App\Models\ProgramKursus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProgramKursus>
 */
class ProgramKursusFactory extends Factory
{
    protected $model = ProgramKursus::class;

    public function definition()
    {
        return [
            'nama_program' => fake()->unique()->sentence(3),
            'deskripsi' => fake()->optional()->paragraph(),
            'level' => fake()->randomElement(['Pemula', 'Menengah', 'Lanjutan']),
            'biaya' => fake()->randomFloat(2, 0, 5000000),
            'durasi_minggu' => fake()->numberBetween(1, 52),
            'gambar' => null,
            'status_tampil' => true,
        ];
    }
}
