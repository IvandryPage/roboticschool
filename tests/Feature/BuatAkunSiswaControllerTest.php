<?php

/**
 * Test: BuatAkunSiswaController
 *
 * Yang ditest (dari kode controller yang sebenarnya):
 * 1. GET /pendaftaran/{id}/buat-akun — tampil view dengan data pre-filled
 * 2. POST — validasi field wajib (nama, email unique, password rules)
 * 3. POST valid — buat User + Siswa + update pendaftaran.user_id + auto-login
 * 4. Guard duplikat: POST ke pendaftaran yang user_id sudah terisi → auto-login redirect
 * 5. GET ke pendaftaran yang sudah punya akun + user sudah login → redirect dashboard
 */

use App\Models\CalonPeserta;
use App\Models\Pendaftaran;
use App\Models\ProgramKursus;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;

// ─────────────────────────────────────────────────────────────
// SETUP HELPER
// ─────────────────────────────────────────────────────────────

function buatPendaftaranDenganCalonPeserta(): Pendaftaran
{
    $cp = CalonPeserta::factory()->create([
        'nama_lengkap' => 'Tes Calon',
        'email'        => 'tes.calon.' . uniqid() . '@example.com',
        'no_hp'        => '081234567890',
    ]);

    $program = ProgramKursus::factory()->create();

    return Pendaftaran::factory()->create([
        'calon_peserta_id' => $cp->id,
        'program_id'       => $program->id,
        'status'           => 'lunas',
        'user_id'          => null,
    ]);
}

function roleSiswa(): Role
{
    return Role::firstOrCreate(['nama_role' => 'Siswa']);
}

// ─────────────────────────────────────────────────────────────
// GET — SHOW FORM
// ─────────────────────────────────────────────────────────────

test('GET buat-akun menampilkan view dengan data pendaftaran dan calonPeserta', function () {
    roleSiswa();
    $pendaftaran = buatPendaftaranDenganCalonPeserta();

    $response = $this->get(route('pendaftaran.buat-akun', $pendaftaran->id));

    $response->assertOk();
    $response->assertViewIs('pendaftaran.buat-akun');
    $response->assertViewHas('pendaftaran');
    $response->assertViewHas('calonPeserta');
});

test('GET buat-akun ke pendaftaran yang sudah punya akun + user login → redirect dashboard', function () {
    roleSiswa();
    $pendaftaran = buatPendaftaranDenganCalonPeserta();

    // Buat user dan kaitkan ke pendaftaran
    $user = User::factory()->create(['role_id' => roleSiswa()->id]);
    $pendaftaran->update(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('pendaftaran.buat-akun', $pendaftaran->id));

    $response->assertRedirect(route('siswa.dashboard'));
});

// ─────────────────────────────────────────────────────────────
// POST — VALIDASI
// ─────────────────────────────────────────────────────────────

test('POST gagal jika nama_lengkap kosong', function () {
    roleSiswa();
    $pendaftaran = buatPendaftaranDenganCalonPeserta();

    $response = $this->post(route('pendaftaran.buat-akun.store', $pendaftaran->id), [
        'nama_lengkap'          => '',
        'email'                 => 'valid@example.com',
        'password'              => 'Password123',
        'password_confirmation' => 'Password123',
    ]);

    $response->assertSessionHasErrors('nama_lengkap');
    $this->assertDatabaseMissing('users', ['email' => 'valid@example.com']);
});

test('POST gagal jika email sudah dipakai user lain', function () {
    roleSiswa();
    $pendaftaran = buatPendaftaranDenganCalonPeserta();
    User::factory()->create(['email' => 'sudah.ada@example.com']);

    $response = $this->post(route('pendaftaran.buat-akun.store', $pendaftaran->id), [
        'nama_lengkap'          => 'Tes User',
        'email'                 => 'sudah.ada@example.com',
        'password'              => 'Password123',
        'password_confirmation' => 'Password123',
    ]);

    $response->assertSessionHasErrors('email');
    // Tidak boleh ada user kedua dengan email yang sama
    $this->assertCount(1, User::where('email', 'sudah.ada@example.com')->get());
});

test('POST gagal jika password terlalu pendek (< 8 karakter)', function () {
    roleSiswa();
    $pendaftaran = buatPendaftaranDenganCalonPeserta();

    $response = $this->post(route('pendaftaran.buat-akun.store', $pendaftaran->id), [
        'nama_lengkap'          => 'Tes User',
        'email'                 => 'baru@example.com',
        'password'              => 'abc1',
        'password_confirmation' => 'abc1',
    ]);

    $response->assertSessionHasErrors('password');
});

test('POST gagal jika password tidak punya angka (Laravel Password::numbers())', function () {
    roleSiswa();
    $pendaftaran = buatPendaftaranDenganCalonPeserta();

    $response = $this->post(route('pendaftaran.buat-akun.store', $pendaftaran->id), [
        'nama_lengkap'          => 'Tes User',
        'email'                 => 'baru@example.com',
        'password'              => 'HanyaHurufSaja',
        'password_confirmation' => 'HanyaHurufSaja',
    ]);

    $response->assertSessionHasErrors('password');
});

test('POST gagal jika password_confirmation tidak cocok', function () {
    roleSiswa();
    $pendaftaran = buatPendaftaranDenganCalonPeserta();

    $response = $this->post(route('pendaftaran.buat-akun.store', $pendaftaran->id), [
        'nama_lengkap'          => 'Tes User',
        'email'                 => 'baru@example.com',
        'password'              => 'Password123',
        'password_confirmation' => 'BedaBanget456',
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertDatabaseMissing('users', ['email' => 'baru@example.com']);
});

// ─────────────────────────────────────────────────────────────
// POST — HAPPY PATH
// ─────────────────────────────────────────────────────────────

test('POST valid → User dibuat dengan role Siswa, Siswa dibuat, pendaftaran.user_id diisi, auto-login, redirect dashboard', function () {
    roleSiswa();
    $pendaftaran = buatPendaftaranDenganCalonPeserta();
    $email       = 'siswa.baru.' . uniqid() . '@example.com';

    $response = $this->post(route('pendaftaran.buat-akun.store', $pendaftaran->id), [
        'nama_lengkap'          => 'Siswa Baru Test',
        'email'                 => $email,
        'password'              => 'Password123',
        'password_confirmation' => 'Password123',
    ]);

    // 1. Redirect ke dashboard siswa
    $response->assertRedirect(route('siswa.dashboard'));

    // 2. User terbuat di DB
    $this->assertDatabaseHas('users', [
        'email'        => $email,
        'nama_lengkap' => 'Siswa Baru Test',
        'status_aktif' => true,
    ]);

    $user = User::where('email', $email)->firstOrFail();

    // 3. Role harus Siswa
    expect($user->role?->nama_role)->toBe('Siswa');

    // 4. Record Siswa terbuat dan terhubung ke user
    $this->assertDatabaseHas('siswa', ['user_id' => $user->id]);

    // 5. pendaftaran.user_id sudah terisi — guard duplikat aktif
    $pendaftaran->refresh();
    expect($pendaftaran->user_id)->toBe($user->id);

    // 6. User sudah login setelah POST (auto-login)
    $this->assertAuthenticatedAs($user);
});

// ─────────────────────────────────────────────────────────────
// GUARD: DUPLIKAT AKUN
// ─────────────────────────────────────────────────────────────

test('POST ke pendaftaran yang user_id sudah terisi → auto-login user lama, redirect dashboard, tidak buat user baru', function () {
    roleSiswa();
    $pendaftaran = buatPendaftaranDenganCalonPeserta();

    // Simulasi: akun sudah dibuat sebelumnya
    $userLama = User::factory()->create([
        'role_id' => roleSiswa()->id,
        'email'   => 'user.lama@example.com',
    ]);
    $pendaftaran->update(['user_id' => $userLama->id]);

    $userCountSebelum = User::count();

    $response = $this->post(route('pendaftaran.buat-akun.store', $pendaftaran->id), [
        'nama_lengkap'          => 'Percobaan Duplikat',
        'email'                 => 'coba.duplikat@example.com',
        'password'              => 'Password123',
        'password_confirmation' => 'Password123',
    ]);

    // Redirect ke dashboard
    $response->assertRedirect(route('siswa.dashboard'));

    // Tidak ada user baru dibuat
    expect(User::count())->toBe($userCountSebelum);

    // Tidak ada record di DB untuk email percobaan
    $this->assertDatabaseMissing('users', ['email' => 'coba.duplikat@example.com']);
});
