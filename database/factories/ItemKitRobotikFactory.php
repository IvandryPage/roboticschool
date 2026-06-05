<?php

namespace Database\Factories;

use App\Models\ItemKitRobotik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemKitRobotik>
 */
class ItemKitRobotikFactory extends Factory
{
    protected $model = ItemKitRobotik::class;

    public function definition()
    {
        return [
            'aset_id' => null,
            'serial_number' => strtoupper(fake()->unique()->bothify('SN-########')),
            'status_kondisi' => fake()->randomElement(['Bagus', 'Rusak', 'Perbaikan']),
            'lokasi_rak' => fake()->optional()->bothify('RACK-#'),
        ];
    }
}
