<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    public function definition()
    {
        return [
            'batch_id' => null,
            'nama_kelas' => fake()->words(3, true),
            'instruktur_id' => null,
            'kapasitas' => fake()->numberBetween(5, 30),
            'status' => 'Aktif',
        ];
    }
}
