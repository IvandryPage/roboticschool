<?php

namespace Database\Factories;

use App\Models\ArsipLaporan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArsipLaporanFactory extends Factory
{
    protected $model = ArsipLaporan::class;

    public function definition(): array
    {
        return [
            // FIX: remove \Str::uuid() — HasUuids trait handles this automatically
            'judul'        => fake()->sentence(4),
            'tipe_laporan' => fake()->randomElement([
                'laporan_kelulusan',
                'laporan_keuangan',
                'laporan_akademik',
                'laporan_instruktur',
                'laporan_bulanan',
                'laporan_tahunan',
            ]),
            'file_path'   => null,
            'dibuat_oleh' => null,
            'periode'     => now()->format('Y-m'),
            'catatan'     => fake()->optional()->paragraph(),
        ];
    }
}
