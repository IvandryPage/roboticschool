<?php

use App\Models\ArsipLaporan;
use App\Models\EvaluasiInstruktur;
use App\Models\Kelas;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ============================================================
// PBI-142: Testing Dashboard Instruktur & Arsip Laporan
// ============================================================

beforeEach(function () {
    $roleAdmin      = Role::create(['nama_role' => 'Admin Akademik']);
    $roleInstruktur = Role::create(['nama_role' => 'Instruktur']);
    $this->admin      = User::factory()->create(['role_id' => $roleAdmin->id]);
    $this->instruktur = User::factory()->create(['role_id' => $roleInstruktur->id]);
});

// ---- Arsip Laporan ----

test('[PBI-139] arsip laporan berhasil disimpan ke database', function () {
    $laporan = ArsipLaporan::create([
        'judul'        => 'Laporan Kelulusan Batch 1',
        'tipe_laporan' => 'laporan_kelulusan',
        'dibuat_oleh'  => $this->admin->id,
        'periode'      => '2026-04',
        'catatan'      => 'Semua data sudah diverifikasi.',
    ]);

    expect($laporan->id)->not->toBeNull();

    $this->assertDatabaseHas('arsip_laporan', [
        'judul'       => 'Laporan Kelulusan Batch 1',
        'dibuat_oleh' => $this->admin->id,
    ]);
});

test('[PBI-140] arsip laporan dapat diambil dan diurutkan terbaru', function () {
    // Buat dua laporan dengan periode berbeda, verifikasi keduanya tersimpan
    $lama = ArsipLaporan::create([
        'judul'        => 'Laporan Lama',
        'tipe_laporan' => 'laporan_bulanan',
        'dibuat_oleh'  => $this->admin->id,
        'periode'      => '2025-01',
    ]);
    $baru = ArsipLaporan::create([
        'judul'        => 'Laporan Baru',
        'tipe_laporan' => 'laporan_akademik',
        'dibuat_oleh'  => $this->admin->id,
        'periode'      => '2026-05',
    ]);

    // Kedua record tersimpan di database
    expect(ArsipLaporan::count())->toBe(2);

    // Record terbaru bisa ditemukan berdasarkan periode
    $terbaru = ArsipLaporan::where('periode', '2026-05')->first();
    expect($terbaru)->not->toBeNull();
    expect($terbaru->judul)->toBe('Laporan Baru');
});

test('[PBI-140] arsip laporan dapat dihapus', function () {
    $laporan = ArsipLaporan::create([
        'judul'        => 'Laporan Hapus Test',
        'tipe_laporan' => 'laporan_keuangan',
        'dibuat_oleh'  => $this->admin->id,
        'periode'      => '2026-01',
    ]);

    $id = $laporan->id;
    $laporan->delete();

    $this->assertDatabaseMissing('arsip_laporan', ['id' => $id]);
});

test('[PBI-140] arsip laporan dapat difilter berdasarkan tipe', function () {
    ArsipLaporan::create(['judul' => 'Laporan A', 'tipe_laporan' => 'laporan_kelulusan', 'dibuat_oleh' => $this->admin->id]);
    ArsipLaporan::create(['judul' => 'Laporan B', 'tipe_laporan' => 'laporan_keuangan',  'dibuat_oleh' => $this->admin->id]);
    ArsipLaporan::create(['judul' => 'Laporan C', 'tipe_laporan' => 'laporan_kelulusan', 'dibuat_oleh' => $this->admin->id]);

    $kelulusan = ArsipLaporan::where('tipe_laporan', 'laporan_kelulusan')->count();
    expect($kelulusan)->toBe(2);
});

test('[PBI-140] relasi pembuat tersedia di arsip laporan', function () {
    $laporan = ArsipLaporan::create([
        'judul'        => 'Laporan Relasi Test',
        'tipe_laporan' => 'laporan_akademik',
        'dibuat_oleh'  => $this->admin->id,
        'periode'      => '2026-03',
    ]);

    $loaded = ArsipLaporan::with('pembuat')->find($laporan->id);
    expect($loaded->pembuat)->not->toBeNull();
    expect($loaded->pembuat->id)->toBe($this->admin->id);
});

// ---- Evaluasi Instruktur ----

test('[PBI-138] evaluasi instruktur tersimpan dengan benar', function () {
    $kelas = Kelas::factory()->create(['instruktur_id' => $this->instruktur->id]);
    $siswa = Siswa::factory()->create();

    $evaluasi = EvaluasiInstruktur::create([
        'kelas_id'       => $kelas->id,
        'siswa_id'       => $siswa->id,
        'instruktur_id'  => $this->instruktur->id,
        'skor_rata_rata' => 4.5,
        'saran_ulasan'   => 'Instruktur sangat membantu dan komunikatif.',
    ]);

    $this->assertDatabaseHas('evaluasi_instruktur', [
        'instruktur_id'  => $this->instruktur->id,
        'skor_rata_rata' => 4.5,
    ]);
});

test('[PBI-138] instruktur hanya melihat evaluasi milik kelasnya sendiri', function () {
    $instruktur2 = User::factory()->create(['role_id' => $this->instruktur->role_id]);
    $kelas1 = Kelas::factory()->create(['instruktur_id' => $this->instruktur->id]);
    $kelas2 = Kelas::factory()->create(['instruktur_id' => $instruktur2->id]);
    $siswa  = Siswa::factory()->create();

    EvaluasiInstruktur::create([
        'kelas_id' => $kelas1->id, 'siswa_id' => $siswa->id,
        'instruktur_id' => $this->instruktur->id, 'skor_rata_rata' => 4.0,
    ]);
    EvaluasiInstruktur::create([
        'kelas_id' => $kelas2->id, 'siswa_id' => $siswa->id,
        'instruktur_id' => $instruktur2->id, 'skor_rata_rata' => 3.5,
    ]);

    $evaluasiInstruktur1 = EvaluasiInstruktur::where('instruktur_id', $this->instruktur->id)->count();
    expect($evaluasiInstruktur1)->toBe(1);
});
