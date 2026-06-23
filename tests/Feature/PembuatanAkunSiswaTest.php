<?php

namespace Tests\Feature;

use App\Models\CalonPeserta;
use App\Models\Pendaftaran;
use App\Models\ProgramKursus;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * PBI-074 — Pengujian pembuatan akun siswa & pengelolaan siswa aktif
 *
 * Mencakup:
 * - PBI-067 : Buat akun siswa dari pendaftaran disetujui
 * - PBI-068 : Halaman daftar siswa
 * - PBI-069 : Filter & pencarian siswa
 * - PBI-070 : Edit profil siswa oleh admin
 * - PBI-071 : Nonaktifkan / aktifkan kembali akun siswa
 * - PBI-072 : Profil siswa setelah login (akses sebagai siswa)
 *
 * Jalankan: php artisan test --filter=PembuatanAkunSiswaTest
 */
class PembuatanAkunSiswaTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private ProgramKursus $program;

    protected function setUp(): void
    {
        parent::setUp();

        $role = \App\Models\Role::firstOrCreate(['nama_role' => 'Admin Akademik']);

        $this->admin = User::factory()->create([
            'name'    => 'Admin Test',
            'email'   => 'admin@test.com',
            'role_id' => $role->id,
        ]);

        $this->program = ProgramKursus::factory()->create([
            'nama_program' => 'Web Development',
        ]);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function buatPendaftaranDisetujui(): Pendaftaran
    {
        $unique = uniqid();
        $cp = CalonPeserta::factory()->create([
            'nama_lengkap'              => 'Siti Rahayu ' . $unique,
            'email'                     => 'siti_' . $unique . '@example.com',
            'no_hp'                     => '08129876543',
            'asal_sekolah_atau_instansi'=> 'SMA Negeri 1',
        ]);

        return Pendaftaran::factory()->create([
            'calon_peserta_id' => $cp->id,
            'program_id'       => $this->program->id,
            'status'           => 'disetujui',
        ]);
    }

    private function buatSiswaAktif(): Siswa
    {
        $pendaftaran = $this->buatPendaftaranDisetujui();
        $unique = uniqid();
        $user = User::factory()->create([
            'name'  => 'sitiuser_' . $unique,
            'email' => $pendaftaran->calonPeserta->email,
        ]);

        return Siswa::factory()->create([
            'user_id'           => $user->id,
            'pendaftaran_id'    => $pendaftaran->id,
            'nama_lengkap'      => $pendaftaran->calonPeserta->nama_lengkap,
            'email'             => $pendaftaran->calonPeserta->email,
            'no_hp'             => $pendaftaran->calonPeserta->no_hp,
            'asal_sekolah'      => $pendaftaran->calonPeserta->asal_sekolah_atau_instansi,
            'status_akun'       => 'aktif',
            'tanggal_bergabung' => now(),
        ]);
    }

    // =========================================================================
    // GROUP 1: PBI-067 — Form & buat akun siswa
    // =========================================================================

    /** @test */
    public function test_admin_dapat_mengakses_form_buat_akun_siswa()
    {
        $pendaftaran = $this->buatPendaftaranDisetujui();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.siswa.create-akun', $pendaftaran->id));

        $response->assertOk();
        $response->assertViewIs('admin.siswa.create-akun');
        $response->assertViewHas('pendaftaran');
    }

    /** @test */
    public function test_admin_tidak_bisa_buat_akun_untuk_pendaftaran_yang_belum_disetujui()
    {
        $cp = CalonPeserta::factory()->create();
        $pendaftaran = Pendaftaran::factory()->create([
            'calon_peserta_id' => $cp->id,
            'program_id'       => $this->program->id,
            'status'           => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.siswa.create-akun', $pendaftaran->id));

        $response->assertForbidden();
    }

    /** @test */
    public function test_admin_dapat_membuat_akun_siswa_baru()
    {
        $pendaftaran = $this->buatPendaftaranDisetujui();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.siswa.store-akun', $pendaftaran->id), [
                'username'              => 'sitirahayuuser',
                'password'              => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]);

        $response->assertRedirect(route('admin.siswa.index'));
        $response->assertSessionHas('success');

        // User login terbuat
        $this->assertDatabaseHas('users', [
            'name'  => 'sitirahayuuser',
            'email' => $pendaftaran->calonPeserta->email,
        ]);

        // Record siswa terbuat & terhubung
        $this->assertDatabaseHas('siswa', [
            'pendaftaran_id' => $pendaftaran->id,
        ]);
        $this->assertDatabaseHas('users', [
            'email'          => $pendaftaran->calonPeserta->email,
            'nama_lengkap'   => $pendaftaran->calonPeserta->nama_lengkap,
            'status_aktif'   => true,
        ]);
    }

    /** @test */
    public function test_pembuatan_akun_gagal_jika_username_duplikat()
    {
        User::factory()->create(['name' => 'duplikatuser']);
        $pendaftaran = $this->buatPendaftaranDisetujui();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.siswa.store-akun', $pendaftaran->id), [
                'username'              => 'duplikatuser',
                'password'              => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]);

        $response->assertSessionHasErrors('username');
        $this->assertDatabaseMissing('siswa', ['pendaftaran_id' => $pendaftaran->id]);
    }

    /** @test */
    public function test_pembuatan_akun_gagal_jika_password_terlalu_pendek()
    {
        $pendaftaran = $this->buatPendaftaranDisetujui();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.siswa.store-akun', $pendaftaran->id), [
                'username'              => 'sitiuser',
                'password'              => '123',
                'password_confirmation' => '123',
            ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function test_pembuatan_akun_gagal_jika_konfirmasi_password_tidak_cocok()
    {
        $pendaftaran = $this->buatPendaftaranDisetujui();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.siswa.store-akun', $pendaftaran->id), [
                'username'              => 'sitiuser',
                'password'              => 'Password123!',
                'password_confirmation' => 'BerbedaSekali!',
            ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function test_akun_tidak_bisa_dibuat_dua_kali_untuk_pendaftaran_yang_sama()
    {
        $siswa       = $this->buatSiswaAktif();
        $pendaftaran = $siswa->pendaftaran;

        $response = $this->actingAs($this->admin)
            ->post(route('admin.siswa.store-akun', $pendaftaran->id), [
                'username'              => 'akun_baru_lagi',
                'password'              => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]);

        // Harus redirect ke detail siswa yang sudah ada
        $response->assertRedirect(route('admin.siswa.show', $siswa->id));
        $response->assertSessionHas('info');
    }

    // =========================================================================
    // GROUP 2: PBI-068 & PBI-069 — Daftar & filter siswa
    // =========================================================================

    /** @test */
    public function test_admin_dapat_melihat_daftar_siswa()
    {
        $this->buatSiswaAktif();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.siswa.index'));

        $response->assertOk();
        $response->assertViewIs('admin.siswa.index');
        $response->assertViewHas(['siswaList', 'stats', 'programList']);
    }

    /** @test */
    public function test_admin_dapat_mencari_siswa_berdasarkan_nama()
    {
        $this->buatSiswaAktif(); // nama: Siti Rahayu

        $response = $this->actingAs($this->admin)
            ->get(route('admin.siswa.index', ['search' => 'Siti']));

        $response->assertOk();
        $response->assertViewHas('siswaList', fn($list) => $list->isNotEmpty());
    }

    /** @test */
    public function test_pencarian_dengan_nama_tidak_ada_mengembalikan_hasil_kosong()
    {
        $this->buatSiswaAktif();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.siswa.index', ['search' => 'NamaTidakAda12345']));

        $response->assertOk();
        $response->assertViewHas('siswaList', fn($list) => $list->isEmpty());
    }

    /** @test */
    public function test_admin_dapat_filter_siswa_berdasarkan_status()
    {
        $siswaAktif = $this->buatSiswaAktif();

        // Buat siswa nonaktif
        $siswaAktif2 = $this->buatSiswaAktif();
        $siswaAktif2->update(['status_akun' => 'nonaktif']);

        $responseAktif = $this->actingAs($this->admin)
            ->get(route('admin.siswa.index', ['status' => 'aktif']));

        $responseAktif->assertViewHas('siswaList', fn($list) =>
            $list->every(fn($s) => $s->status_akun === 'aktif')
        );

        $responseNonaktif = $this->actingAs($this->admin)
            ->get(route('admin.siswa.index', ['status' => 'nonaktif']));

        $responseNonaktif->assertViewHas('siswaList', fn($list) =>
            $list->every(fn($s) => $s->status_akun === 'nonaktif')
        );
    }

    /** @test */
    public function test_statistik_pada_daftar_siswa_akurat()
    {
        $s1 = $this->buatSiswaAktif();
        $s2 = $this->buatSiswaAktif();
        $s2->update(['status_akun' => 'nonaktif']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.siswa.index'));

        $response->assertViewHas('stats', fn($stats) =>
            $stats['total'] === 2 &&
            $stats['aktif'] === 1 &&
            $stats['nonaktif'] === 1
        );
    }

    // =========================================================================
    // GROUP 3: PBI-070 — Edit profil siswa oleh admin
    // =========================================================================

    /** @test */
    public function test_admin_dapat_melihat_form_edit_siswa()
    {
        $siswa = $this->buatSiswaAktif();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.siswa.edit', $siswa->id));

        $response->assertOk();
        $response->assertViewIs('admin.siswa.edit');
        $response->assertViewHas('siswa');
    }

    /** @test */
    public function test_admin_dapat_memperbarui_profil_siswa()
    {
        $siswa = $this->buatSiswaAktif();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.siswa.update', $siswa->id), [
                'nama_lengkap' => 'Siti Rahayu Diperbarui',
                'email'        => $siswa->email,
                'no_hp'        => '0812000000',
                'asal_sekolah' => 'Universitas Gajah Mada',
                'status_akun'  => 'aktif',
            ]);

        $response->assertRedirect(route('admin.siswa.show', $siswa->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'id'           => $siswa->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id'           => $siswa->user_id,
            'nama_lengkap' => 'Siti Rahayu Diperbarui',
            'no_hp'        => '0812000000',
        ]);
        $this->assertDatabaseHas('calon_peserta', [
            'id'                         => $siswa->pendaftaran->calon_peserta_id,
            'asal_sekolah_atau_instansi' => 'Universitas Gajah Mada',
        ]);
    }

    /** @test */
    public function test_admin_dapat_mengubah_status_akun_dari_form_edit()
    {
        $siswa = $this->buatSiswaAktif();

        $this->actingAs($this->admin)
            ->put(route('admin.siswa.update', $siswa->id), [
                'nama_lengkap' => $siswa->nama_lengkap,
                'email'        => $siswa->email,
                'status_akun'  => 'nonaktif',
            ]);

        $this->assertDatabaseHas('siswa', [
            'id'          => $siswa->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id'          => $siswa->user_id,
            'status_aktif' => false,
        ]);
    }

    // =========================================================================
    // GROUP 4: PBI-071 — Nonaktifkan & aktifkan kembali akun siswa
    // =========================================================================

    /** @test */
    public function test_admin_dapat_nonaktifkan_akun_siswa()
    {
        $siswa = $this->buatSiswaAktif();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.siswa.nonaktifkan', $siswa->id));

        $response->assertRedirect(route('admin.siswa.show', $siswa->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'id'          => $siswa->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id'          => $siswa->user_id,
            'status_aktif' => false,
        ]);
    }

    /** @test */
    public function test_admin_dapat_aktifkan_kembali_akun_siswa_nonaktif()
    {
        $siswa = $this->buatSiswaAktif();
        $siswa->update(['status_akun' => 'nonaktif']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.siswa.aktifkan', $siswa->id));

        $response->assertRedirect(route('admin.siswa.show', $siswa->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'id'          => $siswa->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id'          => $siswa->user_id,
            'status_aktif' => true,
        ]);
    }

    /** @test */
    public function test_nonaktifkan_akun_yang_sudah_nonaktif_mengembalikan_error()
    {
        $siswa = $this->buatSiswaAktif();
        $siswa->update(['status_akun' => 'nonaktif']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.siswa.nonaktifkan', $siswa->id));

        $response->assertStatus(422);
    }

    /** @test */
    public function test_aktifkan_akun_yang_sudah_aktif_mengembalikan_error()
    {
        $siswa = $this->buatSiswaAktif(); // sudah aktif

        $response = $this->actingAs($this->admin)
            ->post(route('admin.siswa.aktifkan', $siswa->id));

        $response->assertStatus(422);
    }

    /** @test */
    public function test_toggle_status_mengubah_aktif_menjadi_nonaktif()
    {
        $siswa = $this->buatSiswaAktif();

        $this->actingAs($this->admin)
            ->post(route('admin.siswa.toggle-status', $siswa->id));

        $this->assertDatabaseHas('siswa', [
            'id'          => $siswa->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id'          => $siswa->user_id,
            'status_aktif' => false,
        ]);
    }

    /** @test */
    public function test_toggle_status_mengubah_nonaktif_menjadi_aktif()
    {
        $siswa = $this->buatSiswaAktif();
        $siswa->update(['status_akun' => 'nonaktif']);

        $this->actingAs($this->admin)
            ->post(route('admin.siswa.toggle-status', $siswa->id));

        $this->assertDatabaseHas('siswa', [
            'id'          => $siswa->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id'          => $siswa->user_id,
            'status_aktif' => true,
        ]);
    }

    // =========================================================================
    // GROUP 5: PBI-072 — Profil siswa setelah login (akses sebagai siswa)
    // =========================================================================

    /** @test */
    public function test_siswa_dapat_melihat_halaman_profil_sendiri()
    {
        $siswa = $this->buatSiswaAktif();

        $response = $this->actingAs($siswa->user)
            ->get(route('siswa.profil.show'));

        $response->assertOk();
        $response->assertViewIs('siswa.profil.show');
        $response->assertViewHas('siswa');
        $response->assertSee($siswa->nama_lengkap);
    }

    /** @test */
    public function test_tamu_tidak_bisa_mengakses_halaman_profil_siswa()
    {
        $response = $this->get(route('siswa.profil.show'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_siswa_dapat_memperbarui_profil_sendiri()
    {
        $siswa = $this->buatSiswaAktif();

        $response = $this->actingAs($siswa->user)
            ->put(route('siswa.profil.update'), [
                'nama_lengkap' => 'Siti Rahayu Updated',
                'no_hp'        => '0819999999',
                'asal_sekolah' => 'Universitas Indonesia',
            ]);

        $response->assertRedirect(route('siswa.profil.show'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'id'           => $siswa->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id'           => $siswa->user_id,
            'nama_lengkap' => 'Siti Rahayu Updated',
            'no_hp'        => '0819999999',
        ]);
    }

    /** @test */
    public function test_siswa_tidak_bisa_memperbarui_email_sendiri()
    {
        $siswa = $this->buatSiswaAktif();

        // Email tidak ada di form update siswa, jadi email tidak berubah
        $this->actingAs($siswa->user)
            ->put(route('siswa.profil.update'), [
                'nama_lengkap' => $siswa->nama_lengkap,
            ]);

        // Email di database tidak berubah
        $this->assertDatabaseHas('users', [
            'id'    => $siswa->user_id,
            'email' => $siswa->email,
        ]);
    }

    /** @test */
    public function test_siswa_dapat_mengganti_password_dengan_password_lama_yang_benar()
    {
        $siswa = $this->buatSiswaAktif();

        // Set password yang diketahui
        $siswa->user->update(['password' => bcrypt('PasswordLama123!')]);

        $response = $this->actingAs($siswa->user)
            ->put(route('siswa.profil.update-password'), [
                'password_lama'         => 'PasswordLama123!',
                'password'              => 'PasswordBaru456!',
                'password_confirmation' => 'PasswordBaru456!',
            ]);

        $response->assertRedirect(route('siswa.profil.show'));
        $response->assertSessionHas('success');
    }

    /** @test */
    public function test_ganti_password_gagal_jika_password_lama_salah()
    {
        $siswa = $this->buatSiswaAktif();
        $siswa->user->update(['password' => bcrypt('PasswordLama123!')]);

        $response = $this->actingAs($siswa->user)
            ->put(route('siswa.profil.update-password'), [
                'password_lama'         => 'PasswordSalah!',
                'password'              => 'PasswordBaru456!',
                'password_confirmation' => 'PasswordBaru456!',
            ]);

        $response->assertSessionHasErrors('password_lama');
    }

    /** @test */
    public function test_siswa_tidak_bisa_mengakses_profil_siswa_lain()
    {
        $siswa1 = $this->buatSiswaAktif();
        $siswa2 = $this->buatSiswaAktif();

        // Siswa 1 mencoba akses data siswa 2 via admin route
        $response = $this->actingAs($siswa1->user)
            ->get(route('admin.siswa.show', $siswa2->id));

        // Harus ditolak (redirect ke login atau 403)
        $status = $response->getStatusCode();
        $this->assertTrue(in_array($status, [302, 403]));
    }
}
