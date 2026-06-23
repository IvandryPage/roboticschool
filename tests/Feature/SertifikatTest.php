<?php

use App\Models\EnrollmentKelas;
use App\Models\Kelas;
use App\Models\ProgressAkademik;
use App\Models\Role;
use App\Models\Sertifikat;
use App\Models\Siswa;
use App\Models\User;
use App\Services\SertifikatService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


// ============================================================
// PBI-130: Testing Proses Penerbitan Sertifikat
// ============================================================

beforeEach(function () {
    $roleAdmin  = Role::firstOrCreate(['nama_role' => 'Admin Akademik']);
    $this->admin = User::factory()->create(['role_id' => $roleAdmin->id]);
    $this->service = new SertifikatService();
});

test('[PBI-125] sertifikat berhasil tersimpan ke database dengan field yang benar', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create();

    $sertifikat = $this->service->terbitkanSertifikat($siswa->id, $kelas->id, $this->admin->id);

    expect($sertifikat)->toBeInstanceOf(Sertifikat::class);
    expect($sertifikat->siswa_id)->toBe($siswa->id);
    expect($sertifikat->kelas_id)->toBe($kelas->id);
    expect($sertifikat->diterbitkan_oleh)->toBe($this->admin->id);
    expect($sertifikat->tanggal_terbit)->not->toBeNull();

    $this->assertDatabaseHas('sertifikat', [
        'siswa_id'         => $siswa->id,
        'kelas_id'         => $kelas->id,
        'diterbitkan_oleh' => $this->admin->id,
    ]);
});

test('[PBI-126] nomor sertifikat otomatis mengikuti format RBN-TAHUN-XXX', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create();

    $sertifikat = $this->service->terbitkanSertifikat($siswa->id, $kelas->id, $this->admin->id);

    $tahun = now()->format('Y');
    expect($sertifikat->nomor_sertifikat)->toBe("RBN-{$tahun}-001");
});

test('[PBI-126] nomor sertifikat increment secara berurutan dan unik', function () {
    $kelas1 = Kelas::factory()->create();
    $kelas2 = Kelas::factory()->create();
    $siswa1 = Siswa::factory()->create();
    $siswa2 = Siswa::factory()->create();

    $sert1 = $this->service->terbitkanSertifikat($siswa1->id, $kelas1->id, $this->admin->id);
    $sert2 = $this->service->terbitkanSertifikat($siswa2->id, $kelas2->id, $this->admin->id);

    $tahun = now()->format('Y');
    expect($sert1->nomor_sertifikat)->toBe("RBN-{$tahun}-001");
    expect($sert2->nomor_sertifikat)->toBe("RBN-{$tahun}-002");
    expect($sert1->nomor_sertifikat)->not->toBe($sert2->nomor_sertifikat);
});

test('[PBI-125] sertifikat tidak bisa diterbitkan dua kali untuk siswa + kelas yang sama', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create();

    $this->service->terbitkanSertifikat($siswa->id, $kelas->id, $this->admin->id);

    expect(fn () => $this->service->terbitkanSertifikat($siswa->id, $kelas->id, $this->admin->id))
        ->toThrow(\Exception::class, 'Sertifikat untuk siswa ini di kelas ini sudah pernah diterbitkan.');
});

test('[PBI-126] verified_url tersimpan dengan format yang benar', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create();

    $sertifikat = $this->service->terbitkanSertifikat($siswa->id, $kelas->id, $this->admin->id);

    expect($sertifikat->verified_url)->toContain('/sertifikat/verifikasi/');
    expect($sertifikat->verified_url)->toContain($sertifikat->nomor_sertifikat);
});

test('[PBI-121] syarat kelulusan: konstanta tersedia di SertifikatService', function () {
    expect(SertifikatService::SYARAT_KEHADIRAN_MIN)->toBe(75);
    expect(SertifikatService::SYARAT_NILAI_MIN)->toBe(70);
});

test('[PBI-124] siswa dengan progress akademik memenuhi syarat terdeteksi layak sertifikat', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create();

    EnrollmentKelas::create([
        'kelas_id'         => $kelas->id,
        'siswa_id'         => $siswa->id,
        'tanggal_bergabung'=> now(),
        'status'           => 'Selesai',
    ]);

    ProgressAkademik::create([
        'siswa_id'                => $siswa->id,
        'kelas_id'                => $kelas->id,
        'persentase_kehadiran'    => 85.0,  // >= 75 ✅
        'rata_nilai_tugas'        => 80.0,  // >= 70 ✅
        'persentase_penyelesaian' => 100.0,
        'status'                  => 'Lulus',
    ]);

    // Siswa harus muncul di query layak sertifikat
    $layak = EnrollmentKelas::where('status', 'Selesai')
        ->whereDoesntHave('siswa.sertifikat', fn ($q) =>
            $q->whereColumn('sertifikat.kelas_id', 'enrollment_kelas.kelas_id')
        )
        ->whereHas('siswa.progressAkademik', fn ($q) =>
            $q->whereColumn('progress_akademik.kelas_id', 'enrollment_kelas.kelas_id')
              ->where('persentase_kehadiran', '>=', SertifikatService::SYARAT_KEHADIRAN_MIN)
              ->where('rata_nilai_tugas', '>=', SertifikatService::SYARAT_NILAI_MIN)
        )
        ->count();

    expect($layak)->toBe(1);
});

test('[PBI-124] siswa yang tidak memenuhi syarat tidak masuk daftar layak sertifikat', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create();

    EnrollmentKelas::create([
        'kelas_id'         => $kelas->id,
        'siswa_id'         => $siswa->id,
        'tanggal_bergabung'=> now(),
        'status'           => 'Selesai',
    ]);

    ProgressAkademik::create([
        'siswa_id'                => $siswa->id,
        'kelas_id'                => $kelas->id,
        'persentase_kehadiran'    => 60.0,  // < 75 ❌
        'rata_nilai_tugas'        => 65.0,  // < 70 ❌
        'persentase_penyelesaian' => 80.0,
        'status'                  => 'Remedial',
    ]);

    $layak = EnrollmentKelas::where('status', 'Selesai')
        ->whereHas('siswa.progressAkademik', fn ($q) =>
            $q->whereColumn('progress_akademik.kelas_id', 'enrollment_kelas.kelas_id')
              ->where('persentase_kehadiran', '>=', SertifikatService::SYARAT_KEHADIRAN_MIN)
              ->where('rata_nilai_tugas', '>=', SertifikatService::SYARAT_NILAI_MIN)
        )
        ->count();

    expect($layak)->toBe(0);
});
