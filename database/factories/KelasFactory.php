<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kelas>
 */
class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    public function definition(): array
    {
        return [
            // FIX: batch_id dan instruktur_id NOT NULL di database — auto-create jika tidak disuplai
            'batch_id'      => Batch::factory(),
            'nama_kelas'    => 'Kelas ' . fake()->randomElement(['A', 'B', 'C']) . ' - ' . fake()->randomElement(['Pagi', 'Sore', 'Malam']),
            'instruktur_id' => User::factory(),
            'kapasitas'     => fake()->numberBetween(5, 30),
            'status'        => fake()->randomElement(['Aktif', 'Selesai']),
        ];
    }
}
