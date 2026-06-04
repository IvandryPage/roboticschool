<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sertifikat;

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
