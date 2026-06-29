<?php

/**
 * Test: KeluhanController
 *
 * Logic yang ditest dari controller asli:
 * 1. GET /keluhan — form create (bukan riwayat)
 * 2. GET /keluhan/saya — riwayat milik user yang login, bukan milik user lain
 * 3. POST /keluhan — validasi field wajib
 * 4. POST valid → TiketKeluhan tersimpan, status 'Open', pelapor_id = Auth::user()->id
 * 5. POST valid → response back()->with('success_modal', true)
 * 6. Prioritas default 'Sedang' jika tidak dikirim
 * 7. EnsureSiswa: non-siswa diblokir dari semua route keluhan
 */

use App\Models\Role;
use App\Models\TiketKeluhan;
use App\Models\User;

// ─────────────────────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────────────────────

function buatUserDenganRole(string $namaRole): User
{
    $role = Role::firstOrCreate(['nama_role' => $namaRole]);
    return User::factory()->create(['role_id' => $role->id]);
}

function payloadKeluhan(array $override = []): array
{
    return array_merge([
        'kategori' => 'Pembelajaran',
        'subjek'   => 'Materi tidak bisa diakses',
        'prioritas'=> 'Sedang',
        'deskripsi'=> 'Saya tidak bisa membuka materi pertemuan ke-3. Mohon bantuan.',
    ], $override);
}

// ─────────────────────────────────────────────────────────────
// GET — FORM CREATE
// ─────────────────────────────────────────────────────────────

test('GET /keluhan menampilkan view form keluhan untuk siswa', function () {
    $siswa = buatUserDenganRole('Siswa');

    $response = $this->actingAs($siswa)->get('/keluhan');

    $response->assertOk();
    $response->assertViewIs('keluhan.index');
});

test('GET /keluhan tanpa login → redirect ke /login', function () {
    $response = $this->get('/keluhan');

    $response->assertRedirect('/login');
});

test('GET /keluhan sebagai Admin → 403 (EnsureSiswa)', function () {
    $admin = buatUserDenganRole('Admin Akademik');

    $response = $this->actingAs($admin)->get('/keluhan');

    $response->assertForbidden();
});

test('GET /keluhan sebagai Instruktur → 403 (EnsureSiswa)', function () {
    $instruktur = buatUserDenganRole('Instruktur');

    $response = $this->actingAs($instruktur)->get('/keluhan');

    $response->assertForbidden();
});

// ─────────────────────────────────────────────────────────────
// GET — RIWAYAT /keluhan/saya
// ─────────────────────────────────────────────────────────────

test('GET /keluhan/saya mengembalikan hanya tiket milik user yang login', function () {
    $siswa1 = buatUserDenganRole('Siswa');
    $siswa2 = buatUserDenganRole('Siswa');

    // Buat tiket untuk siswa1
    TiketKeluhan::factory()->create(['pelapor_id' => $siswa1->id, 'status' => 'Open']);
    TiketKeluhan::factory()->create(['pelapor_id' => $siswa1->id, 'status' => 'Resolved']);

    // Buat tiket untuk siswa2 — tidak boleh ikut tampil
    TiketKeluhan::factory()->create(['pelapor_id' => $siswa2->id, 'status' => 'Open']);

    $response = $this->actingAs($siswa1)->get('/keluhan/saya');

    $response->assertOk();
    $response->assertViewHas('tiketKeluhans', fn ($list) => $list->count() === 2);
    $response->assertViewHas('tiketKeluhans', fn ($list) =>
        $list->every(fn ($t) => $t->pelapor_id === $siswa1->id)
    );
});

test('GET /keluhan/saya untuk user tanpa tiket → list kosong, bukan error', function () {
    $siswa = buatUserDenganRole('Siswa');

    $response = $this->actingAs($siswa)->get('/keluhan/saya');

    $response->assertOk();
    $response->assertViewHas('tiketKeluhans', fn ($list) => $list->isEmpty());
});

test('GET /keluhan/saya tanpa login → redirect ke /login', function () {
    $response = $this->get('/keluhan/saya');

    $response->assertRedirect('/login');
});

// ─────────────────────────────────────────────────────────────
// POST — VALIDASI
// ─────────────────────────────────────────────────────────────

test('POST /keluhan tanpa kategori → error validasi', function () {
    $siswa = buatUserDenganRole('Siswa');

    $response = $this->actingAs($siswa)->post('/keluhan', payloadKeluhan(['kategori' => '']));

    $response->assertSessionHasErrors('kategori');
    $this->assertDatabaseCount('tiket_keluhan', 0);
});

test('POST /keluhan tanpa subjek → error validasi', function () {
    $siswa = buatUserDenganRole('Siswa');

    $response = $this->actingAs($siswa)->post('/keluhan', payloadKeluhan(['subjek' => '']));

    $response->assertSessionHasErrors('subjek');
    $this->assertDatabaseCount('tiket_keluhan', 0);
});

test('POST /keluhan tanpa deskripsi → error validasi', function () {
    $siswa = buatUserDenganRole('Siswa');

    $response = $this->actingAs($siswa)->post('/keluhan', payloadKeluhan(['deskripsi' => '']));

    $response->assertSessionHasErrors('deskripsi');
    $this->assertDatabaseCount('tiket_keluhan', 0);
});

test('POST /keluhan dengan prioritas invalid → error validasi (hanya Rendah|Sedang|Tinggi)', function () {
    $siswa = buatUserDenganRole('Siswa');

    $response = $this->actingAs($siswa)->post('/keluhan', payloadKeluhan(['prioritas' => 'KRITIS']));

    $response->assertSessionHasErrors('prioritas');
    $this->assertDatabaseCount('tiket_keluhan', 0);
});

// ─────────────────────────────────────────────────────────────
// POST — HAPPY PATH
// ─────────────────────────────────────────────────────────────

test('POST valid → TiketKeluhan tersimpan dengan pelapor_id = user yang login', function () {
    $siswa = buatUserDenganRole('Siswa');

    $this->actingAs($siswa)->post('/keluhan', payloadKeluhan());

    $this->assertDatabaseHas('tiket_keluhan', [
        'pelapor_id' => $siswa->id,
        'kategori'   => 'Pembelajaran',
        'subjek'     => 'Materi tidak bisa diakses',
        'status'     => 'Open',
    ]);
});

test('POST valid → status tiket selalu "Open" saat dibuat, bukan status lain', function () {
    $siswa = buatUserDenganRole('Siswa');

    $this->actingAs($siswa)->post('/keluhan', payloadKeluhan());

    $tiket = TiketKeluhan::where('pelapor_id', $siswa->id)->firstOrFail();
    expect($tiket->status)->toBe('Open');
});

test('POST valid → response adalah redirect back dengan flash success_modal=true', function () {
    $siswa = buatUserDenganRole('Siswa');

    $response = $this->actingAs($siswa)->post('/keluhan', payloadKeluhan());

    // back() akan redirect ke referer atau '/' jika referer tidak ada
    $response->assertRedirect();
    $response->assertSessionHas('success_modal', true);
});

test('POST tanpa prioritas → default "Sedang" tersimpan di DB', function () {
    $siswa = buatUserDenganRole('Siswa');

    // Kirim tanpa field prioritas sama sekali
    $payload = payloadKeluhan();
    unset($payload['prioritas']);

    $this->actingAs($siswa)->post('/keluhan', $payload);

    $this->assertDatabaseHas('tiket_keluhan', [
        'pelapor_id' => $siswa->id,
        'prioritas'  => 'Sedang',
    ]);
});

test('POST dengan prioritas Tinggi → tersimpan sebagai Tinggi', function () {
    $siswa = buatUserDenganRole('Siswa');

    $this->actingAs($siswa)->post('/keluhan', payloadKeluhan(['prioritas' => 'Tinggi']));

    $this->assertDatabaseHas('tiket_keluhan', [
        'pelapor_id' => $siswa->id,
        'prioritas'  => 'Tinggi',
    ]);
});

// ─────────────────────────────────────────────────────────────
// ISOLATION: Tiket satu siswa tidak terekspos ke siswa lain
// ─────────────────────────────────────────────────────────────

test('Siswa B tidak bisa melihat tiket milik Siswa A di /keluhan/saya', function () {
    $siswaA = buatUserDenganRole('Siswa');
    $siswaB = buatUserDenganRole('Siswa');

    TiketKeluhan::factory()->create([
        'pelapor_id' => $siswaA->id,
        'subjek'     => 'Keluhan rahasia Siswa A',
    ]);

    $response = $this->actingAs($siswaB)->get('/keluhan/saya');

    $response->assertViewHas('tiketKeluhans', fn ($list) => $list->isEmpty());
    $response->assertDontSee('Keluhan rahasia Siswa A');
});
