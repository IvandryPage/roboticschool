<?php

namespace Database\Factories;

use App\Models\MaintenanceAset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MaintenanceAsetFactory extends Factory
{
    protected $model = MaintenanceAset::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'item_kit_id' => null,
            'dilaporkan_oleh' => null,
            'ditangani_oleh' => null,
            'tanggal_lapor' => now(),
            'deskripsi_kerusakan' => fake()->sentence(),
            'status' => 'Diajukan',
            'biaya' => null,
            'selesai_pada' => null,
        ];
    }
}
