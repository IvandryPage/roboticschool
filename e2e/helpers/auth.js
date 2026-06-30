// @ts-check
import { expect } from '@playwright/test';

/**
 * Kredensial sesuai DatabaseSeeder.php
 * Pastikan sudah `php artisan db:seed` sebelum run test
 */
export const USERS = {
  admin: { email: 'admin@example.test', password: 'admin123', redirectTo: /\/admin/ },
  instruktur: { email: 'instruktur1@robonesia.test', password: 'password', redirectTo: /\/admin/ },
  instruktur2: { email: 'instruktur2@robonesia.test', password: 'password', redirectTo: /\/admin/ },
  direktur: { email: 'direktur@robonesia.test', password: 'password', redirectTo: /\/admin/ },
  publikasi: { email: 'publikasi@robonesia.test', password: 'password', redirectTo: /\/publikasi/ },
  siswa: { email: 'budi@siswa.test', password: 'password', redirectTo: /\/siswa\/dashboard/ },
  siswa2: { email: 'dewi@siswa.test', password: 'password', redirectTo: /\/siswa\/dashboard/ },
};

/**
 * Login dan verifikasi redirect ke halaman yang benar sesuai role.
 * @param {import('@playwright/test').Page} page
 * @param {{ email: string, password: string, redirectTo: RegExp }} user
 */
export async function loginAs(page, user) {
  await page.goto('/login');
  await page.locator('input[name="email"]').fill(user.email);
  await page.locator('input[name="password"]').fill(user.password);
  await page.locator('button[type="submit"]').click();
  await page.waitForURL(user.redirectTo, { timeout: 45_000 });
}

/**
 * Cek HTTP status satu URL tanpa perlu full page assertion.
 * @param {import('@playwright/test').Page} page
 * @param {string} url
 * @param {number} expectedStatus
 */
export async function expectHttpStatus(page, url, expectedStatus) {
  const response = await page.goto(url);
  expect(response?.status(), `Expected HTTP ${expectedStatus} for ${url}`).toBe(expectedStatus);
}
