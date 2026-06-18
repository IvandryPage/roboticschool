<?php

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;

// RefreshDatabase digunakan agar database testing selalu bersih sebelum test dijalankan
uses(RefreshDatabase::class);

test('sistem dapat mencatat log ketika user berhasil login', function () {
    // 1. Persiapan: Buat 1 user palsu (dummy)
    $user = User::factory()->create();

    // 2. Aksi: Simulasikan seolah-olah user tersebut baru saja Login
    event(new Login('web', $user, false));

    // 3. Pengecekan: Cek apakah data benar-benar tersimpan di tabel audit_logs
    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $user->id,
        'aksi' => 'Login',
        'entity_type' => get_class($user),
    ]);
});

test('sistem dapat mencatat log ketika ada data yang dihapus', function () {
    // 1. Persiapan: Buat user dan paksa login
    $user = User::factory()->create();
    $this->actingAs($user);

    // 2. Aksi: Hapus akun user tersebut
    $user->delete();

    // 3. Pengecekan: Cek apakah aksi 'Delete Data' terekam di audit_logs
    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $user->id,
        'aksi' => 'Delete Data',
        'entity_type' => get_class($user),
    ]);
});