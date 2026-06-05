<?php

namespace Database\Factories;

use App\Models\EvaluasiInstruktur;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluasiInstrukturFactory extends Factory
{
    protected $model = EvaluasiInstruktur::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'kelas_id' => null,
            'siswa_id' => null,
            'instruktur_id' => null,
            'skor_rata_rata' => fake()->randomFloat(2, 1, 5),
            'jawaban_kuesioner' => null,
            'saran_ulasan' => fake()->sentence(),
        ];
    }
}
