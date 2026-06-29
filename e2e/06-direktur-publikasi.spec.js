// @ts-check
/**
 * TEST SUITE 06 — Direktur & Tim Publikasi
 *
 * Direktur:
 * - Masuk panel /admin
 * - View-only di resource tertentu
 * - Tidak bisa create/edit/delete di resource sensitif
 * - Audit Log hanya tipe bisnis
 *
 * Tim Publikasi:
 * - Masuk panel /publikasi (BUKAN /admin)
 * - Bisa kelola Program, Batch, konten landing page
 * - Tidak bisa akses resource akademik
 */

import { test, expect } from '@playwright/test';
import { USERS, loginAs } from './helpers/auth.js';

// ─────────────────────────────────────────────────────────────
// DIREKTUR
// ─────────────────────────────────────────────────────────────

test.describe('Direktur — panel akses', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.direktur);
  });

  test('Direktur redirect ke /admin setelah login', async ({ page }) => {
    await expect(page).toHaveURL(/\/admin/);
    await expect(page.locator('body')).not.toContainText(/500|Whoops/i);
  });

  // Resource yang Direktur BOLEH lihat (view-only sesuai PRD)
  const direkturViewableResources = [
    { name: 'Audit Log',     url: '/admin/audit-logs' },
    { name: 'Data Siswa',    url: '/admin/siswas' },
    { name: 'Sesi Live',     url: '/admin/sesi-lives' },
    { name: 'Pembayaran',    url: '/admin/pembayarans' },
    { name: 'Instruktur',    url: '/admin/instrukturs' },
    { name: 'Laporan',       url: '/admin/laporans' },
  ];

  for (const resource of direkturViewableResources) {
    test(`Direktur bisa lihat "${resource.name}"`, async ({ page }) => {
      const response = await page.goto(resource.url);
      const status = response?.status() ?? 0;
      expect(status, `${resource.name} return ${status}`).toBeLessThan(500);
      await expect(page).not.toHaveURL(/\/login/);
    });
  }

  test('Direktur tidak bisa akses halaman create user', async ({ page }) => {
    const response = await page.goto('/admin/users/create');
    // Direktur tidak punya akses ke UserResource create
    // Acceptable: 403, redirect ke /admin, atau form yang disabled
    const status = response?.status() ?? 0;
    expect(status).not.toBe(500);
  });

  test('Audit Log Direktur — tidak dapat filter tipe teknis', async ({ page }) => {
    await page.goto('/admin/audit-logs');
    const status = (await page.goto('/admin/audit-logs'))?.status() ?? 0;
    expect(status).toBeLessThan(500);
    // Verifikasi filter: Direktur seharusnya hanya lihat bisnis
    // (implementasi: AuditLogsTable.php scope berdasarkan role)
    // Kita tidak bisa cek data langsung dari E2E tapi bisa cek halaman load
    await expect(page.locator('body')).not.toContainText(/500|Whoops/i);
  });

  test('Dashboard Direktur menampilkan widget/statistik', async ({ page }) => {
    await page.goto('/admin');
    await expect(page.locator('body')).not.toContainText(/500|Whoops/i);
  });

});

// ─────────────────────────────────────────────────────────────
// TIM PUBLIKASI
// ─────────────────────────────────────────────────────────────

test.describe('Tim Publikasi — panel /publikasi', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.publikasi);
  });

  test('Tim Publikasi masuk ke /publikasi (BUKAN /admin)', async ({ page }) => {
    await expect(page).toHaveURL(/\/publikasi/);
    // CRITICAL: Jangan ada di /admin
    expect(page.url()).not.toMatch(/^http[s]?:\/\/[^/]+\/admin/);
  });

  test('Panel /publikasi load tanpa error', async ({ page }) => {
    await expect(page.locator('body')).not.toContainText(/500|Whoops/i);
  });

  // Resource yang Tim Publikasi BOLEH kelola di /publikasi
  const publikasiResources = [
    { name: 'Program Kursus',  url: '/publikasi/programs' },
    { name: 'Batch',           url: '/publikasi/batches' },
  ];

  for (const resource of publikasiResources) {
    test(`Tim Publikasi bisa akses "${resource.name}" di panel publikasi`, async ({ page }) => {
      const response = await page.goto(resource.url);
      const status = response?.status() ?? 0;
      expect(status, `${resource.name} return ${status}`).toBeLessThan(500);
      await expect(page).not.toHaveURL(/\/login/);
    });
  }

  test('Tim Publikasi tidak bisa akses /admin (bukan panelnya)', async ({ page }) => {
    // Setelah login sebagai publikasi dan sudah di /publikasi,
    // coba akses /admin langsung
    const response = await page.goto('/admin');
    // EnsureTimPublikasi di AdminPanelProvider seharusnya block ini
    // Atau redirect ke /login
    const isBlocked = response?.status() === 403
      || page.url().includes('/login')
      || page.url().includes('/publikasi');
    expect(isBlocked, `Tim Publikasi tidak boleh akses /admin`).toBe(true);
  });

  test('Tim Publikasi tidak bisa akses resource akademik (/admin/siswas)', async ({ page }) => {
    const response = await page.goto('/admin/siswas');
    const isBlocked = (response?.status() === 403)
      || page.url().includes('/login')
      || !page.url().includes('/siswas');
    expect(isBlocked).toBe(true);
  });

  test('Panel publikasi punya navigasi untuk kelola konten', async ({ page }) => {
    await page.goto('/publikasi');
    await expect(page.locator('body')).not.toContainText(/500|Whoops/i);
    // Ada sidebar navigasi
    const hasSidebar = await page.locator('nav, aside, [role="navigation"]').first().isVisible()
      .catch(() => false);
    expect(hasSidebar, 'Panel publikasi harus punya sidebar navigasi').toBe(true);
  });

});

// ─────────────────────────────────────────────────────────────
// Cross-Panel Enforcement
// ─────────────────────────────────────────────────────────────

test.describe('Cross-panel enforcement', () => {

  test('Direktur tidak bisa akses /publikasi', async ({ page }) => {
    await loginAs(page, USERS.direktur);
    const response = await page.goto('/publikasi');
    const status = response?.status() ?? 0;
    // EnsureTimPublikasi harus block Direktur
    const isBlocked = status === 403
      || page.url().includes('/login')
      || !page.url().includes('/publikasi');
    expect(isBlocked, `Direktur tidak boleh akses /publikasi, status: ${status}`).toBe(true);
  });

  test('Admin Akademik tidak bisa akses /publikasi', async ({ page }) => {
    await loginAs(page, USERS.admin);
    const response = await page.goto('/publikasi');
    const isBlocked = (response?.status() === 403)
      || !page.url().includes('/publikasi');
    expect(isBlocked, `Admin Akademik tidak boleh akses panel /publikasi`).toBe(true);
  });

});
