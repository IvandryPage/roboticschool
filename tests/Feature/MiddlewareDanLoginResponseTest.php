<?php

/**
 * Test: EnsureSiswa + EnsureTimPublikasi Middleware + LoginResponse
 *
 * Ini adalah test paling kritis — jika middleware atau LoginResponse salah,
 * seluruh access control sistem collapse.
 *
 * EnsureSiswa.handle():
 * - Unauthenticated → redirect /login
 * - Authenticated tapi role != 'Siswa' → abort(403)
 * - Authenticated + role = 'Siswa' → lanjut
 *
 * EnsureTimPublikasi.handle():
 * - Unauthenticated → redirect /login
 * - Authenticated tapi role != 'Tim Publikasi' → abort(403)
 * - Authenticated + role = 'Tim Publikasi' → lanjut
 *
 * LoginResponse.toResponse():
 * - Admin Akademik → /admin
 * - Instruktur → /admin
 * - Direktur → /admin
 * - Tim Publikasi → /publikasi
 * - Siswa → route('siswa.dashboard')
 * - Role tidak dikenal → /dashboard
 */

use App\Http\Middleware\EnsureSiswa;
use App\Http\Middleware\EnsureTimPublikasi;
use App\Http\Responses\LoginResponse;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ─────────────────────────────────────────────────────────────
// HELPER
// ─────────────────────────────────────────────────────────────

function userDenganRole(string $namaRole): User
{
    $role = Role::firstOrCreate(['nama_role' => $namaRole]);
    return User::factory()->create(['role_id' => $role->id]);
}

// ─────────────────────────────────────────────────────────────
// ENSURASISWA — via HTTP request ke route yang di-guard
// Menggunakan /keluhan sebagai probe (pakai middleware role.siswa)
// ─────────────────────────────────────────────────────────────

test('EnsureSiswa: unauthenticated → redirect /login', function () {
    $response = $this->get('/keluhan');
    $response->assertRedirect('/login');
});

test('EnsureSiswa: role Siswa → lanjut (200)', function () {
    $siswa = userDenganRole('Siswa');
    $response = $this->actingAs($siswa)->get('/keluhan');
    $response->assertOk();
});

test('EnsureSiswa: role Admin Akademik → 403', function () {
    $admin = userDenganRole('Admin Akademik');
    $response = $this->actingAs($admin)->get('/keluhan');
    $response->assertForbidden();
});

test('EnsureSiswa: role Instruktur → 403', function () {
    $instruktur = userDenganRole('Instruktur');
    $response   = $this->actingAs($instruktur)->get('/keluhan');
    $response->assertForbidden();
});

test('EnsureSiswa: role Direktur → 403', function () {
    $direktur = userDenganRole('Direktur');
    $response = $this->actingAs($direktur)->get('/keluhan');
    $response->assertForbidden();
});

test('EnsureSiswa: role Tim Publikasi → 403', function () {
    $publikasi = userDenganRole('Tim Publikasi');
    $response  = $this->actingAs($publikasi)->get('/keluhan');
    $response->assertForbidden();
});

test('EnsureSiswa: user tanpa role (role_id null) → 403', function () {
    $user = User::factory()->create(['role_id' => null]);
    $response = $this->actingAs($user)->get('/keluhan');
    $response->assertForbidden();
});

// ─────────────────────────────────────────────────────────────
// ENSURÉTIMPUBLIKASI — via HTTP
// Panel /publikasi dilindungi middleware ini di PublikasiPanelProvider
// Test via route langsung ke panel
// ─────────────────────────────────────────────────────────────

test('EnsureTimPublikasi: Tim Publikasi → bisa akses /publikasi', function () {
    $user     = userDenganRole('Tim Publikasi');
    $response = $this->actingAs($user)->get('/publikasi');
    // 200 atau redirect internal Filament — yang penting bukan 403 atau /login
    $this->assertNotEquals(403, $response->getStatusCode());
    $this->assertNotEquals(302, $response->getStatusCode() === 302 && str_contains($response->headers->get('Location', ''), 'login'));
});

test('EnsureTimPublikasi: Admin Akademik → 403 di /publikasi', function () {
    $admin    = userDenganRole('Admin Akademik');
    $response = $this->actingAs($admin)->get('/publikasi');
    $response->assertForbidden();
});

test('EnsureTimPublikasi: Instruktur → 403 di /publikasi', function () {
    $instruktur = userDenganRole('Instruktur');
    $response   = $this->actingAs($instruktur)->get('/publikasi');
    $response->assertForbidden();
});

test('EnsureTimPublikasi: Siswa → 403 di /publikasi', function () {
    $siswa    = userDenganRole('Siswa');
    $response = $this->actingAs($siswa)->get('/publikasi');
    $response->assertForbidden();
});

test('EnsureTimPublikasi: unauthenticated → redirect /login', function () {
    $response = $this->get('/publikasi');
    $response->assertRedirect('/login');
});

// ─────────────────────────────────────────────────────────────
// LOGINRESPONSE — unit test langsung via POST /login
// ─────────────────────────────────────────────────────────────

/**
 * Test via POST /login (Fortify default) — tidak bisa test LoginResponse
 * secara unit karena ia di-inject oleh Fortify container.
 * Cara paling reliable: login langsung via form.
 */

test('LoginResponse: Admin Akademik redirect ke /admin setelah login', function () {
    $admin    = userDenganRole('Admin Akademik');
    $admin->update(['password' => bcrypt('secret123')]);

    $response = $this->post('/login', [
        'email'    => $admin->email,
        'password' => 'secret123',
    ]);

    $response->assertRedirect('/admin');
});

test('LoginResponse: Instruktur redirect ke /admin setelah login', function () {
    $instruktur = userDenganRole('Instruktur');
    $instruktur->update(['password' => bcrypt('secret123')]);

    $response = $this->post('/login', [
        'email'    => $instruktur->email,
        'password' => 'secret123',
    ]);

    $response->assertRedirect('/admin');
});

test('LoginResponse: Direktur redirect ke /admin setelah login', function () {
    $direktur = userDenganRole('Direktur');
    $direktur->update(['password' => bcrypt('secret123')]);

    $response = $this->post('/login', [
        'email'    => $direktur->email,
        'password' => 'secret123',
    ]);

    $response->assertRedirect('/admin');
});

test('LoginResponse: Tim Publikasi redirect ke /publikasi — BUKAN /admin', function () {
    $publikasi = userDenganRole('Tim Publikasi');
    $publikasi->update(['password' => bcrypt('secret123')]);

    $response = $this->post('/login', [
        'email'    => $publikasi->email,
        'password' => 'secret123',
    ]);

    // Ini yang bug di test lama — harus /publikasi, bukan /admin
    $response->assertRedirect('/publikasi');
    $this->assertStringNotContainsString('/admin', $response->headers->get('Location', ''));
});

test('LoginResponse: Siswa redirect ke siswa.dashboard setelah login', function () {
    $siswa = userDenganRole('Siswa');
    $siswa->update(['password' => bcrypt('secret123')]);

    $response = $this->post('/login', [
        'email'    => $siswa->email,
        'password' => 'secret123',
    ]);

    $response->assertRedirect(route('siswa.dashboard'));
});

test('LoginResponse: user tanpa role redirect ke /dashboard (default case)', function () {
    $user = User::factory()->create([
        'role_id'  => null,
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => 'secret123',
    ]);

    $response->assertRedirect('/dashboard');
});

// ─────────────────────────────────────────────────────────────
// REGRESSION: role tidak boleh cross-redirect
// ─────────────────────────────────────────────────────────────

test('REGRESSION: Instruktur tidak pernah redirect ke /siswa/dashboard', function () {
    $instruktur = userDenganRole('Instruktur');
    $instruktur->update(['password' => bcrypt('secret123')]);

    $response = $this->post('/login', [
        'email'    => $instruktur->email,
        'password' => 'secret123',
    ]);

    $location = $response->headers->get('Location', '');
    $this->assertStringNotContainsString('siswa', $location);
});

test('REGRESSION: Tim Publikasi tidak pernah redirect ke /siswa/dashboard', function () {
    $publikasi = userDenganRole('Tim Publikasi');
    $publikasi->update(['password' => bcrypt('secret123')]);

    $response = $this->post('/login', [
        'email'    => $publikasi->email,
        'password' => 'secret123',
    ]);

    $location = $response->headers->get('Location', '');
    $this->assertStringNotContainsString('siswa', $location);
});

test('REGRESSION: Siswa tidak pernah redirect ke /admin atau /publikasi', function () {
    $siswa = userDenganRole('Siswa');
    $siswa->update(['password' => bcrypt('secret123')]);

    $response = $this->post('/login', [
        'email'    => $siswa->email,
        'password' => 'secret123',
    ]);

    $location = $response->headers->get('Location', '');
    $this->assertStringNotContainsString('/admin', $location);
    $this->assertStringNotContainsString('/publikasi', $location);
});
