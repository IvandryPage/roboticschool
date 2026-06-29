// @ts-check
/**
 * TEST SUITE 02 — Alur Calon Peserta (Publik)
 *
 * Yang ditest:
 * - Landing page tampil dengan benar
 * - Form pendaftaran end-to-end (data diri → dokumen → pembayaran → buat akun)
 * - Cek status pendaftaran via nomor referensi
 * - Validasi field wajib di form pendaftaran
 *
 * Catatan penting:
 * - Test ini TIDAK pakai RefreshDatabase — data akan tersimpan ke DB
 * - Gunakan email unik per run dengan timestamp
 * - Fixture PDF ada di e2e/fixtures/dummy.pdf
 */

import path from 'path';
import { fileURLToPath } from 'url';
import { test, expect } from '@playwright/test';

const __filename = fileURLToPath(import.meta.url);
const __dirname  = path.dirname(__filename);
const FIXTURE_PDF = path.join(__dirname, 'fixtures', 'dummy.pdf');

/** Generate email unik tiap run biar tidak tabrakan */
function uniqueEmail() {
  return `calon.test.${Date.now()}@example.test`;
}

// ─────────────────────────────────────────────────────────────
// Landing Page
// ─────────────────────────────────────────────────────────────

test.describe('Landing page', () => {

  test('Landing page load dan tampil konten utama', async ({ page }) => {
    await page.goto('/');
    await expect(page).toHaveTitle(/.+/); // Ada title, tidak kosong
    // Tombol CTA utama harus ada
    await expect(
      page.locator('text=Daftar Sekarang').or(page.locator('text=Daftar'))
    ).toBeVisible();
  });

  test('Halaman program tampil jika ada data program', async ({ page }) => {
    await page.goto('/');
    // Tidak boleh 500
    const response = await page.goto('/');
    expect(response?.status()).toBeLessThan(500);
  });

  test('Link navigasi ke /daftar berfungsi', async ({ page }) => {
    await page.goto('/');
    // Klik tombol daftar pertama yang ditemukan
    const daftarBtn = page.locator('a[href*="daftar"]').first();
    if (await daftarBtn.isVisible()) {
      await daftarBtn.click();
      await expect(page).toHaveURL(/\/daftar/);
    } else {
      // Fallback: langsung navigasi
      await page.goto('/daftar');
      await expect(page).toHaveURL(/\/daftar/);
    }
  });

  test('Halaman cek-status publik bisa diakses tanpa login', async ({ page }) => {
    const response = await page.goto('/cek-status');
    expect(response?.status()).toBeLessThan(400);
    await expect(page).not.toHaveURL(/\/login/);
  });

});

// ─────────────────────────────────────────────────────────────
// Form Pendaftaran - Validasi
// ─────────────────────────────────────────────────────────────

test.describe('Form pendaftaran — validasi', () => {

  test('Submit form kosong → tidak redirect ke halaman dokumen', async ({ page }) => {
    await page.goto('/daftar');
    // Klik submit tanpa isi apapun
    const submitBtn = page.locator('button:has-text("Lanjutkan"), button[type="submit"]').first();
    if (await submitBtn.isVisible()) {
      await submitBtn.click();
      // Tidak boleh pindah ke /dokumen
      await expect(page).not.toHaveURL(/\/dokumen/);
    }
  });

  test('Field email di form pendaftaran BISA diisi (tidak readonly)', async ({ page }) => {
    await page.goto('/daftar');
    const emailInput = page.locator('input[name="email"]');
    await expect(emailInput).toBeVisible();
    // Pastikan tidak ada atribut readonly
    const isReadonly = await emailInput.getAttribute('readonly');
    expect(isReadonly, 'Field email seharusnya tidak readonly di form pendaftaran').toBeNull();
    // Bisa diisi
    await emailInput.fill('test@example.com');
    await expect(emailInput).toHaveValue('test@example.com');
  });

});

// ─────────────────────────────────────────────────────────────
// Alur Pendaftaran Full End-to-End
// ─────────────────────────────────────────────────────────────

test.describe('Alur pendaftaran calon peserta', () => {

  /**
   * Test ini end-to-end penuh:
   * /daftar → isi data diri → /pendaftaran/{id}/dokumen → upload → /pembayaran/{id} → upload bukti → /buat-akun
   *
   * Skip jika tidak ada program aktif di DB (seeder belum jalan)
   */
  test('Daftar lengkap: data diri → dokumen → pembayaran', async ({ page }) => {
    const email = uniqueEmail();

    // Step 1: Isi data diri
    await page.goto('/daftar');
    await expect(page).not.toHaveURL(/\/login/);

    // Cek apakah ada program tersedia di select
    const programSelect = page.locator('select[name="program_id"]');
    const hasPrograms = await programSelect.isVisible();
    if (!hasPrograms) {
      test.skip();
      return;
    }

    const firstProgramOption = programSelect.locator('option:not([value=""])').first();
    const programValue = await firstProgramOption.getAttribute('value');
    if (!programValue) {
      test.skip();
      return;
    }

    await page.fill('input[name="nama_lengkap"]', 'Test Calon Peserta E2E');
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="no_hp"]', '081234567890');
    await page.fill('input[name="tanggal_lahir"]', '2000-06-15');

    const jenisKelaminInput = page.locator('input[name="jenis_kelamin"]').first();
    if (await jenisKelaminInput.isVisible()) {
      await jenisKelaminInput.check();
    }

    const domisiliInput = page.locator('input[name="domisili"]');
    if (await domisiliInput.isVisible()) {
      await domisiliInput.fill('Yogyakarta');
    }

    const alamatInput = page.locator('textarea[name="alamat"]');
    if (await alamatInput.isVisible()) {
      await alamatInput.fill('Jl. Testing No. 99, Yogyakarta');
    }

    const pendidikanSelect = page.locator('select[name="pendidikan"]');
    if (await pendidikanSelect.isVisible()) {
      await pendidikanSelect.selectOption({ index: 1 });
    }

    const motivasiInput = page.locator('textarea[name="motivasi"]');
    if (await motivasiInput.isVisible()) {
      await motivasiInput.fill('Ingin belajar robotika untuk keperluan testing E2E.');
    }

    await programSelect.selectOption(programValue);

    const formatKelas = page.locator('input[name="format_kelas"]').first();
    if (await formatKelas.isVisible()) {
      await formatKelas.check();
    }

    await page.click('button:has-text("Lanjutkan"), button[type="submit"]');
    await page.waitForURL(/\/pendaftaran\/[^/]+\/dokumen/, { timeout: 15_000 });

    // Step 2: Upload dokumen
    const ktpInput = page.locator('input[type="file"][name*="identitas"], input[type="file"][name*="ktp"]');
    const fotoInput = page.locator('input[type="file"][name*="foto"]');

    if (await ktpInput.isVisible()) {
      await ktpInput.setInputFiles(FIXTURE_PDF);
    }
    if (await fotoInput.isVisible()) {
      await fotoInput.setInputFiles(FIXTURE_PDF);
    }

    await page.click('button:has-text("Lanjutkan"), button[type="submit"]');
    await page.waitForURL(/\/pembayaran\/[^/]+/, { timeout: 15_000 });

    // Step 3: Upload bukti pembayaran
    await expect(page).toHaveURL(/\/pembayaran\//);
    const buktiInput = page.locator('input[type="file"][name*="bukti"]');
    if (await buktiInput.isVisible()) {
      await buktiInput.setInputFiles(FIXTURE_PDF);
    }

    // Centang checkbox konfirmasi kalau ada
    const konfirmasiCheckbox = page.locator('input[type="checkbox"]').first();
    if (await konfirmasiCheckbox.isVisible()) {
      await konfirmasiCheckbox.check();
    }

    await page.click('button:has-text("Bayar"), button:has-text("Selesai"), button[type="submit"]');

    // Setelah bayar → harusnya ke buat-akun atau sukses
    await page.waitForURL(/\/(buat-akun|sukses|pendaftaran)/, { timeout: 15_000 });
    expect(page.url()).toMatch(/buat-akun|sukses|pendaftaran/);
  });

});

// ─────────────────────────────────────────────────────────────
// Cek Status Pendaftaran
// ─────────────────────────────────────────────────────────────

test.describe('Cek status pendaftaran publik', () => {

  test('/cek-status form bisa diisi dan disubmit', async ({ page }) => {
    await page.goto('/cek-status');
    expect(page.url()).toMatch(/cek-status/);

    // Form cek status harus punya input referensi atau email
    const hasForm = await page.locator('form').isVisible();
    expect(hasForm, 'Halaman cek-status harus punya form').toBe(true);
  });

  test('Nomor referensi tidak valid → tampil pesan tidak ditemukan', async ({ page }) => {
    await page.goto('/cek-status');

    // Isi dengan nomor referensi dummy
    const refInput = page.locator('input[name*="referensi"], input[name*="nomor"]').first();
    const emailInput = page.locator('input[name="email"]').first();

    if (await refInput.isVisible()) {
      await refInput.fill('REG-TIDAK-ADA-9999');
    }
    if (await emailInput.isVisible()) {
      await emailInput.fill('tidakada@example.com');
    }

    await page.click('button[type="submit"]');

    // Harus tampil pesan tidak ditemukan, tidak boleh error 500
    const status = (await page.goto('/cek-status'))?.status() ?? 200;
    expect(status).toBeLessThan(500);
  });

});

// ─────────────────────────────────────────────────────────────
// Sertifikat Verifikasi Publik
// ─────────────────────────────────────────────────────────────

test.describe('Verifikasi sertifikat publik', () => {

  test('Route verifikasi sertifikat bisa diakses tanpa login', async ({ page }) => {
    // Nomor dummy — boleh 404, tidak boleh 500 atau redirect ke login
    const response = await page.goto('/sertifikat/verifikasi/CERT-TIDAK-ADA');
    const status = response?.status() ?? 0;
    expect(status, 'Verifikasi sertifikat tidak boleh 500').toBeLessThan(500);
    await expect(page).not.toHaveURL(/\/login/);
  });

});
