// @ts-check
/**
 * Auth Setup — dijalankan SEKALI sebelum semua test.
 * Login untuk setiap role dan simpan storageState ke file.
 * Test lain tinggal pakai file ini tanpa perlu login ulang.
 */
import { test as setup, expect } from '@playwright/test';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const AUTH_DIR = path.join(__dirname, '..', 'playwright', '.auth');

setup('login sebagai admin', async ({ page }) => {
  await page.goto('/login');
  await page.locator('input[name="email"]').fill('admin@example.test');
  await page.locator('input[name="password"]').fill('admin123');
  await page.locator('button[type="submit"]').click();
  await page.waitForURL(/\/admin/, { timeout: 60_000 });
  await page.context().storageState({ path: path.join(AUTH_DIR, 'admin.json') });
});

setup('login sebagai instruktur', async ({ page }) => {
  await page.goto('/login');
  await page.locator('input[name="email"]').fill('instruktur1@robonesia.test');
  await page.locator('input[name="password"]').fill('password');
  await page.locator('button[type="submit"]').click();
  await page.waitForURL(/\/admin/, { timeout: 60_000 });
  await page.context().storageState({ path: path.join(AUTH_DIR, 'instruktur.json') });
});

setup('login sebagai publikasi', async ({ page }) => {
  await page.goto('/login');
  await page.locator('input[name="email"]').fill('publikasi@robonesia.test');
  await page.locator('input[name="password"]').fill('password');
  await page.locator('button[type="submit"]').click();
  await page.waitForURL(/\/publikasi/, { timeout: 60_000 });
  await page.context().storageState({ path: path.join(AUTH_DIR, 'publikasi.json') });
});

setup('login sebagai siswa', async ({ page }) => {
  await page.goto('/login');
  await page.locator('input[name="email"]').fill('budi@siswa.test');
  await page.locator('input[name="password"]').fill('password');
  await page.locator('button[type="submit"]').click();
  await page.waitForURL(/\/siswa\/dashboard/, { timeout: 60_000 });
  await page.context().storageState({ path: path.join(AUTH_DIR, 'siswa.json') });
});
