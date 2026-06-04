<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ArsipLaporan;

class ArsipLaporanFactory extends Factory
{
    protected $model = ArsipLaporan::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'judul' => fake()->sentence(),
            'tipe_laporan' => fake()->randomElement(['laporan_mingguan','laporan_bulanan']),
            'file_path' => null,
            'dibuat_oleh' => null,
            'periode' => date('Y-m'),
            'catatan' => null,
        ];
    }
}
