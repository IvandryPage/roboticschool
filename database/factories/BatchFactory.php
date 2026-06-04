<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\ProgramKursus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Batch>
 */
class BatchFactory extends Factory
{
    protected $model = Batch::class;

    public function definition()
    {
        return [
            'program_id' => ProgramKursus::factory(),
            'nama_batch' => 'Batch ' . fake()->numberBetween(1, 10) . ' - ' . fake()->year(),
            'tanggal_mulai' => fake()->date(),
            'tanggal_selesai' => fake()->date(),
            'kuota_max' => fake()->numberBetween(15, 30),
            'status_aktif' => true,
        ];
    }
}
