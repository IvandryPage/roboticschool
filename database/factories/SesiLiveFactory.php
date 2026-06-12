<?php

namespace Database\Factories;

use App\Models\SesiLive;
use Illuminate\Database\Eloquent\Factories\Factory;

class SesiLiveFactory extends Factory
{
    protected $model = SesiLive::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'kelas_id' => null,
            'nomor_sesi' => fake()->numberBetween(1, 20),
            'judul_sesi' => fake()->sentence(4),
            'tanggal' => now()->addDays(fake()->numberBetween(1, 30)),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'platform' => 'Zoom',
            'link_akses' => null,
            'keterangan' => null,
        ];
    }
}
