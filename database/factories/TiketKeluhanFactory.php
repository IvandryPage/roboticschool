<?php

namespace Database\Factories;

use App\Models\TiketKeluhan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TiketKeluhanFactory extends Factory
{
    protected $model = TiketKeluhan::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'pelapor_id' => null,
            'ditangani_oleh' => null,
            'kategori' => fake()->randomElement(['Akademik', 'Teknis', 'Administratif']),
            'prioritas' => fake()->randomElement(['Rendah', 'Sedang', 'Tinggi', 'Kritis']),
            'subjek' => fake()->sentence(3),
            'deskripsi' => fake()->paragraph(),
            'status' => 'Open',
            'resolved_at' => null,
        ];
    }
}
