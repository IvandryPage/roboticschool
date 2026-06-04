<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ForumKomentar;

class ForumKomentarFactory extends Factory
{
    protected $model = ForumKomentar::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'topik_id' => null,
            'user_id' => null,
            'komentar' => fake()->sentence(),
        ];
    }
}
