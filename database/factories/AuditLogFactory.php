<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AuditLog;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'user_id' => null,
            'aksi' => fake()->randomElement(['Login','Create','Update','Delete','Verify']),
            'model' => null,
            'model_id' => null,
            'perubahan' => null,
            'ip_address' => null,
            'user_agent' => null,
            'created_at' => now(),
        ];
    }
}
