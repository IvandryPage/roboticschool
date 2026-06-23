<?php

use App\Models\AsetRobotik;
use App\Models\ItemKitRobotik;
use App\Models\PeminjamanItemAset;
use App\Models\User;
use Illuminate\Support\Str;

test('guests are redirected to the login page from peminjaman', function () {
    $response = $this->get(route('peminjaman.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can access the peminjaman page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('peminjaman.index'));
    $response->assertOk();
});

test('user can submit a borrowing request when item kit is available', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $asset = AsetRobotik::factory()->create([
        'nama_kit' => 'Test Kit',
    ]);

    $item = ItemKitRobotik::factory()->create([
        'aset_id' => $asset->id,
        'status_kondisi' => 'Bagus',
    ]);

    $response = $this->post(route('peminjaman.store'), [
        'aset_id' => $asset->id,
        'tanggal_jatuh_tempo' => now()->addDays(7)->format('Y-m-d'),
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('peminjaman_item_aset', [
        'user_id' => $user->id,
        'item_kit_id' => $item->id,
        'status' => 'Diajukan',
    ]);

    // Available stock should remain 1 since the status is Diajukan (stock remains the same)
    expect($asset->fresh()->available_stock)->toBe(1);
});

test('user cannot borrow when no item kit is available', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $asset = AsetRobotik::factory()->create([
        'nama_kit' => 'Test Kit Empty',
    ]);

    // No item kit is created for this asset, so stock is 0

    $response = $this->post(route('peminjaman.store'), [
        'aset_id' => $asset->id,
        'tanggal_jatuh_tempo' => now()->addDays(7)->format('Y-m-d'),
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['aset_id']);

    $this->assertDatabaseCount('peminjaman_item_aset', 0);
});

test('admin can approve borrowing request', function () {
    $role = \App\Models\Role::firstOrCreate(['nama_role' => 'Admin Akademik']);
    $admin = User::factory()->create(['role_id' => $role->id]);
    $user = User::factory()->create();
    $asset = AsetRobotik::factory()->create();
    $item = ItemKitRobotik::factory()->create([
        'aset_id' => $asset->id,
        'status_kondisi' => 'Bagus',
    ]);

    $peminjaman = PeminjamanItemAset::create([
        'id' => (string) Str::uuid(),
        'user_id' => $user->id,
        'item_kit_id' => $item->id,
        'status' => 'Diajukan',
        'kondisi_awal' => 'Baik',
        'tanggal_jatuh_tempo' => now()->addDays(7),
    ]);

    $response = $this->actingAs($admin)->post(route('admin.peminjaman.approve', $peminjaman));
    $response->assertRedirect();

    $peminjaman = $peminjaman->fresh();
    expect($peminjaman->status)->toBe('Dipinjam');
    expect($peminjaman->diverifikasi_oleh)->toBe($admin->id);
    expect($peminjaman->tanggal_pinjam)->not->toBeNull();
    expect($asset->fresh()->available_stock)->toBe(0); // approved reduces stock
});

test('admin can reject borrowing request', function () {
    $role = \App\Models\Role::firstOrCreate(['nama_role' => 'Admin Akademik']);
    $admin = User::factory()->create(['role_id' => $role->id]);
    $user = User::factory()->create();
    $asset = AsetRobotik::factory()->create();
    $item = ItemKitRobotik::factory()->create([
        'aset_id' => $asset->id,
        'status_kondisi' => 'Bagus',
    ]);

    $peminjaman = PeminjamanItemAset::create([
        'id' => (string) Str::uuid(),
        'user_id' => $user->id,
        'item_kit_id' => $item->id,
        'status' => 'Diajukan',
        'kondisi_awal' => 'Baik',
        'tanggal_jatuh_tempo' => now()->addDays(7),
    ]);

    $response = $this->actingAs($admin)->post(route('admin.peminjaman.reject', $peminjaman));
    $response->assertRedirect();

    $peminjaman = $peminjaman->fresh();
    expect($peminjaman->status)->toBe('Ditolak');
    expect($peminjaman->diverifikasi_oleh)->toBe($admin->id);
    expect($asset->fresh()->available_stock)->toBe(1); // rejected keeps stock
});

test('admin can confirm return of kit in good condition', function () {
    $role = \App\Models\Role::firstOrCreate(['nama_role' => 'Admin Akademik']);
    $admin = User::factory()->create(['role_id' => $role->id]);
    $user = User::factory()->create();
    $asset = AsetRobotik::factory()->create();
    $item = ItemKitRobotik::factory()->create([
        'aset_id' => $asset->id,
        'status_kondisi' => 'Bagus',
    ]);

    $peminjaman = PeminjamanItemAset::create([
        'id' => (string) Str::uuid(),
        'user_id' => $user->id,
        'item_kit_id' => $item->id,
        'status' => 'Dipinjam',
        'kondisi_awal' => 'Baik',
        'tanggal_pinjam' => now()->subDays(2),
        'tanggal_jatuh_tempo' => now()->addDays(5),
    ]);

    $response = $this->actingAs($admin)->post(route('admin.peminjaman.return', $peminjaman), [
        'kondisi_akhir' => 'Baik',
    ]);
    $response->assertRedirect();

    $peminjaman = $peminjaman->fresh();
    expect($peminjaman->status)->toBe('Dikembalikan');
    expect($peminjaman->tanggal_kembali)->not->toBeNull();
    expect($peminjaman->kondisi_akhir)->toBe('Baik');
    expect($item->fresh()->status_kondisi)->toBe('Bagus');
    expect($asset->fresh()->available_stock)->toBe(1); // returned good restores stock
});

test('admin can confirm return of kit in broken condition', function () {
    $role = \App\Models\Role::firstOrCreate(['nama_role' => 'Admin Akademik']);
    $admin = User::factory()->create(['role_id' => $role->id]);
    $user = User::factory()->create();
    $asset = AsetRobotik::factory()->create();
    $item = ItemKitRobotik::factory()->create([
        'aset_id' => $asset->id,
        'status_kondisi' => 'Bagus',
    ]);

    $peminjaman = PeminjamanItemAset::create([
        'id' => (string) Str::uuid(),
        'user_id' => $user->id,
        'item_kit_id' => $item->id,
        'status' => 'Dipinjam',
        'kondisi_awal' => 'Baik',
        'tanggal_pinjam' => now()->subDays(2),
        'tanggal_jatuh_tempo' => now()->addDays(5),
    ]);

    $response = $this->actingAs($admin)->post(route('admin.peminjaman.return', $peminjaman), [
        'kondisi_akhir' => 'Rusak',
    ]);
    $response->assertRedirect();

    $peminjaman = $peminjaman->fresh();
    expect($peminjaman->status)->toBe('Dikembalikan');
    expect($peminjaman->tanggal_kembali)->not->toBeNull();
    expect($peminjaman->kondisi_akhir)->toBe('Rusak');
    expect($item->fresh()->status_kondisi)->toBe('Rusak');
    expect($asset->fresh()->available_stock)->toBe(0); // returned broken leaves stock at 0
});

test('admin user accessing peminjaman is redirected to admin panel', function () {
    $role = \App\Models\Role::firstOrCreate(['nama_role' => 'Admin Akademik']);
    $admin = User::factory()->create(['role_id' => $role->id]);
    $this->actingAs($admin);

    $response = $this->get(route('peminjaman.index'));
    $response->assertRedirect(route('admin.aset.index'));
});

test('admin user accessing dashboard is redirected to admin panel', function () {
    $role = \App\Models\Role::firstOrCreate(['nama_role' => 'Admin Akademik']);
    $admin = User::factory()->create(['role_id' => $role->id]);
    $this->actingAs($admin);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect('/admin');
});
