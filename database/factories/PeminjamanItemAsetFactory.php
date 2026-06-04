<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PeminjamanItemAset;

class PeminjamanItemAsetFactory extends Factory
{
    protected $model = PeminjamanItemAset::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'item_kit_id' => null,
            'peminjam_id' => null,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(7),
            'status' => 'borrowed',
            'catatan' => null,
        ];
    }
}
