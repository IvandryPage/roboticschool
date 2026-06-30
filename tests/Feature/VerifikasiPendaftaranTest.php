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
 * PBI-073 — Pengujian alur verifikasi pendaftaran
 *
 * Mencakup seluruh alur dari pendaftaran masuk hingga admin melakukan
 * tindakan (setujui / revisi / tolak) dan dampaknya terhadap status.
 *
 * Jalankan: php artisan test --filter=VerifikasiPendaftaranTest
 */
class VerifikasiPendaftaranTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private ProgramKursus $program;

    protected function setUp(): void
    {
        parent::setUp();

        $role = \App\Models\Role::firstOrCreate(['nama_role' => 'Admin Akademik']);

        // Buat admin untuk semua test
        $this->admin = User::factory()->create([
            'name'  => 'Admin Test',
            'email' => 'admin@test.com',
            'role_id' => $role->id,
        ]);

        // Buat program kursus
        $this->program = ProgramKursus::factory()->create([
            'nama_program' => 'Web Development',
        ]);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    /**
     * Buat pendaftaran dengan status tertentu
     */
    private function buatPendaftaran(string $status = 'pending'): Pendaftaran
    {
        $calonPeserta = CalonPeserta::factory()->create([
            'nama_lengkap' => 'Budi Santoso',
            'email'        => 'budi@example.com',
            'no_hp'        => '08123456789',
        ]);

        return Pendaftaran::factory()->create([
            'calon_peserta_id' => $calonPeserta->id,
            'program_id'       => $this->program->id,
            'status'           => $status,
        ]);
    }

    // =========================================================================
    // GROUP 1: Akses halaman daftar & detail pendaftaran
    // =========================================================================

    /** @test */
    public function test_admin_dapat_melihat_daftar_pendaftaran()
    {
        $this->buatPendaftaran('pending');
        $this->buatPendaftaran('disetujui');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.pendaftaran.index'));

        $response->assertOk();
        $response->assertViewIs('admin.pendaftaran.index');
        $response->assertViewHas('pendaftaranList');
    }

    /** @test */
    public function test_admin_dapat_melihat_detail_pendaftaran()
    {
        $pendaftaran = $this->buatPendaftaran('pending');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.pendaftaran.show', $pendaftaran->id));

        $response->assertOk();
        $response->assertViewIs('admin.pendaftaran.show');
        $response->assertViewHas('pendaftaran');
    }

    /** @test */
    public function test_tamu_tidak_dapat_mengakses_halaman_pendaftaran()
    {
        $response = $this->get(route('admin.pendaftaran.index'));

        $response->assertRedirect(route('login'));
    }

    // =========================================================================
    // GROUP 2: Alur setujui pendaftaran
    // =========================================================================

    /** @test */
    public function test_admin_dapat_menyetujui_pendaftaran_pending()
    {
        $pendaftaran = $this->buatPendaftaran('pending');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.setujui', $pendaftaran->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftaran', [
            'id'     => $pendaftaran->id,
            'status' => 'disetujui',
        ]);
    }

    /** @test */
    public function test_admin_dapat_menyetujui_pendaftaran_yang_sedang_revisi()
    {
        $pendaftaran = $this->buatPendaftaran('revisi');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.setujui', $pendaftaran->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('pendaftaran', [
            'id'     => $pendaftaran->id,
            'status' => 'disetujui',
        ]);
    }

    /** @test */
    public function test_pendaftaran_yang_sudah_disetujui_tidak_bisa_disetujui_ulang()
    {
        $pendaftaran = $this->buatPendaftaran('disetujui');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.setujui', $pendaftaran->id));

        // Harus redirect dengan error atau 422, bukan mengubah status
        try {
            $response->assertStatus(422);
        } catch (\PHPUnit\Framework\AssertionFailedError $e) {
            $response->assertSessionHas('error');
        }
    }

    // =========================================================================
    // GROUP 3: Alur revisi pendaftaran
    // =========================================================================

    /** @test */
    public function test_admin_dapat_meminta_revisi_pendaftaran_pending()
    {
        $pendaftaran = $this->buatPendaftaran('pending');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.revisi', $pendaftaran->id), [
                'catatan_revisi' => 'Dokumen belum lengkap, harap lampirkan KTP.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftaran', [
            'id'     => $pendaftaran->id,
            'status' => 'revisi',
        ]);
    }

    /** @test */
    public function test_permintaan_revisi_membutuhkan_catatan()
    {
        $pendaftaran = $this->buatPendaftaran('pending');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.revisi', $pendaftaran->id), [
                'catatan_revisi' => '',
            ]);

        $response->assertSessionHasErrors('catatan_revisi');

        // Status tidak berubah
        $this->assertDatabaseHas('pendaftaran', [
            'id'     => $pendaftaran->id,
            'status' => 'pending',
        ]);
    }

    // =========================================================================
    // GROUP 4: Alur tolak pendaftaran
    // =========================================================================

    /** @test */
    public function test_admin_dapat_menolak_pendaftaran_pending()
    {
        $pendaftaran = $this->buatPendaftaran('pending');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.tolak', $pendaftaran->id), [
                'alasan_penolakan' => 'Kuota sudah penuh.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftaran', [
            'id'     => $pendaftaran->id,
            'status' => 'ditolak',
        ]);
    }

    /** @test */
    public function test_admin_dapat_menolak_pendaftaran_revisi()
    {
        $pendaftaran = $this->buatPendaftaran('revisi');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.tolak', $pendaftaran->id), [
                'alasan_penolakan' => 'Dokumen tidak valid setelah revisi.',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('pendaftaran', [
            'id'     => $pendaftaran->id,
            'status' => 'ditolak',
        ]);
    }

    /** @test */
    public function test_penolakan_membutuhkan_alasan()
    {
        $pendaftaran = $this->buatPendaftaran('pending');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.tolak', $pendaftaran->id), [
                'alasan_penolakan' => '',
            ]);

        $response->assertSessionHasErrors('alasan_penolakan');
    }

    // =========================================================================
    // GROUP 5: Pendaftaran yang ditolak tidak bisa diubah lagi
    // =========================================================================

    /** @test */
    public function test_pendaftaran_ditolak_tidak_bisa_disetujui()
    {
        $pendaftaran = $this->buatPendaftaran('ditolak');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.pendaftaran.setujui', $pendaftaran->id));

        $this->assertDatabaseHas('pendaftaran', [
            'id'     => $pendaftaran->id,
            'status' => 'ditolak',
        ]);
    }

    // =========================================================================
    // GROUP 6: Tombol buat akun siswa muncul setelah disetujui (PBI-067 integrasi)
    // =========================================================================

    /** @test */
    public function test_halaman_detail_pendaftaran_disetujui_menampilkan_tombol_buat_akun()
    {
        $pendaftaran = $this->buatPendaftaran('disetujui');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.pendaftaran.show', $pendaftaran->id));

        $response->assertOk();
        $response->assertSee(route('admin.siswa.create-akun', $pendaftaran->id));
    }

    /** @test */
    public function test_halaman_detail_pendaftaran_pending_tidak_menampilkan_tombol_buat_akun()
    {
        $pendaftaran = $this->buatPendaftaran('pending');

        $response = $this->actingAs($this->admin)
            ->get(route('admin.pendaftaran.show', $pendaftaran->id));

        $response->assertOk();
        $response->assertDontSee(route('admin.siswa.create-akun', $pendaftaran->id));
    }
}
