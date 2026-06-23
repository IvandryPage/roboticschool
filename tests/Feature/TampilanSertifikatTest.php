<?php

use App\Models\Kelas;
use App\Models\Role;
use App\Models\Sertifikat;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ============================================================
// PBI-131: Testing Tampilan & Proses Cetak Sertifikat
// ============================================================

beforeEach(function () {
    $roleSiswa   = Role::firstOrCreate(['nama_role' => 'Siswa']);
    $roleAdmin   = Role::firstOrCreate(['nama_role' => 'Admin Akademik']);
    $this->admin = User::factory()->create(['role_id' => $roleAdmin->id]);
    $this->user  = User::factory()->create(['role_id' => $roleSiswa->id]);
    $this->siswa = Siswa::factory()->create(['user_id' => $this->user->id]);
    $this->kelas = Kelas::factory()->create();
});

test('[PBI-128] halaman verifikasi sertifikat dapat diakses publik tanpa login', function () {
    $sertifikat = Sertifikat::create([
        'nomor_sertifikat' => 'RBN-2026-001',
        'siswa_id'         => $this->siswa->id,
        'kelas_id'         => $this->kelas->id,
        'diterbitkan_oleh' => $this->admin->id,
        'tanggal_terbit'   => now(),
        'verified_url'     => url('/sertifikat/verifikasi/RBN-2026-001'),
    ]);

    $response = $this->get(route('sertifikat.verifikasi', 'RBN-2026-001'));

    $response->assertOk();
    $response->assertSee('RBN-2026-001');
    $response->assertSee('Verifikasi Sertifikat');
    $response->assertSee('Valid dan Terdaftar Resmi');
});

test('[PBI-128] halaman verifikasi menampilkan nama siswa dan kelas dengan benar', function () {
    Sertifikat::create([
        'nomor_sertifikat' => 'RBN-2026-002',
        'siswa_id'         => $this->siswa->id,
        'kelas_id'         => $this->kelas->id,
        'diterbitkan_oleh' => $this->admin->id,
        'tanggal_terbit'   => now(),
    ]);

    $response = $this->get(route('sertifikat.verifikasi', 'RBN-2026-002'));

    $response->assertOk();
    $response->assertSee($this->user->nama_lengkap);
    $response->assertSee($this->kelas->nama_kelas);
});

test('[PBI-128] halaman verifikasi mengembalikan 404 jika nomor sertifikat tidak ada', function () {
    $response = $this->get(route('sertifikat.verifikasi', 'RBN-9999-999'));
    $response->assertNotFound();
});

test('[PBI-127] halaman sertifikat siswa memerlukan autentikasi', function () {
    $response = $this->get(route('sertifikat.saya'));
    $response->assertRedirect();
});

test('[PBI-127] siswa yang login dapat mengakses halaman sertifikat miliknya', function () {
    Sertifikat::create([
        'nomor_sertifikat' => 'RBN-2026-003',
        'siswa_id'         => $this->siswa->id,
        'kelas_id'         => $this->kelas->id,
        'diterbitkan_oleh' => $this->admin->id,
        'tanggal_terbit'   => now(),
        'verified_url'     => url('/sertifikat/verifikasi/RBN-2026-003'),
    ]);

    $response = $this->actingAs($this->user)->get(route('sertifikat.saya'));

    $response->assertOk();
    $response->assertSee('RBN-2026-003');
    $response->assertSee('Sertifikat Saya');
});

test('[PBI-127] siswa yang belum punya sertifikat melihat pesan belum ada sertifikat', function () {
    $response = $this->actingAs($this->user)->get(route('sertifikat.saya'));

    $response->assertOk();
    $response->assertSee('Belum ada sertifikat');
});

test('[PBI-128] halaman sertifikat mengandung tombol cetak (window.print)', function () {
    Sertifikat::create([
        'nomor_sertifikat' => 'RBN-2026-004',
        'siswa_id'         => $this->siswa->id,
        'kelas_id'         => $this->kelas->id,
        'diterbitkan_oleh' => $this->admin->id,
        'tanggal_terbit'   => now(),
    ]);

    $response = $this->actingAs($this->user)->get(route('sertifikat.saya'));

    $response->assertOk();
    $response->assertSee('window.print()');
    // Tombol cetak bisa berupa 'Cetak Sertifikat' atau 'Cetak Semua Sertifikat'
    $response->assertSee('Cetak');
});
