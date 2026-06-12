<?php

namespace Database\Factories;

use App\Models\Kehadiran;
use Illuminate\Database\Eloquent\Factories\Factory;

class KehadiranFactory extends Factory
{
    protected $model = Kehadiran::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'sesi_id' => null,
            'siswa_id' => null,
            'status_hadir' => fake()->randomElement(['hadir', 'izin', 'alpha']),
            'catatan' => null,
            'dicatat_oleh' => null,
            'waktu_pencatatan' => now(),
        ];
    }
}
