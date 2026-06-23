<?php

use App\Models\ArsipLaporan;
use App\Models\EnrollmentKelas;
use App\Models\Kelas;
use App\Models\ProgressAkademik;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ============================================================
// PBI-141: Testing Dashboard Direktur
// ============================================================

beforeEach(function () {
    $roleAdmin    = Role::create(['nama_role' => 'Admin Akademik']);
    $roleDirektur = Role::create(['nama_role' => 'Direktur']);
    $this->admin    = User::factory()->create(['role_id' => $roleAdmin->id]);
    $this->direktur = User::factory()->create(['role_id' => $roleDirektur->id]);
});

test('[PBI-135] admin dapat mengakses halaman admin panel', function () {
    // Filament panel dapat diakses oleh user yang status_aktif = true
    // Response bukan 403 (forbidden) artinya access diterima (bisa 200 atau redirect)
    $response = $this->actingAs($this->admin)->get('/admin');
    expect($response->status())->not->toBe(403);
});

test('[PBI-135] direktur dapat mengakses halaman admin panel', function () {
    $response = $this->actingAs($this->direktur)->get('/admin');
    expect($response->status())->not->toBe(403);
});

test('[PBI-135] tamu tidak dapat mengakses admin panel', function () {
    $response = $this->get('/admin');
    // Tamu harus di-redirect ke halaman login (bukan 200)
    expect($response->status())->not->toBe(200);
});

test('[PBI-136] query rekap kelulusan menghitung total siswa per batch dengan benar', function () {
    $kelas = Kelas::factory()->create();

    $siswa1 = Siswa::factory()->create();
    $siswa2 = Siswa::factory()->create();
    $siswa3 = Siswa::factory()->create();

    EnrollmentKelas::create(['kelas_id' => $kelas->id, 'siswa_id' => $siswa1->id, 'status' => 'Selesai', 'tanggal_bergabung' => now()]);
    EnrollmentKelas::create(['kelas_id' => $kelas->id, 'siswa_id' => $siswa2->id, 'status' => 'Aktif',   'tanggal_bergabung' => now()]);
    EnrollmentKelas::create(['kelas_id' => $kelas->id, 'siswa_id' => $siswa3->id, 'status' => 'Drop',    'tanggal_bergabung' => now()]);

    $selesai = EnrollmentKelas::where('kelas_id', $kelas->id)->where('status', 'Selesai')->count();
    $aktif   = EnrollmentKelas::where('kelas_id', $kelas->id)->where('status', 'Aktif')->count();
    $drop    = EnrollmentKelas::where('kelas_id', $kelas->id)->where('status', 'Drop')->count();

    expect($selesai)->toBe(1);
    expect($aktif)->toBe(1);
    expect($drop)->toBe(1);
});

test('[PBI-137] syarat kelulusan: siswa dengan kehadiran >= 75 dan nilai >= 70 dianggap lulus', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create();

    EnrollmentKelas::create([
        'kelas_id'         => $kelas->id,
        'siswa_id'         => $siswa->id,
        'status'           => 'Selesai',
        'tanggal_bergabung'=> now(),
    ]);

    ProgressAkademik::create([
        'siswa_id'             => $siswa->id,
        'kelas_id'             => $kelas->id,
        'persentase_kehadiran' => 80.0,
        'rata_nilai_tugas'     => 75.0,
        'status'               => 'Lulus',
    ]);

    $layak = EnrollmentKelas::where('status', 'Selesai')
        ->whereHas('siswa.progressAkademik', fn ($q) =>
            $q->whereColumn('progress_akademik.kelas_id', 'enrollment_kelas.kelas_id')
              ->where('persentase_kehadiran', '>=', 75)
              ->where('rata_nilai_tugas', '>=', 70)
        )->count();

    expect($layak)->toBe(1);
});
