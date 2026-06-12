<?php

namespace Database\Factories;

use App\Models\Tugas;
use Illuminate\Database\Eloquent\Factories\Factory;

class TugasFactory extends Factory
{
    protected $model = Tugas::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'sesi_id' => null,
            'judul_tugas' => fake()->sentence(),
            'deskripsi' => fake()->paragraph(),
            'file_soal' => null,
            'batas_waktu' => now()->addDays(fake()->numberBetween(3, 14)),
            'nilai_maksimum' => fake()->numberBetween(10, 100),
        ];
    }
}
