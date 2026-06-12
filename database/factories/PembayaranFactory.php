<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pembayaran>
 */
class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    public function definition()
    {
        return [
            'invoice_id' => null,
            'nominal' => fake()->randomFloat(2, 0, 5000000),
            'metode_pembayaran' => 'Transfer',
            'provider' => null,
            'provider_reference' => null,
            'status' => 'Pending',
            'paid_at' => null,
            'callback_payload' => null,
        ];
    }
}
