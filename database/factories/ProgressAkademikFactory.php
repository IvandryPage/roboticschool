<?php

namespace Database\Factories;

use App\Models\ProgressAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgressAkademikFactory extends Factory
{
    protected $model = ProgressAkademik::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'siswa_id' => null,
            'program_id' => null,
            'kelas_id' => null,
            'status' => 'active',
            'nilai_rata_rata' => fake()->randomFloat(2, 60, 100),
            'catatan' => null,
        ];
    }
}
