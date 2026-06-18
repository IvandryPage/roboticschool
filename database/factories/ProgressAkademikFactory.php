<?php

namespace Database\Factories;

use App\Models\ProgressAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgressAkademikFactory extends Factory
{
    protected $model = ProgressAkademik::class;

    public function definition(): array
    {
        return [
            // FIX ERROR 9: sesuaikan dengan kolom migration yang ada
            'siswa_id'                => null,
            'kelas_id'                => null,
            'persentase_kehadiran'    => fake()->randomFloat(2, 40, 100),
            'rata_nilai_tugas'        => fake()->randomFloat(2, 50, 100),
            'persentase_penyelesaian' => fake()->randomFloat(2, 60, 100),
            'status'                  => fake()->randomElement(['Lulus', 'Remedial', 'Aktif']),
        ];
    }
}
