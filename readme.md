# Sistem Informasi Manajemen Sekolah Robotik

Proyek ini dibuat sebagai tugas mata kuliah **Rekayasa Berbasis Perangkat Lunak (RBPL)** — dikerjakan bersama satu kelas secara kolaboratif.

---

##  Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 12 |
| UI Admin | Filament v5 |
| Reaktivitas UI | Livewire |
| Database | PostgreSQL |
| CSS | Tailwind CSS |
| Testing | Pest |

---

## ⚙️ Penjelasan Singkat Tech Stack

### Laravel
Framework PHP utama. Mengurus routing, database (via Eloquent ORM), autentikasi, dan struktur MVC project.

### Filament
Admin panel siap pakai di atas Laravel. Kamu bisa buat CRUD, dashboard, form, dan tabel tanpa nulis banyak kode. Dokumentasi: https://filamentphp.com/docs

### Livewire
Membuat komponen UI yang reaktif (seperti form real-time, search, dsb) tanpa perlu tulis JavaScript. Terintegrasi langsung dengan Laravel.

### PostgreSQL
Database relasional yang dipakai project ini. Lebih robust dibanding SQLite (default Laravel), cocok untuk kolaborasi tim.

---

## 🖥️ Requirements

Pastikan semua sudah terinstall sebelum mulai:

- PHP sekitar versi 8.4 (coba coba aja sih, kalau 8.2 bisa, yaudah.)
- Composer
- Node.js 22+
- PostgreSQL 16+
- Git

---

## Setup Awal (Wajib Dibaca!)

### 1. Clone Repository

```bash
git clone https://github.com/USERNAME/REPO_NAME.git
cd REPO_NAME
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Buat File `.env`

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env`, ubah bagian database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sekolah_robotik
DB_USERNAME=postgres
DB_PASSWORD=password_postgresql_kamu
```

Buat database-nya dulu di PostgreSQL:

```bash
psql -U postgres
```

```sql
CREATE DATABASE sekolah_robotik;
\q
```

### 5. Jalankan Migration

```bash
php artisan migrate
```

### 6. Build Assets & Jalankan

```bash
npm run build
php artisan serve
```

Buka browser ke **http://127.0.0.1:8000**

---

## Watch Out — Hal yang Sering Bikin Error

### PHP Extension `pdo_pgsql` tidak aktif

Kalau muncul error `could not find driver` saat migrate, berarti extension PostgreSQL di PHP belum aktif.

Cari file `php.ini` kamu:

```bash
php --ini
```

Buka file tersebut, cari baris ini dan hapus titik koma di depannya:

```ini
extension=pdo_pgsql
extension=pgsql
```

> Kalau pakai PHP standalone (bukan XAMPP), pastikan juga `extension_dir = "ext"` tidak di-comment.

Restart terminal setelah edit `php.ini`.

---

### PHP Extension `intl` tidak aktif

Filament membutuhkan extension `intl`. Sama seperti di atas, buka `php.ini` dan uncomment:

```ini
extension=intl
```

---

### `vendor` folder tidak ada setelah `git pull`

Folder `vendor` tidak di-commit ke Git (sengaja). Setiap kali clone atau pull, jalankan:

```bash
composer install
```
---

### `.env` tidak ada

File `.env` juga tidak di-commit (karena berisi password). Selalu buat dari template:

```bash
cp .env.example .env
php artisan key:generate
```

---

### Konvensi Commit Message

| Prefix | Digunakan untuk |
|--------|----------------|
| `feat:` | Fitur baru |
| `fix:` | Bugfix |
| `chore:` | Konfigurasi, setup |
| `refactor:` | Refactor kode |
| `docs:` | Update dokumentasi |

---

## 🗂️ Struktur Folder Penting

```
app/
├── Models/          ← Model Eloquent (database)
├── Http/
│   ├── Controllers/ ← Controller
│   └── Livewire/    ← Komponen Livewire
├── Filament/
│   └── Resources/   ← CRUD Panel Filament
database/
├── migrations/      ← Skema database
└── seeders/         ← Data dummy
resources/
└── views/           ← Template Blade / Livewire
routes/
└── web.php          ← Routing utama
```

---
