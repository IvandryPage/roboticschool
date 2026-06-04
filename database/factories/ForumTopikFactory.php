<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ForumTopik;

class ForumTopikFactory extends Factory
{
    protected $model = ForumTopik::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'kelas_id' => null,
            'pembuat_id' => null,
            'judul' => fake()->sentence(),
            'konten' => fake()->paragraph(),
        ];
    }
}
