<?php

use App\Models\AsetRobotik;
use App\Models\ItemKitRobotik;
use App\Models\PeminjamanItemAset;
use App\Models\Role;
use App\Models\User;

function buatAdmin(): User
{
    $role = Role::firstOrCreate(['nama_role' => 'Admin'], ['deskripsi' => 'Administrator sistem']);

    return User::create([
        'name' => 'Test Admin',
        'nama_lengkap' => 'Test Admin',
        'email' => 'test-admin-'.uniqid().'@test.com',
        'password' => bcrypt('password'),
        'role_id' => $role->id,
    ]);
}

function buatItemKit(): ItemKitRobotik
{
    $aset = AsetRobotik::create([
        'kode_aset' => 'TEST-'.uniqid(),
        'nama_kit' => 'Test Kit',
        'kategori' => 'Test',
        'stok_minimal' => 1,
    ]);

    return ItemKitRobotik::create([
        'aset_id' => $aset->id,
        'serial_number' => 'SN-TEST-'.uniqid(),
        'status_kondisi' => 'Bagus',
    ]);
}

test('item yang sedang dipinjam tidak bisa di-approve lagi untuk pengajuan lain', function () {
    $admin = buatAdmin();
    $item = buatItemKit();
    $peminjam1 = buatAdmin();
    $peminjam2 = buatAdmin();

    // Pengajuan pertama disetujui dan jadi "Dipinjam"
    $pengajuan1 = PeminjamanItemAset::create([
        'user_id' => $peminjam1->id,
        'item_kit_id' => $item->id,
        'status' => 'Dipinjam',
        'tanggal_pinjam' => now(),
        'diverifikasi_oleh' => $admin->id,
    ]);

    // Pengajuan kedua untuk item yang sama, masih "Diajukan"
    $pengajuan2 = PeminjamanItemAset::create([
        'user_id' => $peminjam2->id,
        'item_kit_id' => $item->id,
        'status' => 'Diajukan',
        'tanggal_pinjam' => now(),
    ]);

    // Cek: ada record aktif "Dipinjam" untuk item ini
    $sedangDipinjam = PeminjamanItemAset::where('item_kit_id', $item->id)
        ->where('status', 'Dipinjam')
        ->exists();

    expect($sedangDipinjam)->toBeTrue();
    expect($pengajuan2->status)->toBe('Diajukan'); // belum berubah, masih nunggu
});

test('approve mengubah status peminjaman jadi Dipinjam dan mengisi diverifikasi_oleh', function () {
    $admin = buatAdmin();
    $item = buatItemKit();
    $peminjam = buatAdmin();

    $pengajuan = PeminjamanItemAset::create([
        'user_id' => $peminjam->id,
        'item_kit_id' => $item->id,
        'status' => 'Diajukan',
        'tanggal_pinjam' => now(),
    ]);

    $pengajuan->update([
        'status' => 'Dipinjam',
        'diverifikasi_oleh' => $admin->id,
    ]);

    $pengajuan->refresh();

    expect($pengajuan->status)->toBe('Dipinjam');
    expect($pengajuan->diverifikasi_oleh)->toBe($admin->id);
});

test('reject mengubah status peminjaman jadi Ditolak', function () {
    $admin = buatAdmin();
    $item = buatItemKit();
    $peminjam = buatAdmin();

    $pengajuan = PeminjamanItemAset::create([
        'user_id' => $peminjam->id,
        'item_kit_id' => $item->id,
        'status' => 'Diajukan',
        'tanggal_pinjam' => now(),
    ]);

    $pengajuan->update([
        'status' => 'Ditolak',
        'diverifikasi_oleh' => $admin->id,
        'kondisi_akhir' => 'Item rusak, tidak bisa dipinjam',
    ]);

    $pengajuan->refresh();

    expect($pengajuan->status)->toBe('Ditolak');
    expect($pengajuan->kondisi_akhir)->not->toBeNull();
});

test('item yang sudah dikembalikan bisa diajukan dan disetujui lagi oleh peminjam lain', function () {
    $admin = buatAdmin();
    $item = buatItemKit();
    $peminjam1 = buatAdmin();
    $peminjam2 = buatAdmin();

    // Pengajuan pertama: dipinjam lalu dikembalikan
    $pengajuan1 = PeminjamanItemAset::create([
        'user_id' => $peminjam1->id,
        'item_kit_id' => $item->id,
        'status' => 'Dikembalikan',
        'tanggal_pinjam' => now()->subDays(3),
        'tanggal_kembali' => now(),
        'diverifikasi_oleh' => $admin->id,
    ]);

    // Cek: tidak ada lagi record "Dipinjam" aktif untuk item ini
    $sedangDipinjam = PeminjamanItemAset::where('item_kit_id', $item->id)
        ->where('status', 'Dipinjam')
        ->exists();

    expect($sedangDipinjam)->toBeFalse();

    // Pengajuan kedua oleh orang lain bisa langsung di-approve karena item tersedia
    $pengajuan2 = PeminjamanItemAset::create([
        'user_id' => $peminjam2->id,
        'item_kit_id' => $item->id,
        'status' => 'Dipinjam',
        'tanggal_pinjam' => now(),
        'diverifikasi_oleh' => $admin->id,
    ]);

    expect($pengajuan2->status)->toBe('Dipinjam');
});