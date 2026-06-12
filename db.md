# Laravel Migration Specification - Sistem Informasi Manajemen Sekolah Robotik

## Tujuan

Buat seluruh migration Laravel 12 untuk Sistem Informasi Manajemen Sekolah Robotik berdasarkan spesifikasi database berikut.

Gunakan:

- Laravel 12
- PostgreSQL
- UUID sebagai primary key untuk seluruh tabel
- Soft Delete hanya jika memang diperlukan
- Foreign Key Constraint aktif
- Index dan Unique Constraint sesuai spesifikasi
- Timestamp menggunakan `created_at` dan `updated_at`
- Gunakan enum apabila memungkinkan, namun lebih disarankan memakai string + validation pada level aplikasi agar mudah dikembangkan.

---

# Aturan Umum

## UUID

Seluruh tabel menggunakan:

```php
$table->uuid('id')->primary();
```

Foreign key menggunakan:

```php
$table->foreignUuid('user_id')
      ->constrained('users');
```

---

## Timestamps

Seluruh tabel memiliki:

```php
$table->timestamps();
```

---

# MODUL AKUN & AKSES

## roles

| Field      | Type          |
| ---------- | ------------- |
| id         | uuid pk       |
| nama_role  | string unique |
| deskripsi  | text nullable |
| created_at | timestamp     |
| updated_at | timestamp     |

Constraint:

- nama_role unique

---

## users

| Field        | Type                 |
| ------------ | -------------------- |
| id           | uuid pk              |
| nama_lengkap | string               |
| email        | string unique        |
| no_hp        | string nullable      |
| password     | string               |
| foto_profil  | string nullable      |
| role_id      | uuid fk roles        |
| status_aktif | boolean default true |
| created_at   | timestamp            |
| updated_at   | timestamp            |

Constraint:

- email unique

Relationship:

- belongsTo roles

---

# MODUL PROGRAM

## program_kursus

Field:

- id
- nama_program
- deskripsi
- level
- biaya decimal
- durasi_minggu integer
- gambar nullable
- status_tampil boolean default true
- timestamps

---

## materi_program

Field:

- id
- program_id
- nomor_urut
- judul_materi
- deskripsi_materi
- timestamps

Constraint:

Unique:

```text
(program_id, nomor_urut)
```

---

## batch

Field:

- id
- program_id
- nama_batch
- tanggal_mulai
- tanggal_selesai
- kuota_max
- status_aktif default true
- timestamps

Relationship:

- belongsTo program_kursus

---

# MODUL PENDAFTARAN

## calon_peserta

Field:

- id
- nama_lengkap
- email
- no_hp
- asal_sekolah_atau_instansi
- jenjang_pendidikan
- timestamps

Catatan:

Email tidak unique.

---

## pendaftaran

Field:

- id
- calon_peserta_id
- program_id
- no_referensi unique
- tanggal_daftar
- status
- catatan_admin nullable
- timestamps

Status:

- Menunggu Verifikasi
- Revisi
- Diterima
- Ditolak
- Dibatalkan

---

## riwayat_status_pendaftaran

Field:

- id
- pendaftaran_id
- status_lama
- status_baru
- catatan
- diubah_oleh
- timestamps

---

## dokumen_pendaftaran

Field:

- id
- pendaftaran_id
- jenis_dokumen
- nama_file
- file_path
- versi default 1
- status_verifikasi
- catatan
- uploaded_at
- updated_at

Constraint:

Unique:

```text
(pendaftaran_id, jenis_dokumen, versi)
```

---

# MODUL PEMBAYARAN

## invoice

Field:

- id
- pendaftaran_id unique
- no_invoice unique
- total_tagihan decimal
- tanggal_terbit
- tanggal_jatuh_tempo
- status_pembayaran
- payment_gateway
- payment_reference unique
- gateway_payload jsonb
- timestamps

Status:

- Menunggu
- Dibayar
- Kedaluwarsa
- Gagal

---

## pembayaran

Field:

- id
- invoice_id unique
- nominal
- metode_pembayaran
- provider
- provider_reference unique
- status
- paid_at nullable
- callback_payload jsonb
- timestamps

Status:

- Pending
- Sukses
- Gagal
- Refund

---

# MODUL SISWA

## siswa

Field:

- id
- user_id unique
- pendaftaran_id unique
- tanggal_lahir
- jenis_kelamin
- alamat
- timestamps

---

## kelas

Field:

- id
- batch_id
- nama_kelas
- instruktur_id
- kapasitas
- status
- timestamps

Status:

- Aktif
- Nonaktif
- Selesai

---

## enrollment_kelas

Field:

- id
- kelas_id
- siswa_id
- tanggal_bergabung
- status
- timestamps

Constraint:

Unique:

```text
(kelas_id, siswa_id)
```

Status:

- Aktif
- Selesai
- Drop
- Dibatalkan

---

# MODUL PEMBELAJARAN

## sesi_live

Field:

- id
- kelas_id
- nomor_sesi
- judul_sesi
- tanggal
- jam_mulai
- jam_selesai
- platform
- link_akses
- keterangan
- timestamps

Constraint:

```text
(kelas_id, nomor_sesi)
```

unique

---

## kehadiran

Field:

- id
- sesi_id
- siswa_id
- status_hadir
- catatan
- dicatat_oleh
- waktu_pencatatan
- timestamps

Constraint:

```text
(sesi_id, siswa_id)
```

unique

---

## materi_pembelajaran

Field:

- id
- sesi_id
- judul
- tipe_konten
- file_path_atau_url
- urutan
- keterangan
- timestamps

Constraint:

```text
(sesi_id, urutan)
```

unique

---

## tugas

Field:

- id
- sesi_id
- judul_tugas
- deskripsi
- file_soal
- batas_waktu
- nilai_maksimum
- timestamps

---

## pengumpulan_tugas

Field:

- id
- tugas_id
- siswa_id
- file_jawaban
- catatan_siswa
- waktu_kumpul
- nilai nullable
- umpan_balik nullable
- status_penilaian
- timestamps

Constraint:

```text
(tugas_id, siswa_id)
```

unique

---

## progress_akademik

Field:

- id
- siswa_id
- kelas_id
- persentase_kehadiran
- rata_nilai_tugas
- persentase_penyelesaian
- status
- timestamps

Constraint:

```text
(siswa_id, kelas_id)
```

unique

---

## forum_topik

Field:

- id
- kelas_id
- pembuat_id
- judul
- konten
- timestamps

---

## forum_komentar

Field:

- id
- topik_id
- user_id
- komentar
- timestamps

---

# MODUL SERTIFIKAT

## sertifikat

Field:

- id
- siswa_id
- kelas_id
- nomor_sertifikat unique
- file_path
- qr_code
- verified_url
- tanggal_terbit
- diterbitkan_oleh
- timestamps

Constraint:

```text
(siswa_id, kelas_id)
```

unique

---

# MODUL ASET ROBOTIK

## aset_robotik

Field:

- id
- kode_aset unique
- nama_kit
- deskripsi
- kategori
- stok_minimal
- timestamps

---

## item_kit_robotik

Field:

- id
- aset_id
- serial_number unique
- status_kondisi
- lokasi_rak
- timestamps

---

## peminjaman_item_aset

Field:

- id
- user_id
- item_kit_id
- tanggal_pinjam
- tanggal_jatuh_tempo
- tanggal_kembali nullable
- status
- kondisi_awal
- kondisi_akhir nullable
- diverifikasi_oleh nullable
- timestamps

---

## maintenance_aset

Field:

- id
- item_kit_id
- dilaporkan_oleh
- ditangani_oleh nullable
- tanggal_lapor
- deskripsi_kerusakan
- status
- biaya nullable
- selesai_pada nullable
- timestamps

---

# MODUL OPERASIONAL

## arsip_laporan

Field:

- id
- judul
- tipe_laporan
- file_path nullable
- dibuat_oleh
- periode
- catatan
- timestamps

---

## notifikasi

Field:

- id
- user_id
- tipe
- judul
- pesan
- link_aksi nullable
- dibaca default false
- dibaca_pada nullable
- timestamps

---

## tiket_keluhan

Field:

- id
- pelapor_id
- ditangani_oleh nullable
- kategori
- prioritas
- subjek
- deskripsi
- status
- resolved_at nullable
- timestamps

---

## audit_logs

Field:

- id
- user_id
- aksi
- entity_type
- entity_id
- data_sebelum jsonb nullable
- data_sesudah jsonb nullable
- ip_address
- timestamps

---

## evaluasi_instruktur

Field:

- id
- kelas_id
- siswa_id
- instruktur_id
- skor_rata_rata
- jawaban_kuesioner jsonb
- saran_ulasan
- timestamps

Constraint:

```text
(kelas_id, siswa_id)
```

unique

---

# Output yang Diharapkan

Generate:

1. Migration file untuk seluruh tabel.
2. Urutan migration harus memperhatikan foreign key dependency.
3. Gunakan PostgreSQL compatible schema.
4. Gunakan UUID untuk seluruh primary key dan foreign key.
5. Tambahkan seluruh unique constraint dan composite index sesuai spesifikasi.
6. Jangan membuat model, factory, seeder, ataupun Filament Resource terlebih dahulu.
7. Fokus hanya pada migration yang production-ready.

Silahkan generate database di bawah ini ke dbdiagram.io untuk melihat struktur database
// ==========================================
// MODUL AKUN & AKSES
// ==========================================
Table roles {
id uuid [pk]
nama_role varchar [unique, note: 'Admin Akademik, Instruktur, Siswa, Tim Publikasi, Direktur']
deskripsi text
created_at timestamp
updated_at timestamp
}

Table users {
id uuid [pk]
nama_lengkap varchar
email varchar [unique]
no_hp varchar
password varchar
foto_profil varchar
role_id uuid [ref: > roles.id]
status_aktif boolean [default: true]
created_at timestamp
updated_at timestamp
}

// ==========================================
// MODUL PROGRAM & LANDING PAGE
// ==========================================
Table program_kursus {
id uuid [pk]
nama_program varchar
deskripsi text
level varchar
biaya numeric
durasi_minggu int
gambar varchar
status_tampil boolean [default: true]
created_at timestamp
updated_at timestamp
}

Table materi_program {
id uuid [pk]
program_id uuid [ref: > program_kursus.id]
nomor_urut int
judul_materi varchar
deskripsi_materi text
created_at timestamp
updated_at timestamp

Indexes {
(program_id, nomor_urut) [unique]
}
}

Table batch {
id uuid [pk]
program_id uuid [ref: > program_kursus.id]
nama_batch varchar [note: 'Contoh: Batch 1 - 2026, Batch Akselerasi']
tanggal_mulai date
tanggal_selesai date
kuota_max int
status_aktif boolean [default: true]
created_at timestamp
updated_at timestamp
}

// ==========================================
// MODUL PENDAFTARAN & PEMBAYARAN
// ==========================================
Table calon_peserta {
id uuid [pk]
nama_lengkap varchar
email varchar [note: 'Tidak unique, agar pendaftaran ulang tetap memungkinkan']
no_hp varchar
asal_sekolah_atau_instansi varchar
jenjang_pendidikan varchar
created_at timestamp
updated_at timestamp
}

Table pendaftaran {
id uuid [pk]
calon_peserta_id uuid [ref: > calon_peserta.id]
program_id uuid [ref: > program_kursus.id]
no_referensi varchar [unique]
tanggal_daftar timestamp
status varchar [note: 'Menunggu Verifikasi, Revisi, Diterima, Ditolak, Dibatalkan']
catatan_admin text
created_at timestamp
updated_at timestamp
}

Table riwayat_status_pendaftaran {
id uuid [pk]
pendaftaran_id uuid [ref: > pendaftaran.id]
status_lama varchar
status_baru varchar
catatan text
diubah_oleh uuid [ref: > users.id]
created_at timestamp
updated_at timestamp
}

Table dokumen_pendaftaran {
id uuid [pk]
pendaftaran_id uuid [ref: > pendaftaran.id]
jenis_dokumen varchar
nama_file varchar
file_path varchar
versi int [default: 1]
status_verifikasi varchar [note: 'Valid, Tidak Valid, Menunggu']
catatan text
uploaded_at timestamp
updated_at timestamp

Indexes {
(pendaftaran_id, jenis_dokumen, versi) [unique]
}
}

Table invoice {
id uuid [pk]
pendaftaran_id uuid [ref: > pendaftaran.id, unique]
no_invoice varchar [unique]
total_tagihan numeric
tanggal_terbit timestamp
tanggal_jatuh_tempo timestamp
status_pembayaran varchar [note: 'Menunggu, Dibayar, Kedaluwarsa, Gagal']
payment_gateway varchar
payment_reference varchar [unique]
gateway_payload jsonb
created_at timestamp
updated_at timestamp
}

Table pembayaran {
id uuid [pk]
invoice_id uuid [ref: > invoice.id, unique]
nominal numeric
metode_pembayaran varchar
provider varchar
provider_reference varchar [unique]
status varchar [note: 'Pending, Sukses, Gagal, Refund']
paid_at timestamp
callback_payload jsonb
created_at timestamp
updated_at timestamp
}

// ==========================================
// MODUL SISWA & KELAS
// ==========================================
Table siswa {
id uuid [pk]
user_id uuid [ref: - users.id]
pendaftaran_id uuid [ref: - pendaftaran.id]
tanggal_lahir date
jenis_kelamin varchar
alamat text
created_at timestamp
updated_at timestamp

Indexes {
(user_id) [unique]
(pendaftaran_id) [unique]
}
}

Table kelas {
id uuid [pk]
batch_id uuid [ref: > batch.id]
nama_kelas varchar
instruktur_id uuid [ref: > users.id]
kapasitas int
status varchar [note: 'Aktif, Nonaktif, Selesai']
created_at timestamp
updated_at timestamp
}

Table enrollment_kelas {
id uuid [pk]
kelas_id uuid [ref: > kelas.id]
siswa_id uuid [ref: > siswa.id]
tanggal_bergabung timestamp
status varchar [note: 'Aktif, Selesai, Drop, Dibatalkan']
created_at timestamp
updated_at timestamp

Indexes {
(kelas_id, siswa_id) [unique, note: 'Mencegah double enrollment siswa di kelas yang sama']
}
}

// ==========================================
// MODUL PEMBELAJARAN, PENUGASAN & KEHADIRAN
// ==========================================
Table sesi_live {
id uuid [pk]
kelas_id uuid [ref: > kelas.id]
nomor_sesi int
judul_sesi varchar
tanggal date
jam_mulai time
jam_selesai time
platform varchar
link_akses varchar
keterangan text
created_at timestamp
updated_at timestamp

Indexes {
(kelas_id, nomor_sesi) [unique]
}
}

Table kehadiran {
id uuid [pk]
sesi_id uuid [ref: > sesi_live.id]
siswa_id uuid [ref: > siswa.id]
status_hadir varchar [note: 'Hadir, Tidak Hadir, Izin']
catatan text
dicatat_oleh uuid [ref: > users.id]
waktu_pencatatan timestamp
created_at timestamp
updated_at timestamp

Indexes {
(sesi_id, siswa_id) [unique, note: 'Satu siswa hanya punya satu status absen per sesi']
}
}

Table materi_pembelajaran {
id uuid [pk]
sesi_id uuid [ref: > sesi_live.id]
judul varchar
tipe_konten varchar
file_path_atau_url varchar
urutan int
keterangan text
created_at timestamp
updated_at timestamp

Indexes {
(sesi_id, urutan) [unique]
}
}

Table tugas {
id uuid [pk]
sesi_id uuid [ref: > sesi_live.id]
judul_tugas varchar
deskripsi text
file_soal varchar
batas_waktu timestamp
nilai_maksimum numeric
created_at timestamp
updated_at timestamp
}

Table pengumpulan_tugas {
id uuid [pk]
tugas_id uuid [ref: > tugas.id]
siswa_id uuid [ref: > siswa.id]
file_jawaban varchar
catatan_siswa text
waktu_kumpul timestamp
nilai numeric
umpan_balik text
status_penilaian varchar [note: 'Belum Dinilai, Dinilai, Revisi']
created_at timestamp
updated_at timestamp

Indexes {
(tugas_id, siswa_id) [unique, note: 'Satu siswa hanya mengumpulkan satu berkas per tugas']
}
}

Table progress_akademik {
id uuid [pk]
siswa_id uuid [ref: > siswa.id]
kelas_id uuid [ref: > kelas.id]
persentase_kehadiran numeric
rata_nilai_tugas numeric
persentase_penyelesaian numeric
status varchar [note: 'Aktif, Hampir Lulus, Lulus, Remedial']
created_at timestamp
updated_at timestamp

Indexes {
(siswa_id, kelas_id) [unique, note: 'Satu record agregat per siswa per kelas']
}
}

Table forum_topik {
id uuid [pk]
kelas_id uuid [ref: > kelas.id]
pembuat_id uuid [ref: > users.id]
judul varchar
konten text
created_at timestamp
updated_at timestamp
}

Table forum_komentar {
id uuid [pk]
topik_id uuid [ref: > forum_topik.id]
user_id uuid [ref: > users.id]
komentar text
created_at timestamp
updated_at timestamp
}

// ==========================================
// MODUL SERTIFIKAT & OPERASIONAL
// ==========================================
Table sertifikat {
id uuid [pk]
siswa_id uuid [ref: > siswa.id]
kelas_id uuid [ref: > kelas.id]
nomor_sertifikat varchar [unique]
file_path varchar
qr_code varchar
verified_url varchar
tanggal_terbit timestamp
diterbitkan_oleh uuid [ref: > users.id]
created_at timestamp
updated_at timestamp

Indexes {
(siswa_id, kelas_id) [unique, note: 'Satu sertifikat per siswa per kelas']
}
}

Table aset_robotik {
id uuid [pk]
kode_aset varchar [unique]
nama_kit varchar
deskripsi text
kategori varchar
stok_minimal int
created_at timestamp
updated_at timestamp
}

Table item_kit_robotik {
id uuid [pk]
aset_id uuid [ref: > aset_robotik.id]
serial_number varchar [unique]
status_kondisi varchar [note: 'Bagus, Rusak, Perbaikan']
lokasi_rak varchar
created_at timestamp
updated_at timestamp
}

Table peminjaman_item_aset {
id uuid [pk]
user_id uuid [ref: > users.id]
item_kit_id uuid [ref: > item_kit_robotik.id]
tanggal_pinjam timestamp
tanggal_jatuh_tempo timestamp
tanggal_kembali timestamp
status varchar [note: 'Diajukan, Dipinjam, Dikembalikan, Terlambat, Rusak']
kondisi_awal varchar
kondisi_akhir varchar
diverifikasi_oleh uuid [ref: > users.id]
created_at timestamp
updated_at timestamp
}

Table maintenance_aset {
id uuid [pk]
item_kit_id uuid [ref: > item_kit_robotik.id]
dilaporkan_oleh uuid [ref: > users.id]
ditangani_oleh uuid [ref: > users.id]
tanggal_lapor timestamp
deskripsi_kerusakan text
status varchar [note: 'Open, Proses, Selesai']
biaya numeric
selesai_pada timestamp
created_at timestamp
updated_at timestamp
}

Table arsip_laporan {
id uuid [pk]
judul varchar
tipe_laporan varchar
file_path varchar
dibuat_oleh uuid [ref: > users.id]
periode varchar
catatan text
created_at timestamp
updated_at timestamp
}

Table notifikasi {
id uuid [pk]
user_id uuid [ref: > users.id]
tipe varchar
judul varchar
pesan text
link_aksi varchar
dibaca boolean [default: false]
dibaca_pada timestamp
created_at timestamp
updated_at timestamp
}

Table tiket_keluhan {
id uuid [pk]
pelapor_id uuid [ref: > users.id]
ditangani_oleh uuid [ref: > users.id]
kategori varchar [note: 'Akademik, Teknis, Administratif']
prioritas varchar [note: 'Rendah, Sedang, Tinggi, Kritis']
subjek varchar
deskripsi text
status varchar [note: 'Open, In Progress, Resolved, Closed']
resolved_at timestamp
created_at timestamp
updated_at timestamp
}

Table audit_logs {
id uuid [pk]
user_id uuid [ref: > users.id]
aksi varchar [note: 'Login, Delete Data, Verifikasi, Update, Create']
entity_type varchar
entity_id uuid
data_sebelum jsonb
data_sesudah jsonb
ip_address varchar
created_at timestamp
updated_at timestamp
}

Table evaluasi_instruktur {
id uuid [pk]
kelas_id uuid [ref: > kelas.id]
siswa_id uuid [ref: > siswa.id]
instruktur_id uuid [ref: > users.id]
skor_rata_rata numeric [note: 'Skala 1-5']
jawaban_kuesioner jsonb
saran_ulasan text
created_at timestamp
updated_at timestamp

Indexes {
(kelas_id, siswa_id) [unique, note: 'Siswa hanya bisa memberi 1 rating per kelas']
}
}
