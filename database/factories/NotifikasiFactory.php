<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notifikasi;

class NotifikasiFactory extends Factory
{
    protected $model = Notifikasi::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'user_id' => null,
            'judul' => fake()->sentence(3),
            'pesan' => fake()->sentence(),
            'jenis' => 'info',
            'read_at' => null,
            'created_at' => now(),
        ];
    }
}
