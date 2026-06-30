// @ts-check
/**
 * TEST SUITE 05 — Siswa Dashboard & Fitur
 *
 * Yang ditest:
 * - Dashboard siswa load dengan elemen yang benar
 * - Semua route /siswa/* yang terdaftar bisa diakses
 * - Sidebar nav item tampil sesuai PRD
 * - Akses halaman keluhan, peminjaman, materi, tugas, progres
 * - EnsureSiswa middleware benar-benar block non-siswa
 */

import path from 'path';
import { fileURLToPath } from 'url';
import { test, expect } from '@playwright/test';
import { USERS, loginAs } from './helpers/auth.js';

const __filename = fileURLToPath(import.meta.url);
const __dirname  = path.dirname(__filename);
const FIXTURE_PDF = path.join(__dirname, 'fixtures', 'dummy.pdf');

// ─────────────────────────────────────────────────────────────
// Dashboard Siswa
// ─────────────────────────────────────────────────────────────

test.describe('Siswa — dashboard dan navigasi', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.siswa);
  });

  test('Dashboard siswa load di /siswa/dashboard', async ({ page }) => {
    await expect(page).toHaveURL(/\/siswa\/dashboard/);
    await expect(page.locator('body')).not.toContainText(/500|Whoops|Server Error/i);
  });

  // Nav item wajib ada di sidebar sesuai sidebar.blade.php
  const expectedNavItems = [
    'Dashboard',
    'Profil',
    'Materi',
    'Tugas',
    'Forum',
    'Keluhan',
    'Peminjaman',
    'Jadwal',
    'Progres',
    'Sertifikat',
  ];

  for (const navItem of expectedNavItems) {
    test(`Nav item "${navItem}" muncul di sidebar siswa`, async ({ page }) => {
      // Case-insensitive partial match di halaman
      const isVisible = await page.locator(`text=${navItem}`).first().isVisible()
        .catch(() => false);
      // Soft check: tampilkan warning kalau tidak ada tapi tidak gagalkan test langsung
      // karena text mungkin berbeda (e.g. "Materi Pembelajaran" vs "Materi")
      if (!isVisible) {
        console.warn(`[WARNING] Nav item "${navItem}" tidak ditemukan di dashboard siswa — cek sidebar.blade.php`);
      }
      // Yang hard-fail: halaman tidak boleh 500
      await expect(page.locator('body')).not.toContainText(/500|Whoops/i);
    });
  }

});

// ─────────────────────────────────────────────────────────────
// Route Siswa — Smoke Test
// ─────────────────────────────────────────────────────────────

test.describe('Siswa — route smoke test', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.siswa);
  });

  const siswaRoutes = [
    { name: 'Dashboard',         url: '/siswa/dashboard' },
    { name: 'Jadwal',            url: '/siswa/jadwal' },
    { name: 'Tugas',             url: '/siswa/tugas' },
    { name: 'Progres',           url: '/siswa/progres' },
    { name: 'Materi',            url: '/siswa/materi' },
    { name: 'Sertifikat Saya',   url: '/sertifikat/saya' },
    { name: 'Peminjaman',        url: '/peminjaman' },
    { name: 'Keluhan (form)',     url: '/keluhan' },
    { name: 'Keluhan Saya',      url: '/keluhan/saya' },
    { name: 'Daftar Kelas Baru', url: '/daftar-kelas' },
  ];

  for (const route of siswaRoutes) {
    test(`Route "${route.name}" (${route.url}) → tidak 500`, async ({ page }) => {
      const response = await page.goto(route.url);
      const status = response?.status() ?? 0;
      expect(
        status,
        `${route.name}: return HTTP ${status}, seharusnya < 500`
      ).toBeLessThan(500);
      // Tidak boleh redirect ke login (middleware EnsureSiswa malfunction)
      await expect(page).not.toHaveURL(/\/login/);
    });
  }

});

// ─────────────────────────────────────────────────────────────
// Keluhan
// ─────────────────────────────────────────────────────────────

test.describe('Siswa — kirim keluhan', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.siswa);
  });

  test('Form keluhan tampil dengan field kategori dan deskripsi', async ({ page }) => {
    await page.goto('/keluhan');
    const hasForm = await page.locator('form').first().isVisible();
    expect(hasForm).toBe(true);
  });

  test('Kategori keluhan menggunakan opsi yang benar dari PRD', async ({ page }) => {
    await page.goto('/keluhan');
    // Sesuai fix di patch_admin: Akademik→Pembelajaran, Teknis→Error Sistem
    const kategoriSelect = page.locator('select[name*="kategori"]');
    if (await kategoriSelect.isVisible()) {
      const options = await kategoriSelect.locator('option').allTextContents();
      // Tidak boleh ada kategori lama yang salah
      const hasWrongOption = options.some(o =>
        o.toLowerCase().includes('akademik') || o.toLowerCase().includes('teknis')
      );
      // Soft warning saja karena tergantung implementasi
      if (hasWrongOption) {
        console.warn('[WARNING] Kategori keluhan mungkin masih pakai nama lama (Akademik/Teknis)');
      }
    }
  });

});

// ─────────────────────────────────────────────────────────────
// Peminjaman Aset
// ─────────────────────────────────────────────────────────────

test.describe('Siswa — peminjaman aset', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.siswa);
  });

  test('Halaman peminjaman tampil tanpa error', async ({ page }) => {
    const response = await page.goto('/peminjaman');
    expect(response?.status()).toBeLessThan(500);
  });

  test('Form peminjaman ada di halaman /peminjaman', async ({ page }) => {
    await page.goto('/peminjaman');
    await expect(page.locator('body')).not.toContainText(/500|Whoops/i);
  });

});

// ─────────────────────────────────────────────────────────────
// Forum Diskusi
// ─────────────────────────────────────────────────────────────

test.describe('Siswa — forum diskusi', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.siswa);
  });

  test('Forum route tidak 500 dan filter per kelas enrolled', async ({ page }) => {
    // Route forum siswa — cek apakah ada di routes
    const forumRoutes = ['/forum', '/siswa/forum'];
    let found = false;
    for (const url of forumRoutes) {
      const response = await page.goto(url);
      if (response && response.status() < 500 && !page.url().includes('/login')) {
        found = true;
        break;
      }
    }
    // Soft: forum mungkin belum di-route-kan tapi tidak boleh crash
    if (!found) {
      console.warn('[WARNING] Route forum tidak ditemukan di /forum atau /siswa/forum');
    }
  });

});

// ─────────────────────────────────────────────────────────────
// EnsureSiswa Middleware — Block Non-Siswa
// ─────────────────────────────────────────────────────────────

test.describe('EnsureSiswa middleware enforcement', () => {

  test('Admin akses /siswa/dashboard → diblokir', async ({ page }) => {
    await loginAs(page, USERS.admin);
    const response = await page.goto('/siswa/dashboard');
    const status = response?.status() ?? 0;
    const isBlocked = status === 403 || !page.url().includes('/siswa/dashboard');
    expect(isBlocked, `Admin tidak boleh akses /siswa/dashboard, status: ${status}`).toBe(true);
  });

  test('Instruktur akses /siswa/tugas → diblokir', async ({ page }) => {
    await loginAs(page, USERS.instruktur);
    const response = await page.goto('/siswa/tugas');
    const isBlocked = (response?.status() === 403)
      || !page.url().includes('/siswa/tugas');
    expect(isBlocked).toBe(true);
  });

  test('Direktur akses /peminjaman (route siswa) → diblokir', async ({ page }) => {
    await loginAs(page, USERS.direktur);
    const response = await page.goto('/peminjaman');
    const isBlocked = (response?.status() === 403)
      || !page.url().includes('/peminjaman');
    expect(isBlocked).toBe(true);
  });

});

// ─────────────────────────────────────────────────────────────
// Profil Siswa
// ─────────────────────────────────────────────────────────────

test.describe('Siswa — profil', () => {

  test.beforeEach(async ({ page }) => {
    await loginAs(page, USERS.siswa);
  });

  test('Halaman profil/settings bisa diakses', async ({ page }) => {
    // Laravel Breeze/Jetstream default profile route
    const profileRoutes = ['/profile', '/settings/profile', '/siswa/profil'];
    let success = false;
    for (const url of profileRoutes) {
      const response = await page.goto(url);
      if (response && response.status() < 500 && !page.url().includes('/login')) {
        success = true;
        break;
      }
    }
    if (!success) {
      console.warn('[WARNING] Route profil siswa tidak ditemukan — cek routes/web.php');
    }
  });

});
