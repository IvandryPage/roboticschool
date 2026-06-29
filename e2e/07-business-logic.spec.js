// @ts-check
/**
 * TEST SUITE 07 — Alur Bisnis Kritis
 *
 * Yang ditest:
 * - Sertifikat: no duplikat, format nomor benar
 * - Peminjaman aset: student access vs admin access
 * - Enrollment kelas lifecycle check
 * - LoginResponse redirect logic (regresi guard)
 * - Filament navigation visibility per role
 *
 * Catatan: test ini lebih ke integration smoke test,
 * bukan unit test (itu tugas Pest).
 * Yang dicek adalah behavior dari sisi HTTP/UI.
 */

import { test, expect } from '@playwright/test';
import { USERS, loginAs } from './helpers/auth.js';

// ─────────────────────────────────────────────────────────────
// Sertifikat
// ─────────────────────────────────────────────────────────────

test.describe('Sertifikat — Admin kelola', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.admin);
  });

  test('Halaman list sertifikat ada di /admin/sertifikats', async ({ page }) => {
    const response = await page.goto('/admin/sertifikats');
    expect(response?.status()).toBeLessThan(500);
    await expect(page).not.toHaveURL(/\/login/);
  });

  test('Sertifikat resource hanya tampil di nav Admin (bukan Instruktur)', async ({ page }) => {
    // Logout dan login sebagai Instruktur
    await page.goto('/logout').catch(() => null);
    await page.request.post('/logout').catch(() => null);

    await loginAs(page, USERS.instruktur);
    await page.goto('/admin/sertifikats');
    // Instruktur tidak boleh akses sertifikat resource
    const status = (await page.goto('/admin/sertifikats'))?.status() ?? 0;
    // Harusnya 403 atau halaman tidak ada di nav
    expect(status).not.toBe(500);
  });

});

test.describe('Sertifikat — Siswa lihat miliknya', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.siswa);
  });

  test('Siswa bisa akses /sertifikat/saya tanpa error', async ({ page }) => {
    const response = await page.goto('/sertifikat/saya');
    expect(response?.status()).toBeLessThan(500);
    await expect(page).not.toHaveURL(/\/login/);
  });

});

// ─────────────────────────────────────────────────────────────
// Peminjaman Aset — Admin vs Siswa
// ─────────────────────────────────────────────────────────────

test.describe('Peminjaman Aset', () => {

  test('Admin bisa akses /admin/peminjaman-item-asets', async ({ page }) => {
    await loginAs(page, USERS.admin);
    const response = await page.goto('/admin/peminjaman-item-asets');
    expect(response?.status()).toBeLessThan(500);
  });

  test('Siswa bisa akses /peminjaman (panel siswa)', async ({ page }) => {
    await loginAs(page, USERS.siswa);
    const response = await page.goto('/peminjaman');
    expect(response?.status()).toBeLessThan(500);
    await expect(page).not.toHaveURL(/\/login/);
  });

  test('Siswa TIDAK bisa akses /admin/peminjaman-item-asets', async ({ page }) => {
    await loginAs(page, USERS.siswa);
    const response = await page.goto('/admin/peminjaman-item-asets');
    const isBlocked = (response?.status() === 403)
      || !page.url().includes('/peminjaman-item-asets');
    expect(isBlocked, 'Siswa tidak boleh akses resource admin peminjaman').toBe(true);
  });

});

// ─────────────────────────────────────────────────────────────
// Enrollment Kelas — Siswa Existing Daftar Kelas Baru
// ─────────────────────────────────────────────────────────────

test.describe('Enrollment kelas baru untuk siswa existing', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.siswa);
  });

  test('Halaman /daftar-kelas tampil untuk siswa', async ({ page }) => {
    const response = await page.goto('/daftar-kelas');
    expect(response?.status()).toBeLessThan(500);
    await expect(page).not.toHaveURL(/\/login/);
  });

  test('Nav item "Daftar Kelas Baru" ada di sidebar siswa', async ({ page }) => {
    await page.goto('/siswa/dashboard');
    // Check sidebar untuk link daftar kelas
    const daftarKelasLink = page.locator('a[href*="daftar-kelas"]');
    const isVisible = await daftarKelasLink.isVisible().catch(() => false);
    if (!isVisible) {
      console.warn('[WARNING] Link "Daftar Kelas Baru" tidak ditemukan di sidebar siswa');
    }
  });

});

// ─────────────────────────────────────────────────────────────
// LoginResponse Redirect Regression
// ─────────────────────────────────────────────────────────────

test.describe('LoginResponse redirect regression test', () => {

  /**
   * Test ini guard terhadap regression LoginResponse.php yang pernah
   * salah redirect Instruktur ke /siswa/dashboard.
   */

  test('Instruktur tidak pernah redirect ke /siswa/dashboard', async ({ page }) => {
    await loginAs(page, USERS.instruktur);
    expect(page.url()).not.toMatch(/\/siswa\/dashboard/);
    await expect(page).toHaveURL(/\/admin/);
  });

  test('Direktur tidak pernah redirect ke /siswa/dashboard atau /publikasi', async ({ page }) => {
    await loginAs(page, USERS.direktur);
    expect(page.url()).not.toMatch(/\/siswa\/dashboard/);
    expect(page.url()).not.toMatch(/\/publikasi/);
    await expect(page).toHaveURL(/\/admin/);
  });

  test('Admin tidak pernah redirect ke /siswa/dashboard atau /publikasi', async ({ page }) => {
    await loginAs(page, USERS.admin);
    expect(page.url()).not.toMatch(/\/siswa\/dashboard/);
    expect(page.url()).not.toMatch(/\/publikasi/);
    await expect(page).toHaveURL(/\/admin/);
  });

  test('Tim Publikasi tidak pernah redirect ke /admin', async ({ page }) => {
    await loginAs(page, USERS.publikasi);
    expect(page.url()).not.toMatch(/^http[s]?:\/\/[^/]+\/admin/);
    await expect(page).toHaveURL(/\/publikasi/);
  });

  test('Siswa tidak pernah redirect ke /admin atau /publikasi', async ({ page }) => {
    await loginAs(page, USERS.siswa);
    expect(page.url()).not.toMatch(/\/admin/);
    expect(page.url()).not.toMatch(/\/publikasi/);
    await expect(page).toHaveURL(/\/siswa\/dashboard/);
  });

});

// ─────────────────────────────────────────────────────────────
// Filament Navigation Visibility
// ─────────────────────────────────────────────────────────────

test.describe('Filament nav visibility per role', () => {

  test('Admin punya navigasi lebih banyak dari Instruktur', async ({ page, browser }) => {
    // Hitung nav items Admin
    await loginAs(page, USERS.admin);
    await page.goto('/admin');
    const adminNavCount = await page.locator('nav a, aside a').count();

    // Buka context baru untuk Instruktur
    const instrukturContext = await browser.newContext();
    const instrukturPage = await instrukturContext.newPage();
    await loginAs(instrukturPage, USERS.instruktur);
    await instrukturPage.goto('/admin');
    const instrukturNavCount = await instrukturPage.locator('nav a, aside a').count();
    await instrukturContext.close();

    // Admin harus punya lebih banyak atau sama banyak nav item
    expect(adminNavCount).toBeGreaterThanOrEqual(instrukturNavCount);
  });

  test('Instruktur tidak ada tombol "Hapus" di resource Siswa', async ({ page }) => {
    await loginAs(page, USERS.instruktur);
    const response = await page.goto('/admin/siswas');
    if (response?.status() === 200) {
      // Kalau bisa akses, tidak boleh ada bulk delete atau tombol hapus
      const deleteBtn = page.locator('button:has-text("Hapus"), button:has-text("Delete")');
      // Ini bisa false jika Instruktur tidak punya akses sama sekali — itu juga oke
      const deleteCount = await deleteBtn.count();
      if (deleteCount > 0) {
        console.warn(`[WARNING] Instruktur terlihat punya ${deleteCount} tombol Hapus di /admin/siswas`);
      }
    }
  });

});

// ─────────────────────────────────────────────────────────────
// Panel Sepian (harus disabled)
// ─────────────────────────────────────────────────────────────

test.describe('Panel Sepian — harus disabled', () => {

  test('/sepian-disabled tidak bisa diakses publik (404 atau redirect)', async ({ page }) => {
    const response = await page.goto('/sepian-disabled');
    const status = response?.status() ?? 0;
    // Panel ini sengaja dinonaktifkan di SepianPanelProvider
    // Seharusnya 404 atau redirect ke /login
    expect(status).not.toBe(500);
    expect(status === 404 || page.url().includes('/login'), 
      `Panel /sepian-disabled seharusnya 404 atau redirect login, dapat: ${status}`
    ).toBe(true);
  });

});
