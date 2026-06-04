<?php

namespace Database\Factories;

use App\Models\CalonPeserta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CalonPeserta>
 */
class CalonPesertaFactory extends Factory
{
    protected $model = CalonPeserta::class;

    public function definition()
    {
        return [
            'nama_lengkap' => fake()->name(),
            'email' => fake()->safeEmail(),
            'no_hp' => fake()->optional()->phoneNumber(),
            'asal_sekolah_atau_instansi' => fake()->optional()->company(),
            'jenjang_pendidikan' => fake()->randomElement(['SD','SMP','SMA','SMK','D3','S1','Lainnya']),
        ];
    }
}
