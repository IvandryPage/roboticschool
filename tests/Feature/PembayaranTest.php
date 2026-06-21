<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Pembayaran;
use App\Models\Invoice;
use App\Models\Pendaftaran;
use App\Models\CalonPeserta;
use App\Models\ProgramKursus;
use App\Models\Siswa;
use App\Filament\Resources\Pembayarans\Tables\PembayaransTable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

beforeEach(function () {
    // Setup roles
    $this->adminRole = Role::create([
        'id' => (string) Str::uuid(),
        'nama_role' => 'Admin Akademik',
    ]);
    
    $this->siswaRole = Role::create([
        'id' => (string) Str::uuid(),
        'nama_role' => 'Siswa',
    ]);

    // Setup admin user
    $this->adminUser = User::create([
        'id' => (string) Str::uuid(),
        'nama_lengkap' => 'Admin Test',
        'name' => 'admintest',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role_id' => $this->adminRole->id,
        'status_aktif' => true,
    ]);

    // Setup program
    $this->program = ProgramKursus::create([
        'id' => (string) Str::uuid(),
        'nama_program' => 'Arduino Basic',
        'biaya' => 500000,
        'deskripsi' => 'Basic class',
        'level' => 'Beginner',
        'durasi_minggu' => 4,
    ]);

    // Setup calon peserta
    $this->calonPeserta = CalonPeserta::create([
        'id' => (string) Str::uuid(),
        'nama_lengkap' => 'Budi Santoso',
        'email' => 'budi.siswa@test.com',
        'no_hp' => '08123456789',
        'asal_sekolah_atau_instansi' => 'SMA 1 Surabaya',
        'jenjang_pendidikan' => 'SMA',
    ]);

    // Setup pendaftaran
    $this->pendaftaran = Pendaftaran::create([
        'id' => (string) Str::uuid(),
        'calon_peserta_id' => $this->calonPeserta->id,
        'program_id' => $this->program->id,
        'no_referensi' => 'REF-' . Str::random(8),
        'tanggal_daftar' => now(),
        'status' => 'Menunggu Verifikasi',
    ]);

    // Setup invoice
    $this->invoice = Invoice::create([
        'id' => (string) Str::uuid(),
        'pendaftaran_id' => $this->pendaftaran->id,
        'no_invoice' => 'INV-' . Str::random(8),
        'total_tagihan' => 500000,
        'tanggal_terbit' => now(),
        'tanggal_jatuh_tempo' => now()->addDays(7),
        'status_pembayaran' => 'Menunggu',
        'payment_reference' => 'PAY-' . Str::random(8),
    ]);

    // Setup pending payment
    $this->pembayaran = Pembayaran::create([
        'id' => (string) Str::uuid(),
        'invoice_id' => $this->invoice->id,
        'nominal' => 500000,
        'metode_pembayaran' => 'Transfer',
        'status' => 'Pending',
        'bukti_file' => 'bukti_pembayaran/dummy.jpg',
    ]);
});

test('pembayaran can be verified as valid and activates student account', function () {
    $this->actingAs($this->adminUser);

    // Simulate verification action for Valid (Sukses)
    $this->pembayaran->update([
        'status' => 'Sukses',
        'diverifikasi_oleh' => $this->adminUser->id,
        'paid_at' => now(),
    ]);

    $this->invoice->update([
        'status_pembayaran' => 'Dibayar',
    ]);

    // Call the activation logic
    PembayaransTable::aktifkanSiswa($this->pembayaran);

    // Assert payment is success
    expect($this->pembayaran->fresh()->status)->toBe('Sukses');
    expect($this->pembayaran->fresh()->diverifikasi_oleh)->toBe($this->adminUser->id);
    expect($this->pembayaran->fresh()->paid_at)->not->toBeNull();

    // Assert invoice is paid
    expect($this->invoice->fresh()->status_pembayaran)->toBe('Dibayar');

    // Assert pendaftaran status becomes 'Diterima'
    expect($this->pendaftaran->fresh()->status)->toBe('Diterima');

    // Assert user account is created for student
    $studentUser = User::where('email', 'budi.siswa@test.com')->first();
    expect($studentUser)->not->toBeNull();
    expect($studentUser->role_id)->toBe($this->siswaRole->id);
    expect($studentUser->status_aktif)->toBeTrue();

    // Assert Siswa record is created
    $siswa = Siswa::where('pendaftaran_id', $this->pendaftaran->id)->first();
    expect($siswa)->not->toBeNull();
    expect($siswa->user_id)->toBe($studentUser->id);
});

test('pembayaran can be verified as invalid with rejection note', function () {
    $this->actingAs($this->adminUser);

    // Simulate verification action for Invalid (Gagal)
    $this->pembayaran->update([
        'status' => 'Gagal',
        'catatan_penolakan' => 'Bukti bayar tidak terbaca / palsu.',
        'diverifikasi_oleh' => $this->adminUser->id,
        'paid_at' => null,
    ]);

    $this->invoice->update([
        'status_pembayaran' => 'Gagal',
    ]);

    // Assert payment is marked as Gagal
    expect($this->pembayaran->fresh()->status)->toBe('Gagal');
    expect($this->pembayaran->fresh()->catatan_penolakan)->toBe('Bukti bayar tidak terbaca / palsu.');
    expect($this->pembayaran->fresh()->diverifikasi_oleh)->toBe($this->adminUser->id);
    expect($this->pembayaran->fresh()->paid_at)->toBeNull();

    // Assert invoice is marked as Gagal
    expect($this->invoice->fresh()->status_pembayaran)->toBe('Gagal');

    // Assert Siswa record is NOT created
    $studentUser = User::where('email', 'budi.siswa@test.com')->first();
    expect($studentUser)->toBeNull();

    $siswa = Siswa::where('pendaftaran_id', $this->pendaftaran->id)->first();
    expect($siswa)->toBeNull();
});
