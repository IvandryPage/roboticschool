<?php

namespace Database\Factories;

use App\Models\Sertifikat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SertifikatFactory extends Factory
{
    protected $model = Sertifikat::class;

    public function definition(): array
    {
        $tahun = now()->format('Y');

        return [
            // FIX ERROR 10: gunakan Str::uuid() dengan import yang benar
            'siswa_id'         => null,
            'kelas_id'         => null,
            'nomor_sertifikat' => 'RBN-' . $tahun . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'file_path'        => null,
            'qr_code'          => null,
            'verified_url'     => null,
            'diterbitkan_oleh' => null,
            'tanggal_terbit'   => now(),
        ];
    }
}
