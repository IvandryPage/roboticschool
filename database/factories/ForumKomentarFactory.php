<?php

namespace Database\Factories;

use App\Models\ForumKomentar;
use Illuminate\Database\Eloquent\Factories\Factory;

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
