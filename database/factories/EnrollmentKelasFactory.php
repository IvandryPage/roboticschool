<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EnrollmentKelas;

class EnrollmentKelasFactory extends Factory
{
    protected $model = EnrollmentKelas::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'kelas_id' => null,
            'siswa_id' => null,
            'tanggal_bergabung' => now(),
            'status' => 'active',
        ];
    }
}
