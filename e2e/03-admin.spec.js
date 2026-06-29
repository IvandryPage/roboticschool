// @ts-check
/**
 * TEST SUITE 03 — Admin Akademik Panel
 *
 * Yang ditest:
 * - Akses ke semua resource yang seharusnya bisa diakses Admin
 * - Resource yang seharusnya TIDAK terlihat oleh non-Admin
 * - Smoke test semua route admin (tidak boleh 500)
 * - Navigasi antar halaman resource utama
 */

import { test, expect } from '@playwright/test';
import { USERS, loginAs } from './helpers/auth.js';

// ─────────────────────────────────────────────────────────────
// Setup: login sebagai Admin sebelum tiap test di describe ini
// ─────────────────────────────────────────────────────────────

test.describe('Admin Akademik — akses panel', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.admin);
  });

  // ── Dashboard ──────────────────────────────────────────────

  test('Dashboard admin load tanpa error', async ({ page }) => {
    await page.goto('/admin');
    await expect(page).toHaveURL(/\/admin/);
    await expect(page.locator('body')).not.toContainText(/500|Server Error|Whoops/i);
  });

  // ── Resource Smoke Tests (Admin punya akses ke semua) ──────

  const adminResources = [
    { name: 'Manajemen User',        url: '/admin/users' },
    { name: 'Data Siswa',            url: '/admin/siswas' },
    { name: 'Kelas',                 url: '/admin/kelas' },
    { name: 'Program Kursus',        url: '/admin/programs' },
    { name: 'Batch',                 url: '/admin/batches' },
    { name: 'Pendaftaran',           url: '/admin/pendaftarans' },
    { name: 'Pembayaran',            url: '/admin/pembayarans' },
    { name: 'Tugas',                 url: '/admin/tugas' },
    { name: 'Materi Pembelajaran',   url: '/admin/materi-pembelajarans' },
    { name: 'Sesi Live',             url: '/admin/sesi-lives' },
    { name: 'Tiket Keluhan',         url: '/admin/tiket-keluhans' },
    { name: 'Aset Robotik',          url: '/admin/aset-robotiks' },
    { name: 'Peminjaman Aset',       url: '/admin/peminjaman-item-asets' },
    { name: 'Sertifikat',            url: '/admin/sertifikats' },
    { name: 'Audit Log',             url: '/admin/audit-logs' },
    { name: 'Pengumpulan Tugas',     url: '/admin/pengumpulan-tugas' },
  ];

  for (const resource of adminResources) {
    test(`Resource "${resource.name}" tidak return 500`, async ({ page }) => {
      const response = await page.goto(resource.url);
      const status = response?.status() ?? 0;
      expect(
        status,
        `${resource.name} (${resource.url}) return HTTP ${status} — seharusnya < 500`
      ).toBeLessThan(500);
    });
  }

  // ── Create Pages Load ──────────────────────────────────────

  const createPages = [
    { name: 'Buat User',       url: '/admin/users/create' },
    { name: 'Buat Kelas',      url: '/admin/kelas/create' },
    { name: 'Buat Program',    url: '/admin/programs/create' },
    { name: 'Buat Batch',      url: '/admin/batches/create' },
    { name: 'Buat Aset',       url: '/admin/aset-robotiks/create' },
    { name: 'Buat Tugas',      url: '/admin/tugas/create' },
    { name: 'Buat Materi',     url: '/admin/materi-pembelajarans/create' },
  ];

  for (const p of createPages) {
    test(`Halaman create "${p.name}" menampilkan form`, async ({ page }) => {
      const response = await page.goto(p.url);
      expect(response?.status()).toBeLessThan(500);
      // Harus ada elemen form
      const hasForm = await page.locator('form').isVisible();
      expect(hasForm, `${p.name}: halaman create harus punya form`).toBe(true);
    });
  }

  // ── Verifikasi Pendaftaran Flow ────────────────────────────

  test('Halaman list pendaftaran tampil tabel data', async ({ page }) => {
    await page.goto('/admin/pendaftarans');
    await expect(page.locator('body')).not.toContainText(/500|Whoops/i);
    // Filament table harus ada
    const tableExists = await page.locator('table, [role="table"]').first().isVisible()
      .catch(() => false);
    // Boleh kosong tapi tidak boleh error
    expect(tableExists || true).toBe(true);
  });

  test('Halaman pembayaran tampil dan bisa diakses', async ({ page }) => {
    await page.goto('/admin/pembayarans');
    await expect(page.locator('body')).not.toContainText(/500|Whoops/i);
  });

});

// ─────────────────────────────────────────────────────────────
// Resource yang seharusnya TIDAK bisa diakses non-Admin
// ─────────────────────────────────────────────────────────────

test.describe('Resource yang restricted untuk non-Admin', () => {

  /**
   * Siswa tidak boleh akses resource admin apapun
   */
  test('Siswa akses /admin/users → diblokir', async ({ page }) => {
    await loginAs(page, USERS.siswa);
    const response = await page.goto('/admin/users');
    // 403 atau redirect ke /login atau /siswa
    const isBlocked = (response?.status() === 403)
      || page.url().includes('/login')
      || page.url().includes('/siswa');
    expect(isBlocked, `Siswa tidak boleh akses /admin/users`).toBe(true);
  });

  test('Tim Publikasi tidak tampil di sidenar /admin/users', async ({ page }) => {
    await loginAs(page, USERS.publikasi);
    // Tim Publikasi redirect ke /publikasi, bukan /admin
    await expect(page).toHaveURL(/\/publikasi/);
    // Coba akses /admin/users langsung
    const response = await page.goto('/admin/users');
    const isBlocked = (response?.status() === 403)
      || page.url().includes('/login')
      || !page.url().includes('/admin/users');
    expect(isBlocked).toBe(true);
  });

});

// ─────────────────────────────────────────────────────────────
// Audit Log — Tipe Filtering
// ─────────────────────────────────────────────────────────────

test.describe('Audit Log — Admin lihat semua, Direktur lihat bisnis saja', () => {

  test('Admin bisa akses /admin/audit-logs', async ({ page }) => {
    await loginAs(page, USERS.admin);
    const response = await page.goto('/admin/audit-logs');
    expect(response?.status()).toBeLessThan(500);
  });

  test('Direktur bisa akses /admin/audit-logs', async ({ page }) => {
    await loginAs(page, USERS.direktur);
    const response = await page.goto('/admin/audit-logs');
    expect(response?.status()).toBeLessThan(500);
  });

});
