// @ts-check
/**
 * TEST SUITE 01 — Autentikasi & Role Redirect
 *
 * Yang ditest:
 * - Login berhasil → redirect ke panel yang benar per role
 * - Login gagal → error message muncul
 * - Guest diblokir dari semua panel
 * - Logout membersihkan sesi
 *
 * Dependency: php artisan db:seed sudah dijalankan
 */

import { test, expect } from '@playwright/test';
import { USERS, loginAs } from './helpers/auth.js';

// ─────────────────────────────────────────────────────────────
// Login Success + Redirect Validation per Role
// ─────────────────────────────────────────────────────────────

test.describe('Login redirect per role', () => {

  test('Admin Akademik → redirect ke /admin', async ({ page }) => {
    await loginAs(page, USERS.admin);
    await expect(page).toHaveURL(/\/admin/);
    // Pastikan benar-benar masuk panel, bukan stuck di login
    await expect(page.locator('body')).not.toContainText(/Email atau password/i);
  });

  test('Instruktur → redirect ke /admin', async ({ page }) => {
    await loginAs(page, USERS.instruktur);
    await expect(page).toHaveURL(/\/admin/);
  });

  test('Direktur → redirect ke /admin', async ({ page }) => {
    await loginAs(page, USERS.direktur);
    await expect(page).toHaveURL(/\/admin/);
  });

  /**
   * BUG yang ada di test lama: test ini salah expect /admin
   * Tim Publikasi HARUS redirect ke /publikasi
   */
  test('Tim Publikasi → redirect ke /publikasi (BUKAN /admin)', async ({ page }) => {
    await loginAs(page, USERS.publikasi);
    await expect(page).toHaveURL(/\/publikasi/);
    // Pastikan TIDAK ada di /admin
    expect(page.url()).not.toMatch(/\/admin/);
  });

  test('Siswa → redirect ke /siswa/dashboard', async ({ page }) => {
    await loginAs(page, USERS.siswa);
    await expect(page).toHaveURL(/\/siswa\/dashboard/);
  });

});

// ─────────────────────────────────────────────────────────────
// Login Failure Cases
// ─────────────────────────────────────────────────────────────

test.describe('Login gagal', () => {

  test('Password salah → tampil pesan error, tidak redirect', async ({ page }) => {
    await page.goto('/login');
    await page.locator('input[name="email"]').fill(USERS.admin.email);
    await page.locator('input[name="password"]').fill('SALAH_PASSWORD');
    await page.locator('button[type="submit"]').click();

    // Harus tetap di /login
    await expect(page).toHaveURL(/\/login/);
    // Pesan error harus muncul — sesuaikan text dengan yang ada di view login
    await expect(page.locator('body')).toContainText(/email atau password|kredensial|salah/i);
  });

  test('Email tidak terdaftar → error, tidak redirect', async ({ page }) => {
    await page.goto('/login');
    await page.locator('input[name="email"]').fill('tidakterdaftar@example.com');
    await page.locator('input[name="password"]').fill('apapun');
    await page.locator('button[type="submit"]').click();

    await expect(page).toHaveURL(/\/login/);
    await expect(page.locator('body')).toContainText(/email atau password|kredensial|salah/i);
  });

  test('Form kosong → tidak boleh submit (HTML5 required)', async ({ page }) => {
    await page.goto('/login');
    // Klik submit tanpa isi apapun
    await page.locator('button[type="submit"]').click();
    // Harus tetap di /login karena HTML5 required validation atau server validation
    await expect(page).toHaveURL(/\/login/);
  });

});

// ─────────────────────────────────────────────────────────────
// Guest Access Control
// ─────────────────────────────────────────────────────────────

test.describe('Guest diblokir dari panel terautentikasi', () => {

  test('Guest akses /admin → redirect ke /login', async ({ page }) => {
    await page.goto('/admin');
    await expect(page).toHaveURL(/\/login/);
  });

  test('Guest akses /admin/users → redirect ke /login', async ({ page }) => {
    await page.goto('/admin/users');
    await expect(page).toHaveURL(/\/login/);
  });

  test('Guest akses /publikasi → redirect ke /login', async ({ page }) => {
    await page.goto('/publikasi');
    await expect(page).toHaveURL(/\/login/);
  });

  test('Guest akses /siswa/dashboard → redirect ke /login', async ({ page }) => {
    await page.goto('/siswa/dashboard');
    await expect(page).toHaveURL(/\/login/);
  });

});

// ─────────────────────────────────────────────────────────────
// Cross-Role Access Enforcement
// ─────────────────────────────────────────────────────────────

test.describe('Cross-role access control', () => {

  test('Siswa tidak bisa akses /admin/users → dapat 403 atau redirect', async ({ page }) => {
    await loginAs(page, USERS.siswa);
    const response = await page.goto('/admin/users');
    const status = response?.status() ?? 0;
    const isForbidden = status === 403 || page.url().includes('/login') || page.url().includes('/siswa');
    expect(isForbidden, `Siswa seharusnya tidak bisa akses /admin/users, status: ${status}`).toBe(true);
  });

  test('Siswa tidak bisa akses /publikasi → dapat 403 atau redirect', async ({ page }) => {
    await loginAs(page, USERS.siswa);
    const response = await page.goto('/publikasi');
    const status = response?.status() ?? 0;
    const isBlocked = status === 403 || !page.url().includes('/publikasi');
    expect(isBlocked, `Siswa seharusnya tidak bisa akses /publikasi, status: ${status}`).toBe(true);
  });

  test('Tim Publikasi tidak bisa akses /siswa/dashboard → diblokir', async ({ page }) => {
    await loginAs(page, USERS.publikasi);
    const response = await page.goto('/siswa/dashboard');
    const status = response?.status() ?? 0;
    const isBlocked = status === 403 || !page.url().includes('/siswa/dashboard');
    expect(isBlocked, `Tim Publikasi seharusnya tidak bisa akses /siswa/dashboard`).toBe(true);
  });

  test('Instruktur tidak bisa akses /siswa/dashboard → diblokir', async ({ page }) => {
    await loginAs(page, USERS.instruktur);
    const response = await page.goto('/siswa/dashboard');
    const status = response?.status() ?? 0;
    const isBlocked = status === 403 || !page.url().includes('/siswa/dashboard');
    expect(isBlocked).toBe(true);
  });

});

// ─────────────────────────────────────────────────────────────
// Logout
// ─────────────────────────────────────────────────────────────

test.describe('Logout', () => {

  test('Setelah logout, akses /admin diarahkan ke /login', async ({ page }) => {
    await loginAs(page, USERS.admin);
    await expect(page).toHaveURL(/\/admin/);

    // Logout via POST /logout (Laravel default)
    await page.evaluate(async () => {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
      await fetch('/logout', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json' },
      });
    });

    await page.goto('/admin');
    await expect(page).toHaveURL(/\/login/);
  });

});
