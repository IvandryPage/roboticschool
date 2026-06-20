<?php

namespace Database\Seeders;

use App\Models\AsetRobotik;
use App\Models\Batch;
use App\Models\ItemKitRobotik;
use App\Models\ProgramKursus;
use App\Models\Role;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['nama_role' => $r['nama_role']], $r);
        }

        $adminRole = Role::where('nama_role', 'Admin Akademik')->first();
        $instrukturRole = Role::where('nama_role', 'Instruktur')->first();
        $siswaRole = Role::where('nama_role', 'Siswa')->first();
        $publikasiRole = Role::where('nama_role', 'Tim Publikasi')->first();
        $direkturRole = Role::where('nama_role', 'Direktur')->first();

        // 1. Create accounts for each role
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@robonesia.test'],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => 'Admin Robonesia',
                'name' => 'admin',
                'password' => bcrypt('password'),
                'role_id' => $adminRole?->id,
                'status_aktif' => true,
            ]
        );

        // Instruktur
        User::firstOrCreate(
            ['email' => 'instruktur@robonesia.test'],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => 'Dina Instruktur',
                'name' => 'dina',
                'password' => bcrypt('password'),
                'role_id' => $instrukturRole?->id,
                'status_aktif' => true,
            ]
        );

        // Siswa User
        $siswaUser = User::firstOrCreate(
            ['email' => 'siswa@robonesia.test'],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => 'Sinta Siswa',
                'name' => 'sinta',
                'password' => bcrypt('password'),
                'role_id' => $siswaRole?->id,
                'status_aktif' => true,
            ]
        );

        // Also create a Siswa profile linked to the siswa user if it doesn't exist
        Siswa::firstOrCreate(
            ['user_id' => $siswaUser->id],
            [
                'id' => (string) Str::uuid(),
                'tanggal_lahir' => '2010-05-15',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Robotik No. 45, Bandung',
            ]
        );

        // Tim Publikasi
        User::firstOrCreate(
            ['email' => 'publikasi@robonesia.test'],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => 'Andi Publikasi',
                'name' => 'andi',
                'password' => bcrypt('password'),
                'role_id' => $publikasiRole?->id,
                'status_aktif' => true,
            ]
        );

        // Direktur
        User::firstOrCreate(
            ['email' => 'direktur@robonesia.test'],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => 'Budi Direktur',
                'name' => 'budi',
                'password' => bcrypt('password'),
                'role_id' => $direkturRole?->id,
                'status_aktif' => true,
            ]
        );

        // 2. Create some assets & items
        // Aset 1: Arduino Starter Kit
        $aset1 = AsetRobotik::firstOrCreate(
            ['kode_aset' => 'KIT-ARDUINO'],
            [
                'id' => (string) Str::uuid(),
                'nama_kit' => 'Arduino Starter Kit',
                'deskripsi' => 'Kit lengkap pembelajaran mikrokontroler Arduino Uno beserta sensor dasar.',
                'kategori' => 'Elektronik',
                'stok_minimal' => 2,
            ]
        );

        // Item Kits for Arduino
        ItemKitRobotik::firstOrCreate(
            ['serial_number' => 'SN-ARD-001'],
            [
                'id' => (string) Str::uuid(),
                'aset_id' => $aset1->id,
                'status_kondisi' => 'Bagus',
                'lokasi_rak' => 'RAK-A1',
            ]
        );
        ItemKitRobotik::firstOrCreate(
            ['serial_number' => 'SN-ARD-002'],
            [
                'id' => (string) Str::uuid(),
                'aset_id' => $aset1->id,
                'status_kondisi' => 'Bagus',
                'lokasi_rak' => 'RAK-A1',
            ]
        );
        ItemKitRobotik::firstOrCreate(
            ['serial_number' => 'SN-ARD-003'],
            [
                'id' => (string) Str::uuid(),
                'aset_id' => $aset1->id,
                'status_kondisi' => 'Rusak',
                'lokasi_rak' => 'RAK-A2',
            ]
        );

        // Aset 2: LEGO Mindstorms EV3
        $aset2 = AsetRobotik::firstOrCreate(
            ['kode_aset' => 'KIT-LEGO-EV3'],
            [
                'id' => (string) Str::uuid(),
                'nama_kit' => 'LEGO Mindstorms EV3',
                'deskripsi' => 'Kit robotika edukasi LEGO Mindstorms untuk melatih logika dan mekanika.',
                'kategori' => 'Mekanik',
                'stok_minimal' => 1,
            ]
        );

        // Item Kits for LEGO
        ItemKitRobotik::firstOrCreate(
            ['serial_number' => 'SN-LEG-001'],
            [
                'id' => (string) Str::uuid(),
                'aset_id' => $aset2->id,
                'status_kondisi' => 'Bagus',
                'lokasi_rak' => 'RAK-B1',
            ]
        );

        // Aset 3: Raspberry Pi 4 Model B
        $aset3 = AsetRobotik::firstOrCreate(
            ['kode_aset' => 'KIT-RASPBERRY'],
            [
                'id' => (string) Str::uuid(),
                'nama_kit' => 'Raspberry Pi 4 Model B',
                'deskripsi' => 'Single board computer Raspberry Pi 4 dengan RAM 4GB untuk proyek IoT.',
                'kategori' => 'Elektronik',
                'stok_minimal' => 2,
            ]
        );

        // Item Kits for Raspberry
        ItemKitRobotik::firstOrCreate(
            ['serial_number' => 'SN-RPI-001'],
            [
                'id' => (string) Str::uuid(),
                'aset_id' => $aset3->id,
                'status_kondisi' => 'Bagus',
                'lokasi_rak' => 'RAK-C1',
            ]
        );
        ItemKitRobotik::firstOrCreate(
            ['serial_number' => 'SN-RPI-002'],
            [
                'id' => (string) Str::uuid(),
                'aset_id' => $aset3->id,
                'status_kondisi' => 'Bagus',
                'lokasi_rak' => 'RAK-C1',
            ]
        );

        // 3. Create demo programs & batches to prevent empty pages if they are opened
        $program = ProgramKursus::firstOrCreate(
            ['nama_program' => 'Robotics for Kids'],
            [
                'id' => (string) Str::uuid(),
                'deskripsi' => 'Program kursus robotik dasar untuk anak-anak sekolah dasar.',
                'level' => 'Pemula',
                'biaya' => 1500000.00,
                'durasi_minggu' => 12,
                'status_tampil' => true,
            ]
        );

        Batch::firstOrCreate(
            ['nama_batch' => 'Batch 1 - 2026'],
            [
                'id' => (string) Str::uuid(),
                'program_id' => $program->id,
                'tanggal_mulai' => '2026-07-01',
                'tanggal_selesai' => '2026-09-30',
                'kuota_max' => 20,
                'status_aktif' => true,
            ]
        );
    }
}
