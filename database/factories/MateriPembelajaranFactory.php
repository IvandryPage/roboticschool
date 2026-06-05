<?php

namespace Database\Factories;

use App\Models\MateriPembelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class MateriPembelajaranFactory extends Factory
{
    protected $model = MateriPembelajaran::class;

    public function definition()
    {
        return [
            'id' => (string) \Str::uuid(),
            'sesi_id' => null,
            'judul' => fake()->sentence(),
            'tipe_konten' => 'file',
            'file_path_atau_url' => null,
            'urutan' => 1,
            'keterangan' => null,
        ];
    }
}
