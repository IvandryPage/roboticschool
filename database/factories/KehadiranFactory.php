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
            'sesi_id' => SesiLive::factory(),
            'siswa_id' => Siswa::factory(),
            'status_hadir' => fake()->randomElement(['hadir', 'izin', 'alpha']),
            'catatan' => null,
            'dicatat_oleh' => User::factory(),
            'waktu_pencatatan' => now(),
        ];
    }
}
