# Product Requirements Document (PRD)
## Sistem Informasi Manajemen Sekolah Robotik
**Metodologi:** Scrum Framework  
**Mata Kuliah:** Rancang Bangun Perangkat Lunak — SI-D  
**Versi Dokumen:** 1.0

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Business Process Landscape](#2-business-process-landscape)
3. [Stakeholder](#3-stakeholder)
4. [Functional Requirements](#4-functional-requirements)
5. [Product Backlog](#5-product-backlog)
6. [Product Backlog Item (PBI) per Sprint](#6-product-backlog-item-pbi-per-sprint)
   - [Sprint 1 — Manajemen Akun & Akses](#sprint-1--manajemen-akun--akses-pb-01--target-8-sp)
   - [Sprint 2 — Landing Page](#sprint-2--landing-page-pb-02--target-8-sp)
   - [Sprint 3 — Manajemen Informasi Program](#sprint-3--manajemen-informasi-program-pb-03--target-5-sp)
   - [Sprint 4 — Pendaftaran Peserta](#sprint-4--pendaftaran-peserta-pb-04--target-13-sp)
   - [Sprint 5 — Manajemen Siswa](#sprint-5--manajemen-siswa-pb-05--target-13-sp)
   - [Sprint 6 — Manajemen Kelas & Jadwal Live Session](#sprint-6--manajemen-kelas--jadwal-live-session-pb-06--target-13-sp)
   - [Sprint 7 — Modul Pembelajaran & Penugasan](#sprint-7--modul-pembelajaran--penugasan-pb-07--target-13-sp)
   - [Sprint 8 — Pemantauan Progres Belajar](#sprint-8--pemantauan-progres-belajar-pb-08--target-8-sp)
   - [Sprint 9 — Manajemen Sertifikat](#sprint-9--manajemen-sertifikat-pb-09--target-5-sp)
   - [Sprint 10 — Laporan Manajemen](#sprint-10--laporan-manajemen-pb-10--target-5-sp)
   - [Sprint 11 — Manajemen Pembayaran](#sprint-11--manajemen-pembayaran-pb-11--target-13-sp)
   - [Sprint 12 — Manajemen Aset Robotik](#sprint-12--manajemen-aset-robotik-pb-12--target-13-sp)
   - [Sprint 13 — Diskusi, Mentoring & Keluhan](#sprint-13--diskusi-mentoring--manajemen-keluhan-pb-13--target-8-sp)
   - [Sprint 14 — Audit Log & Kinerja Instruktur](#sprint-14--audit-log--evaluasi-kinerja-pb-14--target-8-sp)
   - [Sprint 15 — Deployment & Demo Sistem](#sprint-15--deployment--demo-sistem-pb-15--target-5-sp)
7. [Ringkasan Sprint Plan](#7-ringkasan-sprint-plan)
8. [Pembagian Tim](#8-pembagian-tim)

---

## 1. Pendahuluan

### 1.1 Latar Belakang

Perkembangan teknologi robotika dan kecerdasan buatan (AI) mendorong tingginya kebutuhan masyarakat terhadap pendidikan di bidang ini, khususnya bagi pelajar SMA dan mahasiswa. Sekolah robotik hadir sebagai lembaga pendidikan non-formal yang menyediakan pelatihan robotika secara terstruktur — dari dasar elektronika dan mikrokontroler, pengembangan robot berbasis Arduino dan IoT, hingga persiapan kompetisi robot tingkat nasional maupun internasional.

Model pembelajaran online berbasis website menjadi solusi relevan karena memungkinkan peserta didik mengakses materi kapan saja dan dari mana saja. Platform yang baik menyediakan fitur pendaftaran digital, akses materi dan modul, sesi live session terjadwal bersama instruktur, pemantauan progres belajar per sesi, penugasan dan penilaian, hingga penerbitan sertifikat digital.

Namun pada kenyataannya, banyak lembaga sekolah robotik belum memiliki sistem manajemen terintegrasi. Pengelolaan pendaftaran masih manual, penjadwalan via grup chat, progres belajar dicatat terpisah, dan penerbitan sertifikat dilakukan secara ad-hoc. Kondisi ini menyulitkan instruktur dalam memantau perkembangan siswa, menghambat admin mengelola data, dan menurunkan kualitas pengalaman belajar.

Atas dasar tersebut, dikembangkanlah **Sistem Informasi Manajemen Sekolah Robotik** berbasis website yang mengintegrasikan seluruh proses operasional pembelajaran online secara terpusat. Pengembangan menggunakan **framework Scrum** sebagai pendekatan Agile, sehingga setiap fitur dapat dikembangkan, diuji, dan dievaluasi secara bertahap per sprint.

### 1.2 Tujuan Dokumen

Dokumen ini bertujuan untuk:
- Mendokumentasikan kebutuhan fungsional sistem secara menyeluruh
- Menyusun Product Backlog berdasarkan prioritas bisnis dan operasional lembaga
- Merinci setiap kebutuhan menjadi Product Backlog Item (PBI) yang akan dikerjakan dalam setiap sprint

### 1.3 Ruang Lingkup Sistem

Sistem mencakup modul-modul utama berikut:

| No | Modul | Deskripsi |
|----|-------|-----------|
| 1 | Autentikasi & Manajemen Akses Multi-Role | Login, logout, dan hak akses per peran (Admin, Instruktur, Siswa) |
| 2 | Halaman Publik & Informasi Program | Landing page tanpa login: info program, kurikulum, biaya, jadwal |
| 3 | Pendaftaran & Pengelolaan Data Siswa | Pendaftaran online beserta unggah dokumen persyaratan |
| 4 | Verifikasi & Aktivasi Akun Siswa | Verifikasi admin sebelum akun siswa diaktifkan |
| 5 | Manajemen Kelas & Jadwal Live Session | Pengelolaan kelas per level, jadwal sesi online, link akses sesi |
| 6 | Modul Pembelajaran & Penugasan | Akses materi per sesi, pengumpulan tugas, penilaian instruktur |
| 7 | Pemantauan Progres Belajar & Penilaian | Pencatatan kehadiran, nilai tugas, progres per sesi |
| 8 | Manajemen Sertifikat | Penerbitan dan pengelolaan sertifikat digital |
| 9 | Laporan & Dashboard Manajemen | Rekap data siswa, statistik kelulusan, laporan evaluasi |

Setiap modul dikembangkan dalam sprint terpisah dengan durasi **1,5 minggu per sprint**.

---

## 2. Business Process Landscape

Sistem Informasi Manajemen Sekolah Robotik Online distrukturkan dalam tiga lapisan proses bisnis utama:

### 2.1 Lapisan Proses Inti (Core Process)

Berfokus pada siklus utama aktivitas akademik siswa:

```
Pendaftaran Siswa → Kurikulum & Program Pembelajaran → Pembayaran →
Pembelajaran → Penugasan & Penilaian → Progress Akademik → Sertifikasi
```

Rangkaian ini merepresentasikan alur utama layanan pendidikan yang secara langsung menghasilkan nilai bisnis bagi institusi dan peserta didik.

### 2.2 Lapisan Proses Pendukung (Supporting Process)

Mendukung kelancaran proses inti agar berjalan efektif dan terintegrasi:

- Manajemen Pengguna & Hak Akses
- Manajemen Batch & Penjadwalan
- Manajemen Aset Robotik
- Manajemen Konten & File Pembelajaran
- Diskusi, Mentoring, dan Dukungan
- Manajemen Keluhan & Eskalasi
- Audit & Log Aktivitas

### 2.3 Lapisan Proses Manajemen (Management Process)

Menyediakan fungsi pengawasan dan evaluasi untuk mendukung pengambilan keputusan strategis:

- Manajemen Laporan Akademik
- Manajemen Kinerja Instruktur
- Manajemen Laporan Operasional
- Manajemen Laporan Aset & Pengadaan
- Monitoring Strategis

> Secara keseluruhan, sistem ini tidak hanya berfungsi sebagai **Learning Management System (LMS)**, tetapi juga sebagai **platform enterprise pendidikan robotik** yang mengintegrasikan proses akademik, operasional, pengelolaan aset robotik, serta fungsi manajerial dalam satu ekosistem sistem informasi terpadu.

---

## 3. Stakeholder

| No | Stakeholder | Peran dalam Sistem |
|----|-------------|-------------------|
| 1 | Direktur / Pemilik Lembaga | Pengambil keputusan strategis dan penerima laporan akhir hasil pengelolaan program |
| 2 | Admin | Mengelola operasional harian sistem: verifikasi pendaftaran, manajemen data siswa, kelas, dan sertifikat |
| 3 | Instruktur | Mengelola materi dan penugasan kelas, menilai tugas siswa, serta memantau progres belajar |
| 4 | Siswa | Pengguna akhir yang mendaftar program, mengakses materi dan sesi live, mengumpulkan tugas, serta memantau progres dan sertifikat |
| 5 | Tim Publikasi | Mengelola konten halaman publik, informasi program, dan materi promosi |
| 6 | Product Owner | Bertanggung jawab terhadap pengelolaan dan prioritas Product Backlog selama proses pengembangan |

---

## 4. Functional Requirements

### 4.1 Deskripsi Umum

Kebutuhan fungsional menjelaskan perilaku dan layanan yang harus disediakan oleh sistem. Setiap kebutuhan didefinisikan menggunakan ID unik, modul terkait, serta deskripsi fungsi yang harus dipenuhi. Daftar ini menjadi dasar penyusunan Product Backlog dan penentuan prioritas pengembangan pada setiap sprint.

### 4.2 Daftar Kebutuhan Fungsional

| ID | Modul | Deskripsi Kebutuhan Fungsional |
|----|-------|-------------------------------|
| FR-01 | Manajemen Akun | Admin mengelola data akun seluruh pengguna dalam sistem (menambah, melihat, memperbarui, dan menghapus akun Instruktur dan Siswa). |
| FR-02 | Manajemen Akses | Seluruh pengguna (Admin, Instruktur, Siswa, Tim Publikasi, dan Direktur) login menggunakan email dan password untuk mengakses sistem sesuai perannya. |
| FR-03 | Manajemen Akses | Setiap pengguna hanya dapat mengakses fitur sesuai dengan hak akses yang dimiliki berdasarkan perannya masing-masing. |
| FR-04 | Manajemen Akses | Pengguna dapat keluar dari sistem melalui fitur logout. |
| FR-05 | Landing Page | Calon peserta melihat informasi program kursus robotik yang tersedia, mencakup deskripsi kurikulum, biaya, dan fasilitas pembelajaran online. |
| FR-06 | Landing Page | Calon peserta melihat jadwal program yang sedang atau akan dibuka untuk pendaftaran. |
| FR-07 | Landing Page | Calon peserta memilih menu pendaftaran atau login melalui halaman utama website. |
| FR-08 | Manajemen Informasi Program | Tim Publikasi mengunggah dan memperbarui informasi program kursus di halaman publik, termasuk deskripsi, kurikulum, biaya, dan media promosi. |
| FR-09 | Manajemen Informasi Program | Tim Publikasi mengelola status tampil atau sembunyikan konten informasi program di halaman publik. |
| FR-10 | Pendaftaran Peserta | Calon peserta mengisi formulir pendaftaran program kursus secara online melalui website. |
| FR-11 | Pendaftaran Peserta | Calon peserta mengunggah dokumen persyaratan pendaftaran yang dibutuhkan melalui sistem. |
| FR-12 | Manajemen Siswa | Admin menerima dan melihat daftar data pendaftaran masuk dari calon peserta untuk dilakukan verifikasi. |
| FR-13 | Manajemen Siswa | Admin menyetujui pendaftaran calon peserta setelah dokumen dinyatakan valid dan lengkap. |
| FR-14 | Manajemen Siswa | Admin mengirimkan notifikasi revisi kepada calon peserta apabila dokumen tidak memenuhi persyaratan yang ditentukan. |
| FR-15 | Manajemen Siswa | Admin membuat akun login bagi calon peserta yang pendaftarannya telah disetujui. |
| FR-16 | Manajemen Siswa | Calon peserta yang menerima notifikasi revisi dapat memperbarui data dan mengunggah ulang dokumen melalui formulir yang tersedia. |
| FR-17 | Manajemen Kelas dan Jadwal | Admin mengelola data kelas per level program kursus (membuat, memperbarui, dan menghapus data kelas). |
| FR-18 | Manajemen Kelas dan Jadwal | Admin menjadwalkan sesi live session per kelas beserta informasi link akses dan waktu pelaksanaan. |
| FR-19 | Manajemen Kelas dan Jadwal | Instruktur melihat jadwal sesi live session dan daftar siswa yang terdaftar di kelasnya. |
| FR-20 | Manajemen Kelas dan Jadwal | Siswa melihat jadwal sesi live session beserta link akses untuk kelas yang diikutinya. |
| FR-21 | Modul Pembelajaran dan Penugasan | Instruktur mengunggah materi pembelajaran (modul, video, atau dokumen pendukung) untuk setiap sesi kelas. |
| FR-22 | Modul Pembelajaran dan Penugasan | Siswa mengakses dan mengunduh materi pembelajaran yang telah diunggah oleh instruktur sesuai sesi kelasnya. |
| FR-23 | Modul Pembelajaran dan Penugasan | Instruktur membuat dan mengelola tugas untuk setiap sesi kelas beserta batas waktu pengumpulan. |
| FR-24 | Modul Pembelajaran dan Penugasan | Siswa mengumpulkan hasil tugas secara online melalui sistem sesuai sesi yang ditentukan. |
| FR-25 | Modul Pembelajaran dan Penugasan | Instruktur menilai tugas yang dikumpulkan siswa dan memberikan umpan balik melalui sistem. |
| FR-26 | Pemantauan Progres Belajar | Admin atau instruktur mencatat kehadiran siswa pada setiap sesi live session yang berlangsung. |
| FR-27 | Pemantauan Progres Belajar | Siswa dapat memantau progres belajar pribadi, meliputi nilai tugas, rekap kehadiran, dan persentase penyelesaian program secara real-time. |
| FR-28 | Pemantauan Progres Belajar | Instruktur dapat memantau rekap progres belajar seluruh siswa dalam satu kelas, termasuk nilai dan kehadiran. |
| FR-29 | Manajemen Sertifikat | Admin menerbitkan sertifikat digital bagi siswa yang telah menyelesaikan seluruh sesi program dan memenuhi syarat kelulusan yang ditetapkan. |
| FR-30 | Manajemen Sertifikat | Siswa dapat melihat dan mengunduh sertifikat digital mereka melalui akun di sistem. |
| FR-31 | Laporan Manajemen | Direktur mengakses dashboard dan laporan statistik yang mencakup data siswa aktif, tingkat kelulusan, dan rekap hasil per program kursus. |
| FR-32 | Laporan Manajemen | Admin menyusun dan menyimpan laporan operasional program untuk kebutuhan evaluasi dan arsip lembaga. |
| FR-33 | Laporan Manajemen | Instruktur melihat laporan hasil evaluasi belajar siswa per kelas sebagai bahan penilaian dan pelaporan akhir program. |
| FR-34 | Manajemen Pembayaran | Sistem secara otomatis menerbitkan tagihan (invoice) ketika admin menyetujui pendaftaran calon peserta. |
| FR-35 | Manajemen Pembayaran | Calon peserta dapat mengunggah bukti pembayaran melalui sistem. |
| FR-36 | Manajemen Pembayaran | Admin memverifikasi bukti pembayaran dan sistem otomatis mengaktifkan akun siswa setelah pembayaran dinyatakan valid. |
| FR-37 | Manajemen Aset Robotik | Admin mengelola inventaris kit robotik (menambah, mengedit, menghapus data aset dan stok). |
| FR-38 | Manajemen Aset Robotik | Instruktur dan siswa dapat mengajukan permohonan peminjaman kit robotik untuk keperluan kelas/tugas. |
| FR-39 | Manajemen Aset Robotik | Admin menyetujui peminjaman, mencatat pengembalian, dan memperbarui status kondisi aset. |
| FR-40 | Diskusi & Mentoring | Siswa dan instruktur dapat berinteraksi melalui forum diskusi asinkronus yang terikat pada masing-masing kelas. |
| FR-41 | Keluhan & Eskalasi | Pengguna (Siswa/Instruktur) dapat membuat tiket keluhan (kendala sistem atau akademik) untuk diselesaikan oleh Admin. |
| FR-42 | Audit & Log Sistem | Sistem mencatat (logging) secara otomatis setiap aktivitas pengguna yang bersifat krusial (login, perubahan data, persetujuan). |
| FR-43 | Manajemen Kinerja | Siswa dapat mengisi kuesioner evaluasi kinerja instruktur pada akhir program. |
| FR-44 | Laporan Manajemen | Direktur dan Admin dapat melihat rekapitulasi laporan aset robotik dan penilaian kinerja instruktur. |

---

## 5. Product Backlog

### 5.1 Prinsip Penyusunan

Product Backlog disusun berdasarkan prinsip Scrum. Setiap item direpresentasikan dalam bentuk **User Story** dengan format:

> *"Sebagai [peran], saya ingin [fitur] agar [manfaat]."*

Estimasi kompleksitas menggunakan **Story Point** dengan skala Fibonacci (1, 2, 3, 5, 8, 13, 21). Prinsip yang diterapkan adalah **1 Sprint = 1 Product Backlog** agar setiap modul dapat dikembangkan, diuji, dan dievaluasi secara mandiri dan menyeluruh.

### 5.2 Daftar Product Backlog

| ID | Modul | User Story | Prioritas | Story Point | Referensi FR |
|----|-------|------------|-----------|-------------|--------------|
| PB-01 | Manajemen Akun & Akses | Sebagai admin, saya ingin mengelola data akun seluruh pengguna dan mengatur hak akses tiap peran agar setiap pengguna dapat mengakses sistem sesuai kewenangannya dengan aman. | Tinggi | 8 | FR-01, FR-02, FR-03, FR-04 |
| PB-02 | Landing Page | Sebagai calon peserta, saya ingin melihat informasi lengkap program kursus robotik, jadwal pendaftaran, biaya, dan fasilitas pembelajaran online agar dapat mempertimbangkan keikutsertaan sebelum mendaftar. | Tinggi | 8 | FR-05, FR-06, FR-07 |
| PB-03 | Manajemen Informasi Program | Sebagai tim publikasi, saya ingin mengelola dan memperbarui konten informasi program di halaman publik agar informasi yang ditampilkan selalu akurat dan relevan bagi calon peserta. | Sedang | 5 | FR-08, FR-09 |
| PB-04 | Pendaftaran Peserta | Sebagai calon peserta, saya ingin mengisi formulir pendaftaran dan mengunggah dokumen persyaratan secara online agar dapat mengikuti proses seleksi program kursus robotik tanpa harus datang langsung ke lembaga. | Tinggi | 13 | FR-10, FR-11 |
| PB-05 | Manajemen Siswa | Sebagai admin, saya ingin memverifikasi dokumen pendaftaran, menyetujui atau mengirim notifikasi revisi, serta membuat akun siswa agar proses administrasi penerimaan peserta berjalan tertib dan terverifikasi secara digital. | Tinggi | 13 | FR-12, FR-13, FR-14, FR-15, FR-16 |
| PB-06 | Manajemen Kelas & Jadwal | Sebagai admin dan instruktur, saya ingin mengelola data kelas per level dan menjadwalkan sesi live session agar pelaksanaan pembelajaran online dapat berlangsung terstruktur dan mudah diakses oleh siswa. | Tinggi | 13 | FR-17, FR-18, FR-19, FR-20 |
| PB-07 | Modul Pembelajaran & Penugasan | Sebagai instruktur dan siswa, saya ingin mengelola serta mengakses materi pembelajaran dan tugas per sesi kelas agar proses belajar mengajar online dapat berjalan efektif dan seluruh aktivitas terdokumentasi dengan baik. | Tinggi | 13 | FR-21, FR-22, FR-23, FR-24, FR-25 |
| PB-08 | Pemantauan Progres Belajar | Sebagai siswa dan instruktur, saya ingin memantau rekap kehadiran, nilai tugas, dan persentase penyelesaian program agar perkembangan belajar setiap siswa dapat dipantau secara real-time dan transparan. | Sedang | 8 | FR-26, FR-27, FR-28 |
| PB-09 | Manajemen Sertifikat | Sebagai admin dan siswa, saya ingin menerbitkan dan mengakses sertifikat digital kelulusan agar pencapaian siswa yang telah menyelesaikan program dapat diakui secara resmi dan mudah diunduh kapan saja. | Sedang | 5 | FR-29, FR-30 |
| PB-10 | Laporan Manajemen | Sebagai direktur, admin, dan instruktur, saya ingin mengakses laporan dan dashboard statistik hasil program agar evaluasi operasional lembaga dapat dilakukan secara menyeluruh dan berbasis data. | Sedang | 5 | FR-31, FR-32, FR-33 |
| PB-11 | Manajemen Pembayaran | Sebagai admin dan calon peserta, saya ingin mengelola proses penagihan dan verifikasi bukti pembayaran agar transaksi keuangan terekam dan akun dapat diaktifkan secara sah. | Tinggi | 13 | FR-34, FR-35, FR-36 |
| PB-12 | Manajemen Aset Robotik | Sebagai admin, instruktur, dan siswa, saya ingin mengelola inventaris dan sirkulasi peminjaman kit robotik agar alat praktikum terdistribusi dan terlacak dengan baik. | Tinggi | 13 | FR-37, FR-38, FR-39 |
| PB-13 | Diskusi, Mentoring & Keluhan | Sebagai pengguna sistem, saya ingin berinteraksi di forum kelas dan mengajukan tiket keluhan agar kendala belajar maupun teknis dapat segera diatasi. | Sedang | 8 | FR-40, FR-41 |
| PB-14 | Audit Log & Kinerja Instruktur | Sebagai direktur dan admin, saya ingin sistem memantau log aktivitas dan merekap evaluasi instruktur agar keamanan sistem dan kualitas pengajaran tetap terjaga. | Sedang | 8 | FR-42, FR-43, FR-44 |
| PB-15 | Deployment & Demo | Sebagai developer, saya ingin melakukan deployment sistem ke server dan otomasi siklus development serta deployment. | Tinggi | 5 | Non-Fungsional |

**Total Story Point: 138 SP**  
**Total Durasi: 15 Sprint × 1,5 minggu = ±22,5 minggu**

---

## 6. Product Backlog Item (PBI) per Sprint

### Prinsip Penyusunan PBI

Product Backlog Item (PBI) merupakan perincian lebih lanjut dari setiap Product Backlog ke dalam task dan sub-task teknis yang dapat dikerjakan dalam satu sprint. Setiap PBI disusun berdasarkan prinsip **INVEST** (Independent, Negotiable, Valuable, Estimable, Small, Testable).

**Kategori tipe task:**
- **Task** — implementasi teknis
- **Testing** — pengujian fungsional
- **Design** — perancangan antarmuka dan skema data

---

### Sprint 1 — Manajemen Akun & Akses (PB-01 — Target: 8 SP)

> **Sprint Goal:** Membangun mekanisme autentikasi pengguna meliputi login, logout, serta pengaturan hak akses berbasis peran (role-based access control) agar setiap pengguna sistem dapat mengakses fitur yang sesuai dengan kewenangannya secara aman.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-001 | Analisis kebutuhan data pengguna dan identifikasi peran sistem (Admin, Instruktur, Siswa, Tim Publikasi, Direktur) | Task | 1 | To Do |
| PBI-002 | Perancangan skema database tabel `roles` (id, nama_role, deskripsi) | Design | 1 | To Do |
| PBI-003 | Perancangan skema database tabel `users` (id, nama_lengkap, email, password, role_id, status_aktif, created_at) | Design | 1 | To Do |
| PBI-004 | Implementasi tabel `roles` pada database beserta data awal untuk 5 peran | Task | 1 | To Do |
| PBI-005 | Implementasi tabel `users` pada database beserta relasi foreign key ke tabel `roles` | Task | 1 | To Do |
| PBI-006 | Implementasi fitur tambah akun pengguna baru oleh admin: form input nama, email, pilih peran, dan password | Task | 2 | To Do |
| PBI-007 | Implementasi fitur lihat daftar seluruh akun pengguna dalam tabel beserta kolom nama, email, peran, dan status | Task | 1 | To Do |
| PBI-008 | Implementasi fitur edit data akun pengguna oleh admin: ubah nama, email, peran, dan password | Task | 1 | To Do |
| PBI-009 | Implementasi fitur nonaktifkan atau hapus akun pengguna oleh admin | Task | 1 | To Do |
| PBI-010 | Implementasi fitur login menggunakan email dan password dengan validasi kredensial dan manajemen session | Task | 2 | To Do |
| PBI-011 | Implementasi pengalihan otomatis ke halaman dashboard sesuai peran masing-masing setelah login berhasil | Task | 1 | To Do |
| PBI-012 | Implementasi pengecekan session: pengguna yang belum login dialihkan ke halaman login saat mencoba mengakses halaman yang memerlukan autentikasi | Task | 1 | To Do |
| PBI-013 | Implementasi fitur logout yang menghapus session dan mengarahkan pengguna kembali ke halaman login | Task | 1 | To Do |
| PBI-014 | Implementasi tampilan halaman login: form email dan password beserta pesan error jika kredensial salah | Design | 1 | To Do |
| PBI-015 | Implementasi tampilan halaman manajemen akun di panel admin: tabel daftar pengguna dan tombol tambah, edit, hapus | Design | 1 | To Do |
| PBI-016 | Pengujian fungsional modul autentikasi: login dengan data valid, login dengan data salah, akses halaman tanpa login, dan logout | Testing | 1 | To Do |
| PBI-017 | Pengujian fungsional hak akses: pastikan setiap peran hanya dapat membuka halaman yang diizinkan dan tidak bisa mengakses halaman peran lain | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 2 — Landing Page (PB-02 — Target: 8 SP)

> **Sprint Goal:** Mengembangkan halaman publik (landing page) yang informatif dan menarik bagi calon peserta agar mereka dapat memahami program kursus robotik yang tersedia, melihat jadwal pendaftaran, fasilitas pembelajaran online, dan mengakses tombol daftar atau login dengan mudah.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-018 | Analisis kebutuhan konten landing page: daftar informasi yang perlu ditampilkan dan susunan section halaman | Task | 1 | Done |
| PBI-019 | Perancangan layout landing page: sketsa susunan section (hero, tentang, program, fasilitas, jadwal, footer) | Design | 1 | To Do |
| PBI-020 | Implementasi section hero: judul utama, deskripsi singkat, tombol "Daftar Sekarang" dan "Masuk", serta gambar ilustrasi | Task | 2 | To Do |
| PBI-021 | Implementasi section "Tentang Sekolah Robotik": deskripsi singkat lembaga dan keunggulan program | Task | 1 | To Do |
| PBI-022 | Implementasi section daftar program kursus: tampilkan kartu per program berisi nama, level, durasi, biaya, dan tombol "Lihat Detail" | Task | 2 | To Do |
| PBI-023 | Implementasi halaman detail program kursus: deskripsi program, daftar materi yang dipelajari, syarat peserta, dan biaya | Task | 2 | To Do |
| PBI-024 | Implementasi section jadwal pendaftaran: tampilkan status pendaftaran (buka/tutup), tanggal buka dan tutup, serta sisa kuota | Task | 1 | To Do |
| PBI-025 | Implementasi section fasilitas pembelajaran: daftar fasilitas yang didapat peserta (live session, materi digital, tugas, sertifikat) | Task | 1 | To Do |
| PBI-026 | Implementasi komponen navbar: logo, menu navigasi antar section, dan tombol login | Task | 1 | To Do |
| PBI-027 | Implementasi komponen footer: informasi kontak lembaga dan tautan media sosial | Task | 1 | To Do |
| PBI-028 | Pengujian fungsional landing page: semua section tampil dengan benar, link navigasi berfungsi, dan tombol mengarah ke halaman yang tepat | Testing | 1 | To Do |
| PBI-029 | Pengujian tampilan landing page pada ukuran layar desktop dan mobile | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 3 — Manajemen Informasi Program (PB-03 — Target: 5 SP)

> **Sprint Goal:** Mengembangkan fitur bagi tim publikasi untuk mengelola konten program kursus dan media promosi yang ditampilkan di halaman publik, sehingga informasi yang tersedia selalu akurat, relevan, dan dapat diperbarui secara mandiri tanpa campur tangan developer.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-030 | Analisis kebutuhan data konten program: identifikasi field yang perlu dikelola (nama, deskripsi, level, biaya, durasi, gambar, status) | Task | 1 | Done |
| PBI-031 | Perancangan skema database tabel `program_kursus` (id, nama_program, deskripsi, level, biaya, durasi_minggu, gambar, status_tampil, created_at) | Design | 1 | Done |
| PBI-032 | Perancangan skema database tabel `materi_program` (id, program_id, nomor_urut, judul_materi, deskripsi_materi) untuk menyimpan daftar materi per program | Design | 1 | Done |
| PBI-033 | Implementasi tabel `program_kursus` dan `materi_program` pada database | Task | 1 | Done |
| PBI-034 | Implementasi fitur tambah program kursus: form input nama, level, deskripsi, biaya, durasi, unggah gambar, dan daftar materi yang dipelajari | Task | 2 | Done |
| PBI-035 | Implementasi fitur edit informasi program kursus yang sudah ada | Task | 1 | Done |
| PBI-036 | Implementasi fitur hapus program kursus beserta konfirmasi tindakan | Task | 1 | Done |
| PBI-037 | Implementasi fitur aktifkan atau nonaktifkan tampil program kursus di halaman publik melalui tombol toggle | Task | 1 | Done |
| PBI-038 | Implementasi fitur unggah dan ganti gambar promosi program kursus | Task | 1 | Done |
| PBI-039 | Implementasi tampilan dashboard tim publikasi: tabel daftar program yang dikelola beserta status tampil dan tombol aksi | Design | 1 | Done |
| PBI-040 | Pengujian fungsional modul manajemen program: tambah, edit, hapus program, unggah gambar, dan toggle status tampil | Testing | 1 | Done |
| PBI-041 | Pengujian integrasi: pastikan perubahan data program langsung tercermin di halaman landing page | Testing | 1 | Done |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 4 — Pendaftaran Peserta (PB-04 — Target: 13 SP)

> **Sprint Goal:** Mengembangkan fitur pendaftaran program kursus secara online yang memungkinkan calon peserta mengisi formulir pendaftaran, mengunggah dokumen persyaratan, mendapatkan konfirmasi pendaftaran, dan memantau status pendaftarannya melalui sistem.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-042 | Analisis kebutuhan data pendaftaran: identifikasi field formulir, jenis dokumen yang diperlukan, dan alur status (menunggu, revisi, diterima, ditolak) | Task | 1 | To Do |
| PBI-043 | Perancangan skema database tabel `calon_peserta` (id, nama_lengkap, email, no_hp, asal_sekolah_atau_instansi, jenjang_pendidikan, created_at) | Design | 1 | To Do |
| PBI-044 | Perancangan skema database tabel `pendaftaran` (id, calon_peserta_id, program_id, no_referensi, tanggal_daftar, status, catatan_admin, created_at) | Design | 1 | To Do |
| PBI-045 | Perancangan skema database tabel `dokumen_pendaftaran` (id, pendaftaran_id, jenis_dokumen, nama_file, file_path, status_verifikasi, catatan, uploaded_at) | Design | 1 | To Do |
| PBI-046 | Implementasi seluruh tabel pendaftaran pada database beserta relasi antar tabel | Task | 1 | To Do |
| PBI-047 | Implementasi halaman formulir pendaftaran: form satu halaman berisi data diri (nama, email, nomor HP, asal sekolah/instansi, jenjang pendidikan) dan pilihan program kursus | Task | 2 | To Do |
| PBI-048 | Implementasi validasi formulir pendaftaran: semua field wajib diisi, format email benar, dan format nomor HP benar | Task | 1 | To Do |
| PBI-049 | Implementasi fitur unggah dokumen persyaratan pada halaman pendaftaran: KTP atau Kartu Pelajar dan pas foto, dengan validasi format file (jpg, png, pdf) dan ukuran maksimum | Task | 2 | To Do |
| PBI-050 | Implementasi generate nomor referensi pendaftaran unik secara otomatis setelah formulir berhasil dikirim | Task | 1 | To Do |
| PBI-051 | Implementasi halaman konfirmasi setelah pendaftaran berhasil dikirim: tampilkan nomor referensi dan instruksi menunggu verifikasi admin | Task | 1 | To Do |
| PBI-052 | Implementasi halaman cek status pendaftaran: calon peserta memasukkan nomor referensi untuk melihat status terkini dan catatan dari admin | Task | 2 | To Do |
| PBI-053 | Implementasi fitur unggah ulang dokumen oleh calon peserta saat status pendaftaran berubah menjadi "Revisi" | Task | 2 | To Do |
| PBI-054 | Implementasi desain halaman formulir pendaftaran yang rapi dan mudah digunakan | Design | 1 | To Do |
| PBI-055 | Pengujian fungsional formulir pendaftaran: submit data lengkap, validasi field kosong, validasi format file, dan tampil nomor referensi | Testing | 1 | To Do |
| PBI-056 | Pengujian fungsional halaman cek status: pencarian berdasarkan nomor referensi dan email, serta tampil catatan revisi dari admin | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 5 — Manajemen Siswa (PB-05 — Target: 13 SP)

> **Sprint Goal:** Mengembangkan fitur bagi admin untuk memverifikasi dokumen pendaftaran, menyetujui atau menolak pendaftaran, mengirimkan notifikasi revisi kepada calon peserta, membuat akun login siswa, serta mengelola data seluruh siswa aktif di dalam sistem.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-057 | Analisis alur kerja verifikasi pendaftaran oleh admin: tahapan review dokumen dan keputusan (setujui, minta revisi, atau tolak) | Task | 1 | To Do |
| PBI-058 | Perancangan skema database tabel `siswa` (id, user_id, pendaftaran_id, nama_lengkap, tanggal_lahir, jenis_kelamin, alamat, no_hp, foto_profil, created_at) | Design | 1 | To Do |
| PBI-059 | Implementasi tabel `siswa` pada database beserta relasi ke tabel `users` dan `pendaftaran` | Task | 1 | To Do |
| PBI-060 | Implementasi halaman daftar pendaftaran masuk di panel admin: tabel berisi nama calon peserta, program yang dipilih, tanggal daftar, dan status terkini | Task | 2 | To Do |
| PBI-061 | Implementasi fitur filter dan pencarian data pendaftaran berdasarkan nama, program yang dipilih, dan status | Task | 1 | To Do |
| PBI-062 | Implementasi halaman detail pendaftaran: admin melihat data lengkap calon peserta dan pratinjau setiap dokumen yang diunggah | Task | 2 | To Do |
| PBI-063 | Implementasi fitur verifikasi dokumen: admin menandai setiap dokumen sebagai "Valid" atau "Tidak Valid" beserta catatan | Task | 2 | To Do |
| PBI-064 | Implementasi fitur setujui pendaftaran: admin mengubah status menjadi "Diterima" setelah semua dokumen valid | Task | 1 | To Do |
| PBI-065 | Implementasi fitur tolak pendaftaran: admin mengubah status menjadi "Ditolak" beserta pengisian alasan penolakan | Task | 1 | To Do |
| PBI-066 | Implementasi fitur kirim catatan revisi: admin memilih dokumen yang bermasalah, mengisi catatan, dan menyimpan sebagai status "Revisi" yang dapat dilihat calon peserta di halaman cek status | Task | 2 | To Do |
| PBI-067 | Implementasi fitur buat akun siswa oleh admin setelah pendaftaran disetujui: input username dan password awal, lalu akun tersimpan dan terhubung ke data siswa | Task | 2 | To Do |
| PBI-068 | Implementasi halaman daftar siswa aktif di panel admin: tabel berisi nama, email, program yang diikuti, tanggal bergabung, dan status akun | Task | 1 | To Do |
| PBI-069 | Implementasi fitur filter dan pencarian data siswa aktif berdasarkan nama dan program | Task | 1 | To Do |
| PBI-070 | Implementasi fitur edit data profil siswa oleh admin | Task | 1 | To Do |
| PBI-071 | Implementasi fitur nonaktifkan akun siswa oleh admin | Task | 1 | To Do |
| PBI-072 | Implementasi halaman profil siswa setelah login: tampil data diri, program yang diikuti, dan status pendaftaran | Design | 1 | To Do |
| PBI-073 | Pengujian fungsional alur verifikasi pendaftaran: verifikasi dokumen, persetujuan, penolakan, dan pengiriman catatan revisi | Testing | 1 | To Do |
| PBI-074 | Pengujian fungsional pembuatan akun siswa dan pengelolaan data siswa aktif | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 6 — Manajemen Kelas & Jadwal Live Session (PB-06 — Target: 13 SP)

> **Sprint Goal:** Mengembangkan fitur pengelolaan kelas per level program dan penjadwalan sesi live session agar admin dapat mengatur pelaksanaan pembelajaran online secara terstruktur, serta instruktur dan siswa dapat mengakses informasi jadwal dan link sesi dengan mudah melalui dashboard masing-masing.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-075 | Analisis kebutuhan data kelas dan sesi live: atribut kelas (nama, level, kapasitas, instruktur) dan atribut sesi (judul, tanggal, jam, platform, link) | Task | 1 | To Do |
| PBI-076 | Perancangan skema database tabel `kelas` (id, program_id, nama_kelas, level, instruktur_id, kapasitas, status, created_at) | Design | 1 | To Do |
| PBI-077 | Perancangan skema database tabel `sesi_live` (id, kelas_id, nomor_sesi, judul_sesi, tanggal, jam_mulai, jam_selesai, platform, link_akses, keterangan) | Design | 1 | To Do |
| PBI-078 | Perancangan skema database tabel `enrollment_kelas` (id, kelas_id, siswa_id, tanggal_bergabung) untuk mencatat siswa yang terdaftar di kelas tertentu | Design | 1 | To Do |
| PBI-079 | Implementasi seluruh tabel kelas, sesi live, dan enrollment pada database beserta relasi antar tabel | Task | 1 | To Do |
| PBI-080 | Implementasi fitur tambah kelas baru oleh admin: form input nama kelas, pilih program, level, assign instruktur, dan kapasitas maksimum | Task | 2 | To Do |
| PBI-081 | Implementasi fitur edit dan hapus data kelas oleh admin | Task | 1 | To Do |
| PBI-082 | Implementasi fitur daftarkan siswa ke kelas oleh admin: pilih siswa dari daftar yang tersedia dan validasi kapasitas kelas | Task | 2 | To Do |
| PBI-083 | Implementasi fitur lihat daftar siswa yang terdaftar di setiap kelas beserta tombol hapus dari kelas | Task | 1 | To Do |
| PBI-084 | Implementasi fitur tambah jadwal sesi live session oleh admin: form input nomor sesi, judul, tanggal, jam mulai dan selesai, nama platform, dan link akses | Task | 2 | To Do |
| PBI-085 | Implementasi fitur edit dan hapus jadwal sesi live session | Task | 1 | To Do |
| PBI-086 | Implementasi tampilan jadwal sesi live session di dashboard instruktur: tabel daftar sesi mendatang berisi judul, tanggal, jam, platform, link, dan jumlah siswa di kelas | Task | 2 | To Do |
| PBI-087 | Implementasi tampilan jadwal sesi live session di dashboard siswa: tabel daftar sesi mendatang dan riwayat sesi yang sudah berlangsung, beserta link akses yang dapat diklik | Task | 2 | To Do |
| PBI-088 | Implementasi fitur lihat daftar seluruh kelas dan sesi di panel admin dengan filter berdasarkan program dan instruktur | Task | 1 | To Do |
| PBI-089 | Pengujian fungsional modul kelas: tambah, edit, hapus kelas, dan proses daftarkan siswa ke kelas | Testing | 1 | To Do |
| PBI-090 | Pengujian fungsional jadwal sesi: tambah, edit, hapus sesi, dan tampilan jadwal di dashboard instruktur serta siswa | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 7 — Modul Pembelajaran & Penugasan (PB-07 — Target: 13 SP)

> **Sprint Goal:** Mengembangkan fitur pengelolaan materi pembelajaran per sesi dan sistem penugasan online agar instruktur dapat mengunggah konten belajar serta membuat tugas, sementara siswa dapat mengakses materi, mengumpulkan tugas, dan melihat hasil penilaian beserta umpan balik dari instruktur.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-091 | Analisis kebutuhan data materi dan penugasan: jenis file materi yang didukung, atribut tugas, dan alur pengumpulan serta penilaian | Task | 1 | To Do |
| PBI-092 | Perancangan skema database tabel `materi_pembelajaran` (id, sesi_id, judul, tipe_konten, file_path_atau_url, urutan, keterangan, created_at) | Design | 1 | To Do |
| PBI-093 | Perancangan skema database tabel `tugas` (id, sesi_id, judul_tugas, deskripsi, file_soal, batas_waktu, nilai_maksimum, created_at) | Design | 1 | To Do |
| PBI-094 | Perancangan skema database tabel `pengumpulan_tugas` (id, tugas_id, siswa_id, file_jawaban, catatan_siswa, waktu_kumpul, nilai, umpan_balik, status_penilaian) | Design | 1 | To Do |
| PBI-095 | Implementasi seluruh tabel materi dan penugasan pada database beserta relasi antar tabel | Task | 1 | To Do |
| PBI-096 | Implementasi fitur unggah materi per sesi oleh instruktur: form input judul, nomor urut, keterangan, dan unggah file (PDF, gambar, atau dokumen Word) | Task | 2 | To Do |
| PBI-097 | Implementasi fitur tambah materi berupa link tautan (misalnya link video YouTube atau Google Drive) sebagai alternatif selain unggah file | Task | 1 | To Do |
| PBI-098 | Implementasi fitur edit dan hapus materi pembelajaran oleh instruktur | Task | 1 | To Do |
| PBI-099 | Implementasi tampilan halaman materi per sesi untuk siswa: daftar materi yang tersedia, keterangan singkat, dan tombol unduh atau buka tautan | Task | 2 | To Do |
| PBI-100 | Implementasi fitur tambah tugas per sesi oleh instruktur: form input judul, deskripsi, unggah file soal opsional, batas waktu pengumpulan, dan nilai maksimum | Task | 2 | To Do |
| PBI-101 | Implementasi fitur edit dan hapus tugas oleh instruktur | Task | 1 | To Do |
| PBI-102 | Implementasi tampilan daftar tugas di dashboard siswa: nama tugas, batas waktu, dan status pengumpulan (belum dikumpulkan atau sudah dikumpulkan) | Task | 1 | To Do |
| PBI-103 | Implementasi fitur kumpulkan tugas oleh siswa: unggah file jawaban, isi catatan opsional, dan konfirmasi pengumpulan | Task | 2 | To Do |
| PBI-104 | Implementasi validasi pengumpulan tugas: siswa tidak dapat mengumpulkan jika sudah melewati batas waktu, dan validasi format file | Task | 1 | To Do |
| PBI-105 | Implementasi tampilan daftar pengumpulan tugas per tugas di panel instruktur: nama siswa, waktu pengumpulan, dan status penilaian | Task | 1 | To Do |
| PBI-106 | Implementasi fitur nilai tugas oleh instruktur: buka file jawaban siswa, input nilai numerik, tulis umpan balik, dan simpan penilaian | Task | 2 | To Do |
| PBI-107 | Implementasi tampilan hasil penilaian di dashboard siswa: nilai yang diperoleh dan umpan balik dari instruktur | Task | 1 | To Do |
| PBI-108 | Pengujian fungsional modul materi: unggah file, tambah tautan, edit, hapus, dan akses materi oleh siswa | Testing | 1 | To Do |
| PBI-109 | Pengujian fungsional modul penugasan: buat tugas, kumpulkan tugas, validasi batas waktu, penilaian, dan tampil hasil | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 8 — Pemantauan Progres Belajar (PB-08 — Target: 8 SP)

> **Sprint Goal:** Mengembangkan fitur pencatatan kehadiran siswa per sesi live session dan perhitungan progres belajar secara otomatis, sehingga siswa dapat memantau perkembangan belajarnya secara real-time dan instruktur dapat memantau rekap progres seluruh siswa dalam kelasnya.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-110 | Analisis kebutuhan data progres belajar: parameter yang diukur (kehadiran dan nilai tugas) dan cara penghitungannya | Task | 1 | To Do |
| PBI-111 | Perancangan skema database tabel `kehadiran` (id, sesi_id, siswa_id, status_hadir, catatan, dicatat_oleh, waktu_pencatatan) | Design | 1 | To Do |
| PBI-112 | Implementasi tabel `kehadiran` pada database beserta relasi ke tabel sesi dan siswa | Task | 1 | To Do |
| PBI-113 | Implementasi halaman absensi per sesi oleh instruktur atau admin: tampilkan daftar siswa di kelas beserta pilihan status (Hadir, Tidak Hadir, Izin) dan tombol simpan | Task | 2 | To Do |
| PBI-114 | Implementasi fitur edit kehadiran siswa yang sudah dicatat jika terjadi kesalahan | Task | 1 | To Do |
| PBI-115 | Implementasi tampilan rekap kehadiran per siswa per kelas: tabel berisi setiap sesi beserta status kehadiran dan persentase kehadiran total | Task | 2 | To Do |
| PBI-116 | Implementasi tampilan halaman progres belajar di dashboard siswa: persentase kehadiran, rata-rata nilai tugas, dan tabel nilai per tugas | Task | 2 | To Do |
| PBI-117 | Implementasi tampilan rekap progres seluruh siswa per kelas di dashboard instruktur: tabel berisi nama siswa, persentase kehadiran, rata-rata nilai, dan jumlah tugas yang sudah dikumpulkan | Task | 2 | To Do |
| PBI-118 | Implementasi tampilan ringkasan progres kelas di panel admin: jumlah siswa aktif per kelas dan rata-rata kehadiran | Task | 1 | To Do |
| PBI-119 | Pengujian fungsional absensi: pencatatan hadir, tidak hadir, izin, dan edit kehadiran | Testing | 1 | To Do |
| PBI-120 | Pengujian fungsional halaman progres belajar: tampilan di dashboard siswa dan rekap di dashboard instruktur | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 9 — Manajemen Sertifikat (PB-09 — Target: 5 SP)

> **Sprint Goal:** Mengembangkan fitur penerbitan sertifikat digital secara otomatis bagi siswa yang telah memenuhi syarat kelulusan program, termasuk generate file PDF sertifikat dengan template resmi lembaga, mekanisme verifikasi keaslian sertifikat, serta akses unduhan oleh siswa melalui akun mereka.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-121 | Analisis kebutuhan sertifikat: definisi syarat kelulusan (minimal kehadiran dan nilai), informasi yang ditampilkan di sertifikat, dan alur penerbitan | Task | 1 | Done |
| PBI-122 | Perancangan skema database tabel `sertifikat` (id, siswa_id, program_id, kelas_id, nomor_sertifikat, tanggal_terbit, diterbitkan_oleh, created_at) | Design | 1 | Done |
| PBI-123 | Implementasi tabel `sertifikat` pada database beserta relasi ke tabel siswa, program, dan kelas | Task | 1 | Done |
| PBI-124 | Implementasi halaman daftar siswa yang memenuhi syarat kelulusan di panel admin: tampilkan nama, program, persentase kehadiran, dan rata-rata nilai sebagai acuan admin sebelum menerbitkan sertifikat | Task | 2 | Done |
| PBI-125 | Implementasi fitur terbitkan sertifikat oleh admin: pilih siswa yang akan diterbitkan, konfirmasi, dan simpan data sertifikat ke database | Task | 2 | Done |
| PBI-126 | Implementasi generate nomor sertifikat unik secara otomatis saat sertifikat diterbitkan, dengan format kode program, tahun, dan nomor urut | Task | 1 | Done |
| PBI-127 | Implementasi halaman sertifikat di dashboard siswa: tampilkan data sertifikat (nama siswa, program, tanggal terbit, nomor sertifikat) dalam tampilan yang rapi menggunakan HTML dan CSS | Task | 2 | Done |
| PBI-128 | Implementasi fitur cetak atau simpan halaman sertifikat oleh siswa menggunakan fitur print browser (Ctrl+P) tanpa perlu library tambahan | Task | 1 | Done |
| PBI-129 | Implementasi halaman daftar sertifikat yang sudah diterbitkan di panel admin: tabel berisi nama siswa, program, tanggal terbit, dan nomor sertifikat | Task | 1 | Done |
| PBI-130 | Pengujian fungsional penerbitan sertifikat: tampil daftar siswa layak, proses terbitkan, generate nomor, dan tampil di dashboard siswa | Testing | 1 | Done |
| PBI-131 | Pengujian tampilan halaman sertifikat: semua informasi tampil dengan benar dan halaman dapat dicetak via browser | Testing | 1 | Done |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 10 — Laporan Manajemen (PB-10 — Target: 5 SP)

> **Sprint Goal:** Mengembangkan fitur dashboard dan pelaporan manajemen untuk direktur, admin, dan instruktur agar evaluasi operasional lembaga dapat dilakukan secara menyeluruh berbasis data, termasuk statistik siswa, tingkat kelulusan per program, laporan evaluasi per kelas, serta pengelolaan arsip laporan.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-132 | Analisis kebutuhan laporan: data apa yang perlu ditampilkan untuk direktur (ringkasan program), admin (operasional), dan instruktur (evaluasi kelas) | Task | 1 | Done |
| PBI-133 | Perancangan skema database tabel `arsip_laporan` (id, judul, tipe_laporan, dibuat_oleh, periode, catatan, created_at) untuk menyimpan catatan laporan yang disusun admin | Design | 1 | Done |
| PBI-134 | Implementasi tabel `arsip_laporan` pada database | Task | 1 | Done |
| PBI-135 | Implementasi halaman dashboard direktur: kartu ringkasan berisi total siswa aktif, total pendaftar, jumlah program aktif, dan jumlah sertifikat yang sudah diterbitkan | Task | 2 | Done |
| PBI-136 | Implementasi tabel rekap jumlah siswa dan tingkat kelulusan per program di halaman direktur | Task | 2 | Done |
| PBI-137 | Implementasi fitur filter data di halaman direktur berdasarkan program dan periode (bulan atau tahun) | Task | 1 | Done |
| PBI-138 | Implementasi halaman laporan evaluasi kelas untuk instruktur: tabel berisi nama siswa, total kehadiran, rata-rata nilai tugas, jumlah tugas dikumpulkan, dan status lulus atau belum | Task | 2 | Done |
| PBI-139 | Implementasi fitur catat laporan operasional oleh admin: form input judul laporan, pilih periode, dan tulis catatan isi laporan | Task | 2 | Done |
| PBI-140 | Implementasi halaman arsip laporan operasional: daftar laporan yang pernah dicatat oleh admin beserta tombol lihat detail dan hapus | Task | 1 | Done |
| PBI-141 | Pengujian fungsional dashboard direktur: tampilan kartu statistik, tabel rekap program, dan filter periode | Testing | 1 | Done |
| PBI-142 | Pengujian fungsional laporan instruktur dan arsip laporan admin | Testing | 1 | Done |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 11 — Manajemen Pembayaran (PB-11 — Target: 13 SP)

> **Sprint Goal:** Membangun ekosistem transaksi pembayaran yang menjembatani proses pendaftaran dan aktivasi akun, meliputi invoicing, unggah bukti bayar, dan verifikasi admin.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-143 | Perancangan skema database tabel `invoice` dan `pembayaran` (id, pendaftaran_id, nominal, status, bukti_file, tanggal_bayar, diverifikasi_oleh) | Design | 1 | To Do |
| PBI-144 | Implementasi tabel pada database beserta relasi ke tabel `pendaftaran` | Task | 1 | To Do |
| PBI-145 | Implementasi logika otomatis penerbitan invoice saat status pendaftaran "Diterima" | Task | 2 | To Do |
| PBI-146 | Implementasi halaman tagihan di akun calon peserta beserta fitur unggah file bukti pembayaran | Task | 2 | To Do |
| PBI-147 | Implementasi dashboard admin untuk melihat daftar pembayaran menunggu verifikasi | Task | 2 | To Do |
| PBI-148 | Implementasi aksi verifikasi pembayaran oleh admin (Valid/Tidak Valid) beserta input catatan penolakan | Task | 2 | To Do |
| PBI-149 | Implementasi trigger/event aktivasi akun siswa (FR-15) secara otomatis setelah pembayaran divalidasi | Task | 2 | To Do |
| PBI-150 | Pengujian fungsional alur transaksi dari unggah bukti hingga aktivasi akun otomatis | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 12 — Manajemen Aset Robotik (PB-12 — Target: 13 SP)

> **Sprint Goal:** Mengembangkan kapabilitas pengelolaan inventaris perangkat keras robotik (kit, komponen) serta siklus peminjaman dan pengembalian aset oleh siswa dan instruktur.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-151 | Perancangan skema database tabel `aset_robotik` dan `peminjaman_aset` (id, user_id, aset_id, tanggal_pinjam, tanggal_kembali, status, kondisi) | Design | 2 | To Do |
| PBI-152 | Implementasi tabel aset dan peminjaman pada database | Task | 1 | To Do |
| PBI-153 | Implementasi antarmuka dan fungsi CRUD manajemen master data aset oleh admin (nama kit, jumlah stok, kondisi) | Task | 2 | To Do |
| PBI-154 | Implementasi formulir pengajuan peminjaman aset di dashboard siswa dan instruktur | Task | 2 | To Do |
| PBI-155 | Implementasi antarmuka admin untuk menyetujui atau menolak permohonan peminjaman aset (mengurangi stok otomatis) | Task | 2 | To Do |
| PBI-156 | Implementasi fitur konfirmasi pengembalian aset oleh admin beserta pembaharuan status kondisi (baik/rusak/hilang) | Task | 2 | To Do |
| PBI-157 | Implementasi tampilan riwayat peminjaman aset pada akun pengguna masing-masing | Task | 1 | To Do |
| PBI-158 | Pengujian fungsional sinkronisasi stok aset saat dipinjam dan dikembalikan | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 13 — Diskusi, Mentoring & Manajemen Keluhan (PB-13 — Target: 8 SP)

> **Sprint Goal:** Membangun modul komunikasi internal berupa forum diskusi spesifik kelas dan sistem ticketing keluhan (helpdesk) untuk mendukung proses belajar dan operasional.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-159 | Perancangan skema database tabel `forum_thread`, `forum_reply`, dan `tiket_keluhan` | Design | 1 | To Do |
| PBI-160 | Implementasi antarmuka forum diskusi pada dashboard kelas (fitur Create Thread & Reply) untuk instruktur dan siswa | Task | 2 | To Do |
| PBI-161 | Implementasi formulir pengajuan tiket keluhan (Helpdesk) dengan kategori (Akademik/Teknis) oleh pengguna | Task | 2 | To Do |
| PBI-162 | Implementasi dashboard penanganan keluhan oleh admin (update status: Open, In Progress, Resolved) | Task | 2 | To Do |
| PBI-163 | Pengujian fungsional hak akses forum (hanya untuk siswa enrolled) dan alur perubahan status tiket keluhan | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 14 — Audit Log & Evaluasi Kinerja (PB-14 — Target: 8 SP)

> **Sprint Goal:** Menerapkan fungsi audit trail untuk keamanan operasional dan melengkapi metrik manajerial dengan instrumen evaluasi instruktur.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-164 | Perancangan skema database tabel `audit_logs` dan `evaluasi_instruktur` | Design | 1 | To Do |
| PBI-165 | Implementasi middleware atau observer untuk mencatat log otomatis pada aksi krusial (Login, Delete Data, Verifikasi) | Task | 2 | To Do |
| PBI-166 | Implementasi formulir kuesioner evaluasi instruktur yang muncul di akun siswa saat kelas selesai | Task | 2 | To Do |
| PBI-167 | Implementasi antarmuka pembacaan Audit Log (filter berdasarkan user/action) pada panel Admin/Direktur | Task | 1 | To Do |
| PBI-168 | Implementasi integrasi nilai rata-rata evaluasi instruktur dan rekap aset hilang/rusak ke Dashboard Direktur | Task | 1 | To Do |
| PBI-169 | Pengujian fungsional perekaman log aktivitas dan kalkulasi nilai evaluasi instruktur | Testing | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Fitur telah diuji secara fungsional dan memenuhi acceptance criteria | To Do |
| Dokumentasi teknis telah diperbarui | To Do |
| Fitur telah di-deploy ke environment staging dan berjalan dengan baik | To Do |

---

### Sprint 15 — Deployment & Demo Sistem (PB-15 — Target: 5 SP)

> **Sprint Goal:** Melakukan deployment sistem ke server production agar platform Sistem Informasi Manajemen Sekolah Robotik dapat diakses secara online oleh seluruh pengguna, serta mempersiapkan skenario demo yang komprehensif untuk dipresentasikan kepada seluruh stakeholder.

| ID | Task / Sub-task | Tipe | SP | Status |
|----|----------------|------|----|--------|
| PBI-170 | Analisis kebutuhan environment deployment: pilih hosting, tentukan konfigurasi database, dan rencanakan struktur folder di server | Task | 1 | To Do |
| PBI-171 | Persiapan file aplikasi sebelum deployment: atur konfigurasi environment production, bersihkan kode debug, dan pastikan tidak ada kredensial sensitif di kode | Task | 1 | To Do |
| PBI-172 | Setup database di server hosting: buat database baru, import skema dari lokal, dan jalankan seeding data awal (roles dan akun admin pertama) | Task | 1 | To Do |
| PBI-173 | Upload aplikasi ke server dan konfigurasi web server: atur agar aplikasi berjalan di domain atau subdomain yang ditentukan | Task | 1 | To Do |
| PBI-174 | Konfigurasi folder penyimpanan file unggahan (dokumen, materi, foto) di server agar dapat diakses melalui URL dengan benar | Task | 1 | To Do |
| PBI-175 | Pengujian sistem pada server production: cek seluruh fitur utama berjalan normal sesuai yang sudah dikembangkan di lokal | Testing | 1 | To Do |
| PBI-176 | Perbaikan bug yang ditemukan saat pengujian di environment production | Task | 1 | To Do |
| PBI-177 | Penyusunan skenario demo sistem per peran: alur demo untuk Admin, Instruktur, Siswa, Tim Publikasi, dan Direktur | Task | 1 | To Do |
| PBI-178 | Persiapan data dummy yang realistis untuk keperluan demo: akun pengguna, data program, kelas, sesi, materi, tugas, dan sertifikat contoh | Task | 1 | To Do |
| PBI-179 | Penyusunan dokumen panduan penggunaan singkat per peran sebagai bahan demo kepada stakeholder | Task | 1 | To Do |

**Definition of Done:**

| Kriteria | Status |
|----------|--------|
| Tidak ada bug kritis yang belum terselesaikan | To Do |
| Seluruh fitur berjalan dengan baik di environment production | To Do |
| Sistem dapat diakses secara online melalui URL yang telah dikonfigurasi | To Do |
| Demo sistem telah dilakukan dan diterima oleh stakeholder | To Do |

---

## 7. Ringkasan Sprint Plan

| Sprint | Product Backlog | Target SP | Durasi | Jumlah PBI |
|--------|----------------|-----------|--------|------------|
| Sprint 1 | PB-01 – Manajemen Akun & Akses | 8 SP | 1,5 Minggu | 17 PBI |
| Sprint 2 | PB-02 – Landing Page | 8 SP | 1,5 Minggu | 12 PBI |
| Sprint 3 | PB-03 – Manajemen Informasi Program | 5 SP | 1,5 Minggu | 12 PBI |
| Sprint 4 | PB-04 – Pendaftaran Peserta | 13 SP | 1,5 Minggu | 15 PBI |
| Sprint 5 | PB-05 – Manajemen Siswa | 13 SP | 1,5 Minggu | 18 PBI |
| Sprint 6 | PB-06 – Manajemen Kelas & Jadwal Live Session | 13 SP | 1,5 Minggu | 16 PBI |
| Sprint 7 | PB-07 – Modul Pembelajaran & Penugasan | 13 SP | 1,5 Minggu | 19 PBI |
| Sprint 8 | PB-08 – Pemantauan Progres Belajar | 8 SP | 1,5 Minggu | 11 PBI |
| Sprint 9 | PB-09 – Manajemen Sertifikat | 5 SP | 1,5 Minggu | 11 PBI |
| Sprint 10 | PB-10 – Laporan Manajemen | 5 SP | 1,5 Minggu | 11 PBI |
| Sprint 11 | PB-11 – Manajemen Pembayaran | 13 SP | 1,5 Minggu | 8 PBI |
| Sprint 12 | PB-12 – Manajemen Aset Robotik | 13 SP | 1,5 Minggu | 8 PBI |
| Sprint 13 | PB-13 – Diskusi, Mentoring & Keluhan | 8 SP | 1,5 Minggu | 5 PBI |
| Sprint 14 | PB-14 – Audit Log & Kinerja Instruktur | 8 SP | 1,5 Minggu | 6 PBI |
| Sprint 15 | PB-15 – Deployment & Demo Sistem | 5 SP | 1,5 Minggu | 10 PBI |
| **Total** | **15 Sprints** | **138 SP** | **±22,5 Minggu** | **179 PBI** |


*Dokumen ini disusun sebagai referensi pengembangan Sistem Informasi Manajemen Sekolah Robotik menggunakan metodologi Scrum Framework.*
