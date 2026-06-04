<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'nama_role' => fake()->unique()->jobTitle(),
            'deskripsi' => fake()->optional()->sentence(),
        ];
    }
}
