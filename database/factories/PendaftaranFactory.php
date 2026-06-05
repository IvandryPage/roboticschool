<?php

namespace Database\Factories;

use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pendaftaran>
 */
class PendaftaranFactory extends Factory
{
    protected $model = Pendaftaran::class;

    public function definition()
    {
        return [
            'calon_peserta_id' => null,
            'program_id' => null,
            'no_referensi' => 'REF-'.strtoupper(fake()->unique()->bothify('??????')),
            'tanggal_daftar' => now(),
            'status' => 'Menunggu Verifikasi',
            'catatan_admin' => null,
        ];
    }
}
