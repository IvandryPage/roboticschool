<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MaintenanceAset;

class MaintenanceAsetFactory extends Factory
{
    protected $model = MaintenanceAset::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'item_kit_id' => null,
            'dilaporkan_oleh' => null,
            'ditangani_oleh' => null,
            'tanggal_lapor' => now(),
            'jenis_pemeliharaan' => fake()->randomElement(['inspeksi','perbaikan','kalibrasi']),
            'hasil_pemeriksaan' => null,
            'catatan' => null,
        ];
    }
}
