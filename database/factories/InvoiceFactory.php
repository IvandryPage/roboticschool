<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'pendaftaran_id' => null,
            'no_invoice' => 'INV-'.strtoupper(fake()->unique()->bothify('########')),
            'total_tagihan' => fake()->randomFloat(2, 0, 5000000),
            'tanggal_terbit' => now(),
            'tanggal_jatuh_tempo' => now()->addDays(7),
            'status_pembayaran' => 'Menunggu',
            'payment_gateway' => null,
            'payment_reference' => null,
            'gateway_payload' => null,
        ];
    }
}
