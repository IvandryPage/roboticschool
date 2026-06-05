<?php

namespace Database\Seeders;

use App\Models\AsetRobotik;
use App\Models\Batch;
use App\Models\ItemKitRobotik;
use App\Models\ProgramKursus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles
        $roles = [
            ['nama_role' => 'Admin Akademik'],
            ['nama_role' => 'Instruktur'],
            ['nama_role' => 'Siswa'],
            ['nama_role' => 'Tim Publikasi'],
            ['nama_role' => 'Direktur'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['nama_role' => $r['nama_role']], $r);
        }

        // Create an admin user
        $adminRole = Role::where('nama_role', 'Admin Akademik')->first();

        User::factory()->create([
            'nama_lengkap' => 'Admin User',
            'email' => 'admin@example.com',
            'role_id' => $adminRole?->id,
        ]);

        // Create some demo data
        $program = ProgramKursus::factory()->create();
        $batch = Batch::factory()->create(['program_id' => $program->id]);

        // Seed some assets
        $aset = AsetRobotik::factory()->create();
        ItemKitRobotik::factory()->count(3)->create(['aset_id' => $aset->id]);
    }
}
