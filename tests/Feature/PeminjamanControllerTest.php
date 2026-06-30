<?php

/**
 * Test: PeminjamanController (siswa pinjam aset)
 *
 * Logic yang ditest dari controller asli:
 * 1. GET /peminjaman — tampilkan list aktif + histori + stok tersedia
 * 2. POST — validasi field wajib + tanggal after:today
 * 3. POST — guard: tidak ada item tersedia → error
 * 4. POST valid → PeminjamanItemAset dibuat status 'Diajukan'
 * 5. Admin yang akses /peminjaman → redirect /admin/aset
 */

use App\Models\AsetRobotik;
use App\Models\ItemKitRobotik;
use App\Models\PeminjamanItemAset;
use App\Models\Role;
use App\Models\User;

function buatSiswaUser(): User
{
    $role = Role::firstOrCreate(['nama_role' => 'Siswa']);
    return User::factory()->create(['role_id' => $role->id]);
}

function buatAdminUser(): User
{
    $role = Role::firstOrCreate(['nama_role' => 'Admin Akademik']);
    return User::factory()->create(['role_id' => $role->id]);
}

function buatAsetDenganItemBagus(): array
{
    $aset = AsetRobotik::factory()->create();
    $item = ItemKitRobotik::factory()->create([
        'aset_id'        => $aset->id,
        'status_kondisi' => 'Bagus',
    ]);
    return [$aset, $item];
}

// ─────────────────────────────────────────────────────────────
// GET — TAMPILAN LIST
// ─────────────────────────────────────────────────────────────

test('GET /peminjaman untuk siswa menampilkan view dengan data yang benar', function () {
    $siswa = buatSiswaUser();

    $response = $this->actingAs($siswa)->get('/peminjaman');

    $response->assertOk();
    $response->assertViewIs('peminjaman.index');
    $response->assertViewHas('activeBorrowings');
    $response->assertViewHas('historyBorrowings');
    $response->assertViewHas('assets');
});

test('GET /peminjaman untuk admin → redirect ke /admin/aset', function () {
    $admin = buatAdminUser();

    $response = $this->actingAs($admin)->get('/peminjaman');

    $response->assertRedirect('/admin/aset');
});

test('GET /peminjaman tanpa login → redirect ke /login', function () {
    $response = $this->get('/peminjaman');

    $response->assertRedirect('/login');
});

// ─────────────────────────────────────────────────────────────
// POST — VALIDASI
// ─────────────────────────────────────────────────────────────

test('POST tanpa aset_id → validasi error', function () {
    $siswa = buatSiswaUser();

    $response = $this->actingAs($siswa)->post('/peminjaman', [
        'aset_id'              => '',
        'tanggal_jatuh_tempo'  => now()->addDays(7)->format('Y-m-d'),
    ]);

    $response->assertSessionHasErrors('aset_id');
});

test('POST dengan tanggal_jatuh_tempo hari ini (bukan after:today) → error', function () {
    $siswa = buatSiswaUser();
    [$aset] = buatAsetDenganItemBagus();

    $response = $this->actingAs($siswa)->post('/peminjaman', [
        'aset_id'              => $aset->id,
        'tanggal_jatuh_tempo'  => now()->format('Y-m-d'), // hari ini, bukan besok
    ]);

    $response->assertSessionHasErrors('tanggal_jatuh_tempo');
    $this->assertDatabaseMissing('peminjaman_item_aset', ['user_id' => $siswa->id]);
});

test('POST dengan tanggal_jatuh_tempo kemarin → error', function () {
    $siswa = buatSiswaUser();
    [$aset] = buatAsetDenganItemBagus();

    $response = $this->actingAs($siswa)->post('/peminjaman', [
        'aset_id'              => $aset->id,
        'tanggal_jatuh_tempo'  => now()->subDay()->format('Y-m-d'),
    ]);

    $response->assertSessionHasErrors('tanggal_jatuh_tempo');
});

test('POST dengan aset_id yang tidak ada di DB → validasi error (exists:aset_robotik,id)', function () {
    $siswa = buatSiswaUser();

    $response = $this->actingAs($siswa)->post('/peminjaman', [
        'aset_id'              => (string) \Illuminate\Support\Str::uuid(),
        'tanggal_jatuh_tempo'  => now()->addDays(5)->format('Y-m-d'),
    ]);

    $response->assertSessionHasErrors('aset_id');
});

// ─────────────────────────────────────────────────────────────
// POST — GUARD: ITEM TIDAK TERSEDIA
// ─────────────────────────────────────────────────────────────

test('POST saat semua item dalam kondisi Rusak → error "tidak tersedia"', function () {
    $siswa = buatSiswaUser();
    $aset  = AsetRobotik::factory()->create();
    ItemKitRobotik::factory()->create([
        'aset_id'        => $aset->id,
        'status_kondisi' => 'Rusak', // tidak bisa dipinjam
    ]);

    $response = $this->actingAs($siswa)->post('/peminjaman', [
        'aset_id'              => $aset->id,
        'tanggal_jatuh_tempo'  => now()->addDays(5)->format('Y-m-d'),
    ]);

    $response->assertSessionHasErrors('aset_id');
    $this->assertDatabaseMissing('peminjaman_item_aset', ['user_id' => $siswa->id]);
});

test('POST saat semua item bagus sudah berstatus Dipinjam → error "tidak tersedia"', function () {
    $siswa = buatSiswaUser();
    [$aset, $item] = buatAsetDenganItemBagus();

    // Item sudah dipinjam orang lain
    PeminjamanItemAset::factory()->create([
        'item_kit_id' => $item->id,
        'status'      => 'Dipinjam',
    ]);

    $response = $this->actingAs($siswa)->post('/peminjaman', [
        'aset_id'              => $aset->id,
        'tanggal_jatuh_tempo'  => now()->addDays(5)->format('Y-m-d'),
    ]);

    $response->assertSessionHasErrors('aset_id');
});

// ─────────────────────────────────────────────────────────────
// POST — HAPPY PATH
// ─────────────────────────────────────────────────────────────

test('POST valid → PeminjamanItemAset dibuat dengan status Diajukan, terhubung ke item dan user', function () {
    $siswa = buatSiswaUser();
    [$aset, $item] = buatAsetDenganItemBagus();

    $tanggalJatuhTempo = now()->addDays(7)->format('Y-m-d');

    $response = $this->actingAs($siswa)->post('/peminjaman', [
        'aset_id'              => $aset->id,
        'tanggal_jatuh_tempo'  => $tanggalJatuhTempo,
    ]);

    // Redirect back dengan flash success
    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Record tersimpan di DB
    $this->assertDatabaseHas('peminjaman_item_aset', [
        'user_id'     => $siswa->id,
        'item_kit_id' => $item->id,
        'status'      => 'Diajukan',
    ]);
});

test('POST valid → tanggal_pinjam di DB adalah NULL (menunggu verifikasi admin)', function () {
    $siswa = buatSiswaUser();
    [$aset] = buatAsetDenganItemBagus();

    $this->actingAs($siswa)->post('/peminjaman', [
        'aset_id'              => $aset->id,
        'tanggal_jatuh_tempo'  => now()->addDays(7)->format('Y-m-d'),
    ]);

    $peminjaman = PeminjamanItemAset::where('user_id', $siswa->id)->first();
    expect($peminjaman?->tanggal_pinjam)->toBeNull();
    expect($peminjaman?->kondisi_awal)->toBe('Baik');
    expect($peminjaman?->diverifikasi_oleh)->toBeNull();
});

// ─────────────────────────────────────────────────────────────
// ADMIN POST — REDIRECT
// ─────────────────────────────────────────────────────────────

test('POST /peminjaman untuk admin → redirect ke /admin/aset, tidak buat record', function () {
    $admin = buatAdminUser();
    [$aset] = buatAsetDenganItemBagus();

    $response = $this->actingAs($admin)->post('/peminjaman', [
        'aset_id'              => $aset->id,
        'tanggal_jatuh_tempo'  => now()->addDays(5)->format('Y-m-d'),
    ]);

    $response->assertRedirect('/admin/aset');
    $this->assertDatabaseMissing('peminjaman_item_aset', ['user_id' => $admin->id]);
});
