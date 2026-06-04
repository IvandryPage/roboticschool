<?php

namespace Database\Factories;

use App\Models\AsetRobotik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AsetRobotik>
 */
class AsetRobotikFactory extends Factory
{
    protected $model = AsetRobotik::class;

    public function definition()
    {
        return [
            'kode_aset' => strtoupper(fake()->unique()->bothify('ASSET-#####')),
            'nama_kit' => fake()->word() . ' Kit',
            'deskripsi' => fake()->optional()->sentence(),
            'kategori' => fake()->randomElement(['Elektronik','Mekanik','Sensor','Lainnya']),
            'stok_minimal' => fake()->numberBetween(1,10),
        ];
    }
}
