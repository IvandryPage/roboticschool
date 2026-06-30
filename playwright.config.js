// @ts-check
import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './e2e',
  fullyParallel: false, // false karena share satu DB — race condition kalau parallel
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 1 : 0,
  workers: 1, // satu worker untuk hindari state DB bentrok antar test
  reporter: [['html', { open: 'never' }], ['list']],

  timeout: 90_000, // 90 seconds per test global timeout

  use: {
    baseURL: process.env.BASE_URL || 'http://127.0.0.1:8000',
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    actionTimeout: 30_000,
    navigationTimeout: 60_000,
  },

  webServer: {
    command: 'php artisan serve --host=127.0.0.1 --port=8000 --no-reload',
    url: 'http://127.0.0.1:8000',
    reuseExistingServer: true, // kamu jalanin server sendiri, test tinggal pakai
    timeout: 120_000,
  },

  projects: [
    // Jalankan setup terlebih dahulu — login & simpan session
    {
      name: 'setup',
      testMatch: /auth\.setup\.js/,
    },
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
      dependencies: ['setup'],
    },
  ],
});
