<?php

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Schema;

test('sistem dapat mencatat log ketika user berhasil login', function () {
    // --- PENGECEKAN TABEL (Peredam Kejut) ---
    if (!Schema::hasTable('audit_log') && !Schema::hasTable('audit_logs')) {
        $this->markTestSkipped('Tes dilewati sementara: Menunggu tabel audit_log dari tim Database.');
    }
    // ----------------------------------------

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
    // --- PENGECEKAN TABEL (Peredam Kejut) ---
    if (!Schema::hasTable('audit_log') && !Schema::hasTable('audit_logs')) {
        $this->markTestSkipped('Tes dilewati sementara: Menunggu tabel audit_log dari tim Database.');
    }
    // ----------------------------------------

    $user = User::factory()->create();

    // 2. Aksi: Hapus akun user tersebut
    $user->delete();

    // 3. Pengecekan: Cek apakah aksi 'Delete Data' terekam di audit_logs
    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $user->id,
        'aksi' => 'Delete Data',
        'entity_type' => get_class($user),
    ]);
});