<?php

namespace Database\Factories;

use App\Models\PeminjamanItemAset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PeminjamanItemAsetFactory extends Factory
{
    protected $model = PeminjamanItemAset::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'item_kit_id' => null,
            'user_id' => null,
            'tanggal_pinjam' => null,
            'tanggal_jatuh_tempo' => now()->addDays(7),
            'tanggal_kembali' => null,
            'status' => 'Diajukan',
            'kondisi_awal' => 'Baik',
            'kondisi_akhir' => null,
            'diverifikasi_oleh' => null,
        ];
    }
}
