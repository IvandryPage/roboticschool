<?php

/**
 * Test: TugasController (kumpul tugas) + SertifikatController (milikku + verifikasi)
 *
 * TugasController.kumpul — logic yang ditest:
 * 1. Guard: siswa tidak ada di DB → abort 403
 * 2. Guard: deadline sudah lewat → error flash, tidak simpan
 * 3. Guard: sudah pernah kumpul → error flash, tidak buat record duplikat
 * 4. POST valid tanpa file → PengumpulanTugas tersimpan, waktu_kumpul=now, status=Menunggu
 * 5. POST valid dengan file → file_jawaban tersimpan di storage
 *
 * SertifikatController.milikku:
 * 1. Siswa yang punya sertifikat → tampil di view
 * 2. Non-siswa (Admin) → redirect ke /admin dengan flash warning
 *
 * SertifikatController.verifikasi (publik):
 * 1. Nomor valid → tampil data sertifikat
 * 2. Nomor tidak ada → 404
 */

use App\Models\Batch;
use App\Models\EnrollmentKelas;
use App\Models\Kelas;
use App\Models\PengumpulanTugas;
use App\Models\ProgramKursus;
use App\Models\Role;
use App\Models\Sertifikat;
use App\Models\SesiLive;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// ─────────────────────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────────────────────

function siswaAktifDenganTugas(): array
{
    $roleSiswa = Role::firstOrCreate(['nama_role' => 'Siswa']);
    $user      = User::factory()->create(['role_id' => $roleSiswa->id]);
    $siswa     = Siswa::factory()->create(['user_id' => $user->id]);

    $instrukturRole = Role::firstOrCreate(['nama_role' => 'Instruktur']);
    $instruktur     = User::factory()->create(['role_id' => $instrukturRole->id]);

    $kelas = Kelas::factory()->create(['instruktur_id' => $instruktur->id, 'status' => 'Aktif']);
    EnrollmentKelas::factory()->create(['siswa_id' => $siswa->id, 'kelas_id' => $kelas->id, 'status' => 'Aktif']);

    $sesi  = SesiLive::factory()->create(['kelas_id' => $kelas->id]);
    $tugas = Tugas::factory()->create([
        'sesi_id'     => $sesi->id,
        'batas_waktu' => now()->addDays(7), // belum deadline
    ]);

    return [$user, $siswa, $tugas];
}

// ─────────────────────────────────────────────────────────────
// TUGASCONTROLLER — GET /siswa/tugas
// ─────────────────────────────────────────────────────────────

test('GET /siswa/tugas menampilkan tugas dari kelas yang diikuti siswa', function () {
    [$user, $siswa, $tugas] = siswaAktifDenganTugas();

    $response = $this->actingAs($user)->get('/siswa/tugas');

    $response->assertOk();
    $response->assertViewIs('siswa.tugas.index');
    $response->assertViewHas('tugas', fn ($list) => $list->isNotEmpty());
    $response->assertViewHas('siswa');
});

test('GET /siswa/tugas tidak menampilkan tugas dari kelas yang tidak diikuti', function () {
    $roleSiswa = Role::firstOrCreate(['nama_role' => 'Siswa']);
    $user      = User::factory()->create(['role_id' => $roleSiswa->id]);
    Siswa::factory()->create(['user_id' => $user->id]);
    // Tidak ada enrollment → tugas kosong

    $response = $this->actingAs($user)->get('/siswa/tugas');

    $response->assertOk();
    $response->assertViewHas('tugas', fn ($list) => $list->isEmpty());
});

// ─────────────────────────────────────────────────────────────
// TUGASCONTROLLER — POST kumpul
// ─────────────────────────────────────────────────────────────

test('POST kumpul valid tanpa file → PengumpulanTugas tersimpan dengan status Menunggu', function () {
    [$user, $siswa, $tugas] = siswaAktifDenganTugas();

    $response = $this->actingAs($user)->post(
        route('siswa.tugas.kumpul', $tugas->id),
        ['catatan_siswa' => 'Ini jawaban saya tanpa file.']
    );

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('pengumpulan_tugas', [
        'tugas_id'         => $tugas->id,
        'siswa_id'         => $siswa->id,
        'status_penilaian' => 'Menunggu',
    ]);
});

test('POST kumpul valid dengan file → file_jawaban tersimpan di storage public', function () {
    [$user, $siswa, $tugas] = siswaAktifDenganTugas();
    Storage::fake('public');

    $this->actingAs($user)->post(
        route('siswa.tugas.kumpul', $tugas->id),
        [
            'file_jawaban'  => UploadedFile::fake()->create('jawaban.pdf', 500, 'application/pdf'),
            'catatan_siswa' => 'Jawaban dengan file.',
        ]
    );

    $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)->firstOrFail();
    expect($pengumpulan->file_jawaban)->not->toBeNull();
    Storage::disk('public')->assertExists($pengumpulan->file_jawaban);
});

test('POST kumpul → waktu_kumpul tersimpan, bukan null', function () {
    [$user, $siswa, $tugas] = siswaAktifDenganTugas();

    $this->actingAs($user)->post(route('siswa.tugas.kumpul', $tugas->id), []);

    $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)->firstOrFail();
    expect($pengumpulan->waktu_kumpul)->not->toBeNull();
});

// ─── Guard: Deadline Lewat ────────────────────────────────────

test('POST kumpul setelah deadline → error flash, record tidak dibuat', function () {
    [$user, $siswa, $tugasExpired] = siswaAktifDenganTugas();

    // Paksa deadline ke masa lalu
    $tugasExpired->update(['batas_waktu' => now()->subMinute()]);

    $response = $this->actingAs($user)->post(
        route('siswa.tugas.kumpul', $tugasExpired->id),
        ['catatan_siswa' => 'Terlambat']
    );

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $this->assertDatabaseMissing('pengumpulan_tugas', [
        'tugas_id' => $tugasExpired->id,
        'siswa_id' => $siswa->id,
    ]);
});

// ─── Guard: Duplikat Pengumpulan ─────────────────────────────

test('POST kumpul kedua kali untuk tugas yang sama → error flash, tidak buat record duplikat', function () {
    [$user, $siswa, $tugas] = siswaAktifDenganTugas();

    // Kumpul pertama
    $this->actingAs($user)->post(route('siswa.tugas.kumpul', $tugas->id), []);
    $this->assertDatabaseCount('pengumpulan_tugas', 1);

    // Kumpul kedua
    $response = $this->actingAs($user)->post(
        route('siswa.tugas.kumpul', $tugas->id),
        ['catatan_siswa' => 'Coba lagi']
    );

    $response->assertSessionHas('error');
    // Tetap hanya 1 record
    $this->assertDatabaseCount('pengumpulan_tugas', 1);
});

test('POST kumpul untuk tugas tanpa deadline (batas_waktu null) → berhasil', function () {
    [$user, $siswa, $tugas] = siswaAktifDenganTugas();
    $tugas->update(['batas_waktu' => null]);

    $response = $this->actingAs($user)->post(
        route('siswa.tugas.kumpul', $tugas->id),
        ['catatan_siswa' => 'Tugas tanpa deadline.']
    );

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('pengumpulan_tugas', ['tugas_id' => $tugas->id]);
});

// ─────────────────────────────────────────────────────────────
// SERTIFIKAT — milikku (/sertifikat/saya)
// ─────────────────────────────────────────────────────────────

test('GET /sertifikat/saya untuk siswa menampilkan sertifikat miliknya', function () {
    $roleSiswa = Role::firstOrCreate(['nama_role' => 'Siswa']);
    $user      = User::factory()->create(['role_id' => $roleSiswa->id]);
    $siswa     = Siswa::factory()->create(['user_id' => $user->id]);
    $kelas     = Kelas::factory()->create();

    Sertifikat::factory()->create([
        'siswa_id' => $siswa->id,
        'kelas_id' => $kelas->id,
    ]);

    $response = $this->actingAs($user)->get('/sertifikat/saya');

    $response->assertOk();
    $response->assertViewIs('sertifikat.show');
    $response->assertViewHas('sertifikats', fn ($list) => $list->count() === 1);
});

test('GET /sertifikat/saya untuk siswa tanpa sertifikat → list kosong, bukan error', function () {
    $roleSiswa = Role::firstOrCreate(['nama_role' => 'Siswa']);
    $user      = User::factory()->create(['role_id' => $roleSiswa->id]);
    Siswa::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/sertifikat/saya');

    $response->assertOk();
    $response->assertViewHas('sertifikats', fn ($list) => $list->isEmpty());
});

test('GET /sertifikat/saya untuk Admin (tidak punya siswa) → redirect /admin dengan flash warning', function () {
    $role  = Role::firstOrCreate(['nama_role' => 'Admin Akademik']);
    $admin = User::factory()->create(['role_id' => $role->id]);
    // Admin tidak punya relasi siswa

    $response = $this->actingAs($admin)->get('/sertifikat/saya');

    $response->assertRedirect('/admin');
    $response->assertSessionHas('warning');
});

test('GET /sertifikat/saya hanya menampilkan sertifikat milik siswa yang login, bukan siswa lain', function () {
    $roleSiswa = Role::firstOrCreate(['nama_role' => 'Siswa']);
    $userA     = User::factory()->create(['role_id' => $roleSiswa->id]);
    $siswaA    = Siswa::factory()->create(['user_id' => $userA->id]);
    $userB     = User::factory()->create(['role_id' => $roleSiswa->id]);
    $siswaB    = Siswa::factory()->create(['user_id' => $userB->id]);
    $kelas     = Kelas::factory()->create();

    // Sertifikat milik B
    Sertifikat::factory()->create(['siswa_id' => $siswaB->id, 'kelas_id' => $kelas->id]);

    // A login — tidak boleh lihat sertifikat B
    $response = $this->actingAs($userA)->get('/sertifikat/saya');

    $response->assertViewHas('sertifikats', fn ($list) => $list->isEmpty());
});

// ─────────────────────────────────────────────────────────────
// SERTIFIKAT — verifikasi publik (/sertifikat/verifikasi/{nomor})
// ─────────────────────────────────────────────────────────────

test('GET verifikasi sertifikat dengan nomor valid → tampil data sertifikat', function () {
    $kelas  = Kelas::factory()->create();
    $siswa  = Siswa::factory()->create();
    $cert   = Sertifikat::factory()->create([
        'siswa_id'         => $siswa->id,
        'kelas_id'         => $kelas->id,
        'nomor_sertifikat' => 'CERT-2025-ROB-0001',
    ]);

    // Akses tanpa login (route publik)
    $response = $this->get(route('sertifikat.verifikasi', 'CERT-2025-ROB-0001'));

    $response->assertOk();
    $response->assertViewIs('sertifikat.verifikasi');
    $response->assertViewHas('sertifikat', fn ($s) => $s->id === $cert->id);
});

test('GET verifikasi sertifikat dengan nomor tidak ada → 404', function () {
    $response = $this->get(route('sertifikat.verifikasi', 'CERT-TIDAK-ADA-9999'));

    $response->assertNotFound();
});

test('GET verifikasi sertifikat bisa diakses tanpa login (publik)', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create();
    Sertifikat::factory()->create([
        'siswa_id'         => $siswa->id,
        'kelas_id'         => $kelas->id,
        'nomor_sertifikat' => 'CERT-2025-ROB-0002',
    ]);

    // Tidak pakai actingAs — akses sebagai guest
    $response = $this->get(route('sertifikat.verifikasi', 'CERT-2025-ROB-0002'));

    $response->assertOk();
    // Pastikan tidak redirect ke /login
    $response->assertDontSee('login');
});
