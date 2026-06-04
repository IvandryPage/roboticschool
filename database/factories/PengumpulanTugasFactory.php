<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PengumpulanTugas;

class PengumpulanTugasFactory extends Factory
{
    protected $model = PengumpulanTugas::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'tugas_id' => null,
            'siswa_id' => null,
            'file_kumpul' => null,
            'komentar' => fake()->sentence(),
            'nilai' => null,
            'tanggal_kumpul' => now(),
        ];
    }
}
