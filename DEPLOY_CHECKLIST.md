# 🚀 DEPLOYMENT READINESS CHECKLIST & AUDIT REPORT
Project: **RoboNesia Academy**

Dokumen ini berisi laporan audit menyeluruh untuk memastikan project Laravel 12 + Filament v5 + Livewire + PostgreSQL ini bersih, aman, konsisten, dan siap diserahkan ke pemilik repo untuk di-deploy ke environment production.

---

## Ringkasan Status Audit

| Kategori | Status | Keterangan Ringkas |
|---|---|---|
| **1. Environment & Konfigurasi** | ⚠️ Perlu Perhatian | `.env.example` kurang lengkap, default key aman tapi butuh penyesuaian. |
| **2. Database & Migration** | ⚠️ Perlu Perhatian | Migration & seeding sukses di DB kosong, tapi ada inkonsistensi tipe data primary key (UUID vs BigInt). |
| **3. Dependency & Package** | ⚠️ Perlu Perhatian | Mismatch penempatan dependency dan kerentanan keamanan pada library html-sanitizer. |
| **4. CI/CD (GitHub Actions)** | ❌ Kritis | Workflow test & playwright rusak karena tidak ada konfigurasi database & PHP runtime dependencies di runner. |
| **5. Asset & Build** | ✅ Aman | `npm run build` dan `filament:assets` berhasil tanpa error. |
| **6. Kode & Struktur** | ❌ Kritis | Ada file-file sampah ter-commit di git dan banyak kegagalan test suite karena logic redirect admin ke Filament. |
| **7. Filament Specific** | ❌ Kritis | Terdapat file resource duplikat (melanggar PSR-4) dan resource-resource admin yang terisolasi/orphan. |
| **8. Kesiapan Deploy** | ✅ Aman | Strategi deployment run-list sudah disiapkan secara detail. |

---

## Detail Temuan & Panduan Perbaikan

### 1. Environment & Konfigurasi (Status: ⚠️ Perlu Perhatian)

- **Sinkronisasi `.env.example`**:
  * ⚠️ **Temuan**: `.env.example` sudah mencakup sebagian besar key, namun untuk variable Google OAuth (`GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT`) nilainya dikosongkan. 
  * 💡 **Solusi**: Pastikan terdokumentasi di tim bahwa integrasi Google Sign-In membutuhkan pengisian credential ini.
- **Panggilan `env()` Langsung di Codebase**:
  * ✅ **Aman**: Tidak ditemukan pemanggilan `env()` langsung di luar folder `config/`. Semua file di `app/` dan `resources/` menggunakan `config('...')`, sehingga aman ketika configurasi di-cache via `php artisan config:cache`.
- **Hardcoded Values / Path Windows / Credentials**:
  * ✅ **Aman**: Tidak ada hardcoded path Windows (seperti `C:\`) maupun credentials/API keys sensitif yang bocor di kode controller, model, atau view.
- **Default value APP_DEBUG & APP_ENV**:
  * ⚠️ **Temuan**: Di `.env.example`, `APP_ENV=local` dan `APP_DEBUG=true`.
  * 💡 **Solusi**: Di file `.env.example`, ubah atau beri dokumentasi bahwa default production harus diganti menjadi `APP_ENV=production` dan `APP_DEBUG=false`.

---

### 2. Database & Migration (Status: ⚠️ Perlu Perhatian)

- **Mengeksekusi Migration & Seeder dari Awal (Fresh)**:
  * ✅ **Aman**: Perintah `php artisan migrate:fresh --seed` sukses berjalan 100% tanpa error pada database kosong. Semua default roles dan users berhasil di-seed dengan benar.
- **Konsistensi Tipe Data Primary Key (UUID vs BigInt)**:
  * ⚠️ **Temuan**: Seluruh tabel custom (mulai dari `users`, `kelas`, `siswa`, `pendaftaran` dsb.) menggunakan **UUID** (`$table->uuid('id')->primary()`). Namun, tabel `instruktur` adalah **satu-satunya** tabel custom yang menggunakan auto-incrementing **BigInt** (`$table->id()`).
  * ⚠️ **Temuan Lanjutan**: Tabel `instruktur` ini tidak memiliki relasi foreign key dari tabel lain. Tabel `kelas` dan `evaluasi_instruktur` menghubungkan instruktur menggunakan kolom `instruktur_id` yang diarahkan ke tabel `users` (tipe UUID). Hal ini berarti tabel `instruktur` saat ini berdiri sendiri (orphan table) dan tidak konsisten dari segi skema UUID.
  * 💡 **Solusi**:
    1. Jika tabel `instruktur` akan dipakai di masa depan, ubah primary key-nya menjadi UUID agar konsisten dengan seluruh database.
    2. Jika tidak dipakai (karena instruktur diwakili oleh tipe `User` dengan role 'Instruktur'), hapus migrasi & model `Instruktur` agar database tetap bersih.

---

### 3. Dependency & Package (Status: ⚠️ Perlu Perhatian)

- **Penyimpangan `composer.json` (require vs require-dev)**:
  * ⚠️ **Temuan**: Package `laravel/chisel` terdaftar di bagian `"require"`. Chisel adalah tool starter kit development yang seharusnya ditaruh di `"require-dev"`.
  * 💡 **Solusi**: Pindahkan `"laravel/chisel": "^0.1.0"` ke bagian `"require-dev"` di `composer.json`.
- **Lock File Sync**:
  * ✅ **Aman**: `composer.lock` dan `package-lock.json` sinkron dan instalasi package bersih di environment bersih.
- **Security Vulnerability (Advisory)**:
  * ⚠️ **Temuan**: Eksekusi `composer audit` mendeteksi **5 kerentanan keamanan** (Advisories) dengan tingkat *Medium* dan *Low* pada package `symfony/html-sanitizer` (versi terkunci `7.1.*`). Celah keamanan ini meliputi XSS Bypass (CVE-2026-45753) dan Visual href Spoofing (CVE-2026-45064).
  * 💡 **Solusi**: Upgrade package `symfony/html-sanitizer` ke versi aman (misalnya `^7.1.41` atau `^7.2.0`) dengan menjalankan `composer update symfony/html-sanitizer`.

---

### 4. CI/CD / GitHub Actions (Status: ❌ Kritis)

- **Workflow `tests.yml` Rusak**:
  * ❌ **Temuan**: Workflow ini menjalankan test suite (`./vendor/bin/pest`), namun `phpunit.xml` mensyaratkan koneksi PostgreSQL (`DB_CONNECTION=pgsql`). Runner GitHub Actions tidak menyediakan service postgres, sehingga pengetesan otomatis akan langsung error/gagal koneksi.
  * 💡 **Solusi**: Tambahkan service container PostgreSQL ke `tests.yml` dan konfigurasikan environment variable yang sesuai.
- **Workflow `playwright.yml` Rusak**:
  * ❌ **Temuan**: Workflow ini menginstall npm dependencies dan menjalankan `npx playwright test`, namun Playwright dikonfigurasi untuk memanggil local web server via `php artisan serve`. Runner tidak menginstall PHP, composer packages, config `.env`, maupun database migration, sehingga server tidak dapat berjalan dan pengetesan e2e gagal total.
  * 💡 **Solusi**: Tambahkan setup PHP runtime, instalasi composer dependencies, penyalinan `.env`, dan kompilasi asset sebelum menjalankan Playwright.

#### 🛠️ Rekomendasi Perbaikan Workflow CI/CD:

**Perbaikan `tests.yml`**:
```yaml
name: tests
on: [push, pull_request]
jobs:
  ci:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:15
        env:
          POSTGRES_DB: sekolah_robotik_testing
          POSTGRES_USER: postgres
          POSTGRES_PASSWORD: password
        ports:
          - 5432:5432
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
    steps:
      - uses: actions/checkout@v4
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          tools: composer:v2
      - name: Add Flux Credentials
        run: composer config http-basic.composer.fluxui.dev "${{ secrets.FLUX_USERNAME }}" "${{ secrets.FLUX_LICENSE_KEY }}"
      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist --optimize-autoloader
      - name: Copy Env & Key Generate
        run: |
          cp .env.example .env
          php artisan key:generate
      - name: Run Tests
        env:
          DB_CONNECTION: pgsql
          DB_HOST: 127.0.0.1
          DB_PORT: 5432
          DB_DATABASE: sekolah_robotik_testing
          DB_USERNAME: postgres
          DB_PASSWORD: password
        run: ./vendor/bin/pest
```

**Perbaikan `playwright.yml`**:
```yaml
name: Playwright Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:15
        env:
          POSTGRES_DB: sekolah_robotik_testing
          POSTGRES_USER: postgres
          POSTGRES_PASSWORD: password
        ports:
          - 5432:5432
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: lts/*
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          tools: composer:v2
      - name: Add Flux Credentials
        run: composer config http-basic.composer.fluxui.dev "${{ secrets.FLUX_USERNAME }}" "${{ secrets.FLUX_LICENSE_KEY }}"
      - name: Install Composer Dependencies
        run: composer install --no-interaction --prefer-dist
      - name: Setup Env & Migrate
        env:
          DB_CONNECTION: pgsql
          DB_HOST: 127.0.0.1
          DB_PORT: 5432
          DB_DATABASE: sekolah_robotik_testing
          DB_USERNAME: postgres
          DB_PASSWORD: password
        run: |
          cp .env.example .env
          php artisan key:generate
          php artisan migrate --force
      - name: Install Node Dependencies & Build Assets
        run: |
          npm ci
          npm run build
      - name: Install Playwright Browsers
        run: npx playwright install --with-deps
      - name: Run Playwright tests
        env:
          DB_CONNECTION: pgsql
          DB_HOST: 127.0.0.1
          DB_PORT: 5432
          DB_DATABASE: sekolah_robotik_testing
          DB_USERNAME: postgres
          DB_PASSWORD: password
        run: npx playwright test
```

---

### 5. Asset & Build (Status: ✅ Aman)

- **npm run build**:
  * ✅ **Aman**: Berhasil 100% tanpa error, menghasilkan asset production di folder `public/build/`.
- **php artisan filament:assets**:
  * ✅ **Aman**: Berhasil 100% tanpa error, menyalin asset CSS/JS Filament ke folder `public/`.
- **Referensi Path Asset**:
  * ✅ **Aman**: View templates menggunakan utility Laravel seperti `asset()` atau directive `@vite` untuk memuat asset, sehingga tidak akan rusak di production.

---

### 6. Kode & Struktur (Status: ❌ Kritis)

- **File/Folder Accidental Commit (.gitignore violation)**:
  * ❌ **Temuan**: Terdapat beberapa file/folder sampah yang diabaikan di `.gitignore` namun saat ini masih terlacak (tracked) oleh Git:
    - `playwright-report/index.html` (Hasil report lokal)
    - `test-results/.last-run.json`
    - Folder compiled assets `/public/css/filament/`, `/public/js/filament/`, dan `/public/fonts/filament/`
    - Script utilitas Windows `review.bat`
  * 💡 **Solusi**: Jalankan perintah pembersihan tracker git berikut tanpa menghapus file fisiknya:
    ```bash
    git rm --cached -r playwright-report/
    git rm --cached -r test-results/
    git rm --cached -r public/css/filament/ public/js/filament/ public/fonts/filament/
    git rm --cached review.bat
    git commit -m "style: remove ignored tracked files from git cache"
    ```
- **Fungsi Debugging (dd, dump, var_dump)**:
  * ✅ **Aman**: Tidak ada sisa-sisa debug seperti `dd()`, `dump()`, atau `var_dump()` yang tertinggal di folder `app/`, `resources/`, maupun `routes/`.
- **Regression / Kerusakan Test Suite**:
  * ❌ **Temuan 1 (Blade Admin Redirect)**: Di file `routes/admin.php`, seluruh route admin berbasis blade dideprecated dan diarahkan (redirect 302) ke halaman Filament panel. Namun, file test suite admin (`VerifikasiPendaftaranTest.php` dan `BuatAkunSiswaControllerTest.php`) masih menargetkan URL blade lama dan berekspektasi mengembalikan response `200 OK` atau mengubah status database. Karena ter-redirect, test suite menghasilkan **63 error / gagal**.
    * 💡 **Solusi**: Pindahkan logika pengetesan aksi admin (seperti menyetujui pendaftaran, menolak, meminta revisi, dan membuat akun siswa) untuk menargetkan halaman Filament Resource/Infolist Action atau hapus test lama yang sudah obsolete jika fitur tersebut sepenuhnya ditangani Filament v5.
  * ❌ **Temuan 2 (Bug logic `DaftarKelasController@store`)**:
    Pada `DaftarKelasController@store`, terdapat beberapa bug query SQL/database:
    1. Mengisi `'nomor_invoice'` padahal kolom di tabel `invoice` adalah `'no_invoice'`.
    2. Mengisi `'nominal'` padahal kolom di tabel `invoice` adalah `'total_tagihan'`.
    3. Mengisi `'pendaftaran_id' => null` padahal kolom tersebut berstatus non-nullable dan unique constraint di tingkat database migration (`create_invoice_table`).
    * 💡 **Solusi**: Sesuaikan input array untuk `Invoice::create` agar cocok dengan skema tabel database dan ubah kolom database `pendaftaran_id` di tabel `invoice` menjadi `nullable()` melalui migrasi baru jika pendaftaran siswa existing memang tidak memiliki ID pendaftaran.
  * ❌ **Temuan 3 (Bug test mock `PeminjamanAsetTest.php`)**:
    Test suite `PeminjamanAsetTest` mengakses `/peminjaman` menggunakan user kosong yang dibuat via `User::factory()->create()`. URL tersebut dilindungi middleware `role.siswa` (`EnsureSiswa`), sehingga test diblokir dengan status `403 Forbidden` dan gagal.
    * 💡 **Solusi**: Sesuaikan test agar membuat user dengan role 'Siswa' (`['role_id' => $roleSiswa->id]`) dan buat data model `Siswa` yang terhubung.

---

### 7. Filament Specific (Status: ❌ Kritis)

- **File Resource Duplikat (Melanggar PSR-4 Autoloading)**:
  * ❌ **Temuan**: Terdapat tiga salinan file `PengumpulanTugasResource.php` di lokasi berbeda:
    1. `app/Filament/Resources/PengumpulanTugas/PengumpulanTugasResource.php` (Benar, namespace `App\Filament\Resources\PengumpulanTugas`)
    2. `app/Providers/Filament/PengumpulanTugasResource.php` (Salah/Duplikat)
    3. `app/Filament/PengumpulanTugasResource.php` (Salah/Duplikat, namespace `App\Filament\Resources` tapi diletakkan langsung di folder `app/Filament/`)
  * 💡 **Solusi**: Hapus salinan duplikat nomor 2 dan 3. Hanya pertahankan file nomor 1.
- **Orphan/Unused Panel & Resources**:
  * ⚠️ **Temuan 1**: File `app/Providers/Filament/SepianPanelProvider.php` tidak didaftarkan di `bootstrap/providers.php`. Panel ini sepenuhnya mati dan aman untuk dihapus.
  * ⚠️ **Temuan 2**: Terdapat folder resource `app/Filament/Admin/Resources` dan `app/Filament/Publikasi/Resources` berisi class resource (seperti `MateriProgramResource`, `KemajuanBelajarResource`). Folder ini tidak didaftarkan dalam pencarian Resource panel manapun (`AdminPanelProvider` hanya mencari di `app/Filament/Resources`).
  * 💡 **Solusi**: Pindahkan resource tersebut ke folder `app/Filament/Resources/` atau daftarkan folder pencarian tambahannya di panel provider yang bersangkutan menggunakan `discoverResources()`.
- **Policy & Permission Resource**:
  * ⚠️ **Temuan**: Resource penting seperti `UserResource`, `RoleResource`, `PendaftaranResource` membatasi akses menggunakan control logic statis di class resource (misalnya `canViewAny()` memvalidasi role nama secara hardcoded). Metode ini rentan jika ada perubahan role di database.
  * 💡 **Solusi**: Implementasikan standard Laravel Policies untuk tiap model dan hubungkan ke Filament agar validasi hak akses terjadi di level policy.

---

### 8. Kesiapan Deploy (Status: ✅ Aman)

#### Simulasi Production (APP_ENV=production, APP_DEBUG=false)
Semua halaman publik dapat diakses dengan aman tanpa mengandalkan fitur debug. Halaman error 500 standar Laravel akan muncul jika terjadi error, menyembunyikan stack trace sensitif.

#### Kebutuhan storage:link
Aplikasi menyimpan file pendaftaran dan bukti pembayaran di local disk (`public`). Perintah `php artisan storage:link` **wajib** dijalankan saat deploy agar folder storage dapat diakses publik.

#### 📋 Run-List Command Deployment (Urutan First Deploy)

Jalankan perintah-perintah ini secara berurutan di server production:

1. **Install Dependencies (tanpa paket dev)**:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   ```
2. **Setup File Environment**:
   * Salin `.env.example` ke `.env` dan lengkapi konfigurasi database PostgreSQL, mail server, dan Google OAuth.
3. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```
4. **Migrasi Database & Seeding Awal**:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```
5. **Hubungkan Symlink Storage**:
   ```bash
   php artisan storage:link
   ```
6. **Kompilasi Assets untuk Production**:
   ```bash
   npm run build
   php artisan filament:assets
   ```
7. **Cache Konfigurasi & Routing (Optimasi)**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
