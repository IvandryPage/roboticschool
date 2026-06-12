<?php

namespace Database\Factories;

use App\Models\Sertifikat;
use Illuminate\Database\Eloquent\Factories\Factory;

class SertifikatFactory extends Factory
{
    protected $model = Sertifikat::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'siswa_id' => null,
            'kelas_id' => null,
            'nomor_sertifikat' => strtoupper(fake()->bothify('CERT-####')),
            'file_path' => null,
            'qr_code' => null,
            'verified_url' => null,
            'tanggal_terbit' => now(),
        ];
    }
}
