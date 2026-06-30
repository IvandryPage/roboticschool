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
  // Tunggu redirect selesai agar cookie session tersimpan sebelum navigasi berikutnya
  const expectedUrl = user.email.includes('siswa') ? /\/siswa\/dashboard/ : (user.email.includes('publikasi') ? /\/publikasi/ : /\/admin/);
  await page.waitForURL(expectedUrl, { timeout: 45_000 });
}

function pickRegistrationEmail() {
  return `calon+${Date.now()}@example.test`;
}

test.describe('RoboNesia end-to-end business scenario', () => {
  test('Public registration, document upload, payment, and status check', async ({ page }) => {
    const registrationEmail = pickRegistrationEmail();

    await page.goto(BASE_URL);
    await expect(page.locator('text=Daftar Sekarang').first()).toBeVisible();
    await page.locator('text=Daftar Sekarang').first().click();

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

    await expect(page).toHaveURL(/\/pendaftaran\/[^/]+\/dokumen$/);
    await expect(page.locator('h1', { hasText: 'Upload Dokumen' })).toBeVisible();

    const ktpInput = page.locator('input[type="file"][name*="identitas"], input[type="file"][name*="ktp"]');
    const fotoInput = page.locator('input[type="file"][name*="foto"]');
    if (await ktpInput.isVisible()) {
      await ktpInput.setInputFiles(FIXTURE_PDF);
    }
    if (await fotoInput.isVisible()) {
      await fotoInput.setInputFiles(FIXTURE_PDF);
    }
    await page.click('button:has-text("Lanjutkan")');

    await expect(page).toHaveURL(/\/pembayaran\/[^/]+$/);
    await expect(page.locator('h1', { hasText: 'Pembayaran' })).toBeVisible();

    // Pilih metode pembayaran — klik label wrapper karena radio input disembunyikan
    const metodeLabel = page.locator('label.method').first();
    if (await metodeLabel.isVisible()) {
      await metodeLabel.click();
    }

    const buktiInput = page.locator('input[type="file"][name*="bukti"]');
    if (await buktiInput.isVisible()) {
      await buktiInput.setInputFiles(FIXTURE_PDF);
    }

    const konfirmasiCheckbox = page.locator('input[type="checkbox"]').first();
    if (await konfirmasiCheckbox.isVisible()) {
      await konfirmasiCheckbox.check();
    }
    await page.click('button[type="submit"]');

    // Setelah bayar → harusnya ke buat-akun
    await expect(page).toHaveURL(/\/pendaftaran\/[^/]+\/buat-akun$/);
    await page.fill('input[name="password"]', 'password123');
    await page.fill('input[name="password_confirmation"]', 'password123');
    await page.click('button[type="submit"]');

    // Sukses buat akun → redirect ke dashboard siswa
    await expect(page).toHaveURL(/\/siswa\/dashboard$/);
    await expect(page.locator('text=Dashboard').first()).toBeVisible();
  });

  test('Admin can login and reach admin dashboard', async ({ page }) => {
    await login(page, USERS.admin);
    await expect(page).toHaveURL(/\/admin($|\/)/);
    await expect(page.locator('h1').filter({ hasText: 'Dashboard' }).first()).toBeVisible();
  });

  test('Instruktur can login and reach admin panel', async ({ page }) => {
    await login(page, USERS.instruktur);
    await expect(page).toHaveURL(/\/admin($|\/)/);
  });

  test('Direktur can login and reach admin panel', async ({ page }) => {
    await login(page, USERS.direktur);
    await expect(page).toHaveURL(/\/admin($|\/)/);
  });

  test('Publikasi can login and reach publikasi panel', async ({ page }) => {
    await login(page, USERS.publikasi);
    await expect(page).toHaveURL(/\/publikasi($|\/)/);
  });

  test('Invalid login shows an error message', async ({ page }) => {
    await page.goto(`${BASE_URL}/login`);
    await page.locator('input[name="email"]').fill('wrong@test.com');
    await page.locator('input[name="password"]').fill('wrong-password');
    await page.locator('button[type="submit"]').click();
    await expect(page.locator('text=Email atau password yang Anda masukkan salah.').first()).toBeVisible();
  });
});

const ADMIN_AUTH = path.join(__dirname, '..', 'playwright', '.auth', 'admin.json');
const SISWA_AUTH = path.join(__dirname, '..', 'playwright', '.auth', 'siswa.json');

test.describe('RoboNesia admin and siswa resource checks', () => {
  test.use({ storageState: ADMIN_AUTH });

  test('Admin can view users list', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/users`);
    await expect(page.locator('text=Akun Pengguna').first()).toBeVisible();
  });

  test('Admin can view create user page', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/users/create`);
    await page.waitForLoadState('networkidle');
    const hasForm = await page.locator('form, [wire\\:id]').first().isVisible();
    expect(hasForm).toBe(true);
  });

  test('Admin can view kelas list', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/kelas`);
    await expect(page.locator('text=Kelas').first()).toBeVisible();
  });

  test('Admin can view create kelas page', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/kelas/create`);
    await page.waitForLoadState('networkidle');
    const hasForm = await page.locator('form, [wire\\:id]').first().isVisible();
    expect(hasForm).toBe(true);
  });

  test('Admin can view aset robotik list', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/aset-robotiks`);
    await expect(page.locator('text=Aset Robotik').first()).toBeVisible();
  });

  test('Admin can view pembayaran list', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/pembayaran`);
    await expect(page.locator('text=Pembayaran').first()).toBeVisible();
  });
});

test.describe('RoboNesia role-based access — Guest', () => {
  test('Guest is redirected from admin pages to login', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/users`);
    await expect(page).toHaveURL(/\/login/);
  });
});

test.describe('RoboNesia role-based access — Siswa', () => {
  test.use({ storageState: SISWA_AUTH });

  test('Siswa cannot access admin users page', async ({ page }) => {
    await page.goto(`${BASE_URL}/admin/users`);
    await expect(page.locator('body')).toContainText(/forbidden|unauthorized|403/i);
  });

  test('Siswa dashboard contains expected items', async ({ page }) => {
    await page.goto(`${BASE_URL}/siswa/dashboard`);
    await expect(page).toHaveURL(/\/siswa\/dashboard$/);
    await expect(page.locator('text=Profil Saya').first()).toBeVisible();
    await expect(page.locator('text=Sertifikat Saya').first()).toBeVisible();
    await expect(page.locator('text=Peminjaman Aset').first()).toBeVisible();
    await expect(page.locator('text=Forum Diskusi').first()).toBeVisible();
    await expect(page.locator('text=Kirim Keluhan').first()).toBeVisible();
  });
});

test.describe('RoboNesia smoke-route checks', () => {
  test.use({ storageState: ADMIN_AUTH });

  const routes = [
    '/admin/users',
    '/admin/kelas',
    '/admin/aset-robotiks',
    '/admin/pembayaran',
    '/admin/tugas',
    '/admin/materi-pembelajarans',
    '/admin/tiket-keluhans',
    '/admin/peminjaman-item-asets',
    '/admin/pendaftarans',
  ];

  for (const route of routes) {
    test(`Route Works ${route}`, async ({ page }) => {
      const response = await page.goto(`${BASE_URL}${route}`);
      expect(response?.status()).toBeLessThan(500);
    });
  }
});
