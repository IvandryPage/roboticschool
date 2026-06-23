<?php

use App\Models\Kelas;
use App\Models\ProgramKursus;
use App\Models\Batch;
use App\Models\Role;
use App\Models\User;
use App\Models\Siswa;
use App\Models\EnrollmentKelas;
use App\Models\SesiLive;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('can create a class', function () {
    $role = Role::create(['nama_role' => 'Instruktur']);
    $instruktur = User::factory()->create(['role_id' => $role->id, 'nama_lengkap' => 'Instruktur A', 'status_aktif' => true]);
    $program = ProgramKursus::create(['nama_program' => 'Robotik Dasar', 'biaya' => 100000, 'durasi_minggu' => 12]);
    $batch = Batch::create(['program_id' => $program->id, 'nama_batch' => 'Batch 1', 'tanggal_mulai' => now(), 'tanggal_selesai' => now()->addMonths(3)]);

    $kelas = Kelas::create([
        'batch_id' => $batch->id,
        'nama_kelas' => 'Kelas A',
        'instruktur_id' => $instruktur->id,
        'kapasitas' => 30,
        'status' => 'Aktif',
    ]);

    assertDatabaseHas('kelas', [
        'nama_kelas' => 'Kelas A',
        'batch_id' => $batch->id,
        'kapasitas' => 30,
    ]);
});

it('can enroll a student to a class', function () {
    $roleSiswa = Role::create(['nama_role' => 'Siswa']);
    $userSiswa = User::factory()->create(['role_id' => $roleSiswa->id, 'nama_lengkap' => 'Siswa A', 'status_aktif' => true]);
    $siswa = Siswa::create([
        'user_id' => $userSiswa->id,
    ]);

    $roleInstruktur = Role::create(['nama_role' => 'Instruktur']);
    $instruktur = User::factory()->create(['role_id' => $roleInstruktur->id, 'nama_lengkap' => 'Instruktur A', 'status_aktif' => true]);
    $program = ProgramKursus::create(['nama_program' => 'Robotik Dasar', 'biaya' => 100000, 'durasi_minggu' => 12]);
    $batch = Batch::create(['program_id' => $program->id, 'nama_batch' => 'Batch 1', 'tanggal_mulai' => now(), 'tanggal_selesai' => now()->addMonths(3)]);

    $kelas = Kelas::create([
        'batch_id' => $batch->id,
        'nama_kelas' => 'Kelas A',
        'instruktur_id' => $instruktur->id,
        'kapasitas' => 30,
        'status' => 'Aktif',
    ]);

    $enrollment = EnrollmentKelas::create([
        'kelas_id' => $kelas->id,
        'siswa_id' => $siswa->id,
        'tanggal_bergabung' => now(),
        'status' => 'Aktif',
    ]);

    assertDatabaseHas('enrollment_kelas', [
        'kelas_id' => $kelas->id,
        'siswa_id' => $siswa->id,
        'status' => 'Aktif',
    ]);
});

it('can create a live session for a class', function () {
    $roleInstruktur = Role::create(['nama_role' => 'Instruktur']);
    $instruktur = User::factory()->create(['role_id' => $roleInstruktur->id, 'nama_lengkap' => 'Instruktur A', 'status_aktif' => true]);
    $program = ProgramKursus::create(['nama_program' => 'Robotik Dasar', 'biaya' => 100000, 'durasi_minggu' => 12]);
    $batch = Batch::create(['program_id' => $program->id, 'nama_batch' => 'Batch 1', 'tanggal_mulai' => now(), 'tanggal_selesai' => now()->addMonths(3)]);

    $kelas = Kelas::create([
        'batch_id' => $batch->id,
        'nama_kelas' => 'Kelas A',
        'instruktur_id' => $instruktur->id,
        'kapasitas' => 30,
        'status' => 'Aktif',
    ]);

    $sesi = SesiLive::create([
        'kelas_id' => $kelas->id,
        'nomor_sesi' => 1,
        'judul_sesi' => 'Pengenalan Robotik',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '10:00:00',
        'jam_selesai' => '12:00:00',
        'platform' => 'Zoom',
        'link_akses' => 'https://zoom.us/j/123456789',
    ]);

    assertDatabaseHas('sesi_live', [
        'judul_sesi' => 'Pengenalan Robotik',
        'nomor_sesi' => 1,
        'platform' => 'Zoom',
    ]);
});
