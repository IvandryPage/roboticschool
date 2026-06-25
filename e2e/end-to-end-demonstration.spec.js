import path from 'path';
import { fileURLToPath } from 'url';
import { test, expect } from '@playwright/test';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const FIXTURE_PDF = path.join(__dirname, 'fixtures', 'dummy.pdf');

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';

const USERS = {
  admin: { email: 'admin@example.test', password: 'admin123' },
  instruktur: { email: 'instruktur1@robonesia.test', password: 'password' },
  direktur: { email: 'direktur@robonesia.test', password: 'password' },
  publikasi: { email: 'publikasi@robonesia.test', password: 'password' },
  siswa: { email: 'budi@siswa.test', password: 'password' },
};

async function login(page, user) {
  await page.goto(`${BASE_URL}/login`);
  await expect(page).toHaveURL(`${BASE_URL}/login`);
  await page.locator('input[name="email"]').fill(user.email);
  await page.locator('input[name="password"]').fill(user.password);
  await page.locator('button[type="submit"]').click();
}

function pickRegistrationEmail() {
  return `calon+${Date.now()}@example.test`;
}

test.describe('RoboNesia end-to-end business scenario', () => {
  test('Public registration, document upload, payment, and status check', async ({ page }) => {
    const registrationEmail = pickRegistrationEmail();

    await page.goto(BASE_URL);
    await expect(page.locator('text=Daftar Sekarang')).toBeVisible();
    await page.click('text=Daftar Sekarang');

    await expect(page).toHaveURL(/\/daftar$/);
    await expect(page.locator('h1', { hasText: 'Data Diri' })).toBeVisible();

    await page.fill('input[name="nama_lengkap"]', 'Calon Peserta Demo');
    await page.fill('input[name="email"]', registrationEmail);
    await page.fill('input[name="no_hp"]', '081234567890');
    await page.fill('input[name="tanggal_lahir"]', '2000-01-01');
    await page.check('input[name="jenis_kelamin"][value="Laki-laki"]');
    await page.fill('input[name="domisili"]', 'Bandung');
    await page.fill('textarea[name="alamat"]', 'Jl. Contoh No. 1, Bandung');
    await page.selectOption('select[name="pendidikan"]', { label: 'Mahasiswa' });
    await page.fill('input[name="institusi"]', 'Universitas Contoh');
    await page.fill('textarea[name="motivasi"]', 'Saya ingin belajar robotika untuk masa depan.');
    await page.check('input[name="format_kelas"][value="Online"]');

    const programSelect = page.locator('select[name="program_id"]');
    const programValue = await programSelect.locator('option:not([value=""])').nth(0).getAttribute('value');
    await programSelect.selectOption(programValue ?? '');
    await page.click('button:has-text("Lanjutkan")');

    await expect(page).toHaveURL(/\/pendaftaran\/\d+\/dokumen$/);
    await expect(page.locator('h1', { hasText: 'Upload Dokumen' })).toBeVisible();

    await page.setInputFiles('input[name="dokumen_identitas"]', FIXTURE_PDF);
    await page.setInputFiles('input[name="pas_foto"]', FIXTURE_PDF);
    await page.click('button:has-text("Lanjutkan")');

    await expect(page).toHaveURL(/\/pembayaran\/\d+$/);
    await expect(page.locator('h1', { hasText: 'Pembayaran' })).toBeVisible();
    await expect(page.locator('input[name="bukti_pembayaran"]')).toBeVisible();

    await page.check('input[name="metode"][value="transfer"]');
    await page.setInputFiles('input[name="bukti_pembayaran"]', FIXTURE_PDF);
    await page.click('input[type="checkbox"]');
    await page.click('button:has-text("Bayar & Selesaikan")');

    await expect(page).toHaveURL(/\/pendaftaran\/\d+\/sukses$/);
    await expect(page.locator('text=Pendaftaran Berhasil')).toBeVisible();
    await expect(page.locator('text=Lihat Status Pendaftaran Saya')).toBeVisible();

    await page.click('text=Lihat Status Pendaftaran Saya');
    await expect(page).toHaveURL(/\/pendaftaran-saya$/);
    await expect(page.locator('text=Status Pendaftaran Saya')).toBeVisible();
  });

  test('Admin can login and reach admin dashboard', async ({ page }) => {
    await login(page, USERS.admin);
    await expect(page).toHaveURL(/\/admin($|\/)/);
    await expect(page.locator('text=Dashboard')).toBeVisible();
  });

  test('Instruktur can login and reach admin panel', async ({ page }) => {
    await login(page, USERS.instruktur);
    await expect(page).toHaveURL(/\/admin($|\/)/);
  });

  test('Direktur can login and reach admin panel', async ({ page }) => {
    await login(page, USERS.direktur);
    await expect(page).toHaveURL(/\/admin($|\/)/);
  });

  test('Publikasi can login and reach admin panel', async ({ page }) => {
    await login(page, USERS.publikasi);
    await expect(page).toHaveURL(/\/admin($|\/)/);
  });

  test('Invalid login shows an error message', async ({ page }) => {
    await page.goto(`${BASE_URL}/login`);
    await page.locator('input[name="email"]').fill('wrong@test.com');
    await page.locator('input[name="password"]').fill('wrong-password');
    await page.locator('button[type="submit"]').click();
    await expect(page.locator('text=Email atau password yang Anda masukkan salah.')).toBeVisible();
  });
});

test.describe('RoboNesia admin and siswa resource checks', () => {
  test.beforeEach(async ({ page }) => {
    await login(page, USERS.admin);
  });

  test('Admin can view users list', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/users`);
    await expect(page.locator('text=Users')).toBeVisible();
  });

  test('Admin can view create user page', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/users/create`);
    await expect(page.locator('form')).toBeVisible();
  });

  test('Admin can view kelas list', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/kelas`);
    await expect(page.locator('text=Kelas')).toBeVisible();
  });

  test('Admin can view create kelas page', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/kelas/create`);
    await expect(page.locator('form')).toBeVisible();
  });

  test('Admin can view aset robotik list', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/aset-robotiks`);
    await expect(page.locator('text=Aset Robotik')).toBeVisible();
  });

  test('Admin can view pembayaran list', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/pembayarans`);
    await expect(page.locator('text=Pembayaran')).toBeVisible();
  });
});

test.describe('RoboNesia role-based access', () => {
  test('Guest is redirected from admin pages to login', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/users`);
    await expect(page).toHaveURL(/\/login/);
  });

  test('Siswa cannot access admin users page', async ({ page }) => {
    await login(page, USERS.siswa);
    await page.goto(`${BASE_URL}/admin/users`);
    await expect(page.locator('body')).toContainText(/forbidden|unauthorized|403/i);
  });

  test('Siswa dashboard contains expected items', async ({ page }) => {
    await login(page, USERS.siswa);
    await expect(page).toHaveURL(/\/siswa\/dashboard$/);
    await expect(page.locator('text=Profil Saya')).toBeVisible();
    await expect(page.locator('text=Sertifikat Saya')).toBeVisible();
    await expect(page.locator('text=Peminjaman Aset')).toBeVisible();
    await expect(page.locator('text=Forum Diskusi')).toBeVisible();
    await expect(page.locator('text=Kirim Keluhan')).toBeVisible();
  });
});

test.describe('RoboNesia smoke-route checks', () => {
  const routes = [
    '/admin/users',
    '/admin/kelas',
    '/admin/aset-robotiks',
    '/admin/pembayarans',
    '/admin/tugas',
    '/admin/materi-pembelajarans',
    '/admin/tiket-keluhans',
    '/admin/peminjaman-item-asets',
    '/admin/pendaftarans',
  ];

  test.beforeEach(async ({ page }) => {
    await login(page, USERS.admin);
  });

  for (const route of routes) {
    test(`Route Works ${route}`, async ({ page }) => {
      const response = await page.goto(`${BASE_URL}${route}`);
      expect(response?.status()).toBeLessThan(500);
    });
  }
});
