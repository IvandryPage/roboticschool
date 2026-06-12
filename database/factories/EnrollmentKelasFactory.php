<?php

namespace Database\Factories;

use App\Models\EnrollmentKelas;
use Illuminate\Database\Eloquent\Factories\Factory;

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
