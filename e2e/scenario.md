## Ruang Lingkup Demonstrasi

Demonstrasi mencakup seluruh aktor utama yang berinteraksi dengan Sistem Informasi Manajemen Sekolah Robotik, yaitu:

### 1. Tim Publikasi

Tim Publikasi berperan dalam mengelola informasi yang akan ditampilkan kepada calon peserta, termasuk program pelatihan robotik, deskripsi program, jadwal batch, biaya pelatihan, serta konten promosi yang ditampilkan pada halaman publik sistem.

### 2. Siswa

Siswa berperan sebagai pengguna layanan pendidikan yang memanfaatkan sistem untuk melihat informasi program, melakukan pendaftaran, menyelesaikan pembayaran, mengikuti proses pembelajaran, mengumpulkan tugas, memantau progres akademik, dan memperoleh sertifikat setelah menyelesaikan program.

### 3. Admin Akademik

Admin Akademik berperan dalam mengelola operasional akademik, termasuk memproses pendaftaran peserta, mengelola batch dan kelas, melakukan pengelompokan siswa, menerbitkan sertifikat, serta mengelola data akademik yang mendukung proses pembelajaran.

### 4. Instruktur

Instruktur berperan dalam mengelola proses pembelajaran, mulai dari penyusunan dan publikasi materi, pelaksanaan sesi pembelajaran, pemberian tugas, penilaian hasil belajar siswa, hingga pemantauan perkembangan akademik peserta.

### 5. Direktur

Direktur berperan sebagai pengambil keputusan yang memanfaatkan dashboard dan laporan manajemen untuk memantau kondisi operasional, aktivitas akademik, serta kinerja lembaga secara keseluruhan.

---

## Pendekatan Demonstrasi

Demonstrasi sistem dilakukan menggunakan pendekatan **end-to-end business process demonstration**, yaitu menampilkan sistem berdasarkan alur proses bisnis yang terjadi di dalam organisasi, bukan berdasarkan modul atau fitur yang berdiri sendiri.

Pendekatan ini dipilih agar stakeholder dapat memahami bagaimana setiap modul saling terintegrasi dalam mendukung operasional Sekolah Robotik, mulai dari publikasi program hingga penyajian laporan manajemen. Selain itu, pendekatan ini memungkinkan demonstrasi menunjukkan aliran data dan keterkaitan proses antar aktor secara lebih realistis sesuai kondisi operasional sebenarnya.

---
# Skenario Demonstrasi Sistem End-to-End

Dokumen ini digunakan sebagai panduan demonstrasi Sistem Informasi Manajemen Sekolah Robotik kepada stakeholder. Demonstrasi dilakukan berdasarkan alur proses bisnis end-to-end sehingga seluruh modul utama sistem dapat ditunjukkan secara terintegrasi.

| No | Aktor | Aktivitas Demonstrasi | Modul / Product Backlog |
|----|--------|----------------------|-------------------------|
| 1 | Admin Akademik | Login ke sistem menggunakan akun administrator | PB-01 Manajemen Akun & Akses |
| 2 | Admin Akademik | Mengelola role dan akun pengguna sistem | PB-01 Manajemen Akun & Akses |
| 3 | Tim Publikasi | Login ke sistem | PB-01 Manajemen Akun & Akses |
| 4 | Tim Publikasi | Mengelola banner dan konten landing page | PB-02 Landing Page |
| 5 | Tim Publikasi | Membuat program Robotika Dasar | PB-03 Manajemen Informasi Program |
| 6 | Tim Publikasi | Menambahkan deskripsi program, biaya, dan durasi pelatihan | PB-03 Manajemen Informasi Program |
| 7 | Tim Publikasi | Menyusun kurikulum dan daftar materi program | PB-03 Manajemen Informasi Program |
| 8 | Tim Publikasi | Membuat Batch 2026 Gelombang 1 | PB-03 Manajemen Informasi Program |
| 9 | Sistem | Menampilkan program pada halaman publik | PB-02 Landing Page |
| 10 | Calon Peserta | Mengakses website sekolah robotik | PB-02 Landing Page |
| 11 | Calon Peserta | Menelusuri daftar program pelatihan | PB-03 Manajemen Informasi Program |
| 12 | Calon Peserta | Melihat detail program Robotika Dasar | PB-03 Manajemen Informasi Program |
| 13 | Calon Peserta | Mengisi formulir pendaftaran | PB-04 Pendaftaran Peserta |
| 14 | Calon Peserta | Mengunggah dokumen persyaratan | PB-04 Pendaftaran Peserta |
| 15 | Sistem | Menyimpan data pendaftaran peserta | PB-04 Pendaftaran Peserta |
| 16 | Admin Akademik | Meninjau data pendaftaran masuk | PB-04 Pendaftaran Peserta |
| 17 | Admin Akademik | Memverifikasi dokumen pendaftaran | PB-04 Pendaftaran Peserta |
| 18 | Admin Akademik | Menyetujui pendaftaran peserta | PB-04 Pendaftaran Peserta |
| 19 | Sistem | Membuat invoice pembayaran secara otomatis | PB-11 Manajemen Pembayaran |
| 20 | Calon Peserta | Login ke portal pendaftaran | PB-11 Manajemen Pembayaran |
| 21 | Calon Peserta | Melihat detail tagihan pembayaran | PB-11 Manajemen Pembayaran |
| 22 | Calon Peserta | Melakukan pembayaran program | PB-11 Manajemen Pembayaran |
| 23 | Sistem | Menerima callback pembayaran dari payment gateway | PB-11 Manajemen Pembayaran |
| 24 | Sistem | Memperbarui status pembayaran menjadi berhasil | PB-11 Manajemen Pembayaran |
| 25 | Sistem | Mengaktifkan akun siswa secara otomatis | PB-11 Manajemen Pembayaran |
| 26 | Siswa | Login ke dashboard siswa | PB-05 Manajemen Siswa |
| 27 | Siswa | Melengkapi data profil siswa | PB-05 Manajemen Siswa |
| 28 | Admin Akademik | Mengelola data siswa aktif | PB-05 Manajemen Siswa |
| 29 | Admin Akademik | Membuat kelas Robotika Dasar A | PB-06 Manajemen Kelas & Jadwal |
| 30 | Admin Akademik | Menentukan instruktur kelas | PB-06 Manajemen Kelas & Jadwal |
| 31 | Admin Akademik | Menambahkan siswa ke kelas | PB-06 Manajemen Kelas & Jadwal |
| 32 | Admin Akademik | Menjadwalkan sesi live pembelajaran | PB-06 Manajemen Kelas & Jadwal |
| 33 | Instruktur | Login ke dashboard instruktur | PB-01 Manajemen Akun & Akses |
| 34 | Instruktur | Melihat daftar kelas yang diampu | PB-06 Manajemen Kelas & Jadwal |
| 35 | Instruktur | Membuat sesi pembelajaran | PB-06 Manajemen Kelas & Jadwal |
| 36 | Instruktur | Mengunggah materi pembelajaran | PB-07 Modul Pembelajaran & Penugasan |
| 37 | Instruktur | Menambahkan video atau file pendukung | PB-07 Modul Pembelajaran & Penugasan |
| 38 | Instruktur | Membuat tugas pembelajaran | PB-07 Modul Pembelajaran & Penugasan |
| 39 | Siswa | Mengakses dashboard pembelajaran | PB-07 Modul Pembelajaran & Penugasan |
| 40 | Siswa | Melihat jadwal sesi live | PB-06 Manajemen Kelas & Jadwal |
| 41 | Siswa | Mengikuti sesi pembelajaran | PB-06 Manajemen Kelas & Jadwal |
| 42 | Siswa | Mengakses dan mengunduh materi pembelajaran | PB-07 Modul Pembelajaran & Penugasan |
| 43 | Siswa | Mengumpulkan tugas | PB-07 Modul Pembelajaran & Penugasan |
| 44 | Instruktur | Melihat daftar pengumpulan tugas siswa | PB-07 Modul Pembelajaran & Penugasan |
| 45 | Instruktur | Memberikan nilai tugas | PB-07 Modul Pembelajaran & Penugasan |
| 46 | Instruktur | Memberikan umpan balik kepada siswa | PB-07 Modul Pembelajaran & Penugasan |
| 47 | Sistem | Menghitung progres akademik siswa | PB-08 Pemantauan Progres Belajar |
| 48 | Siswa | Melihat progres akademik pribadi | PB-08 Pemantauan Progres Belajar |
| 49 | Instruktur | Memantau perkembangan belajar siswa | PB-08 Pemantauan Progres Belajar |
| 50 | Siswa | Membuka forum diskusi kelas | PB-13 Diskusi, Mentoring & Keluhan |
| 51 | Siswa | Membuat topik diskusi baru | PB-13 Diskusi, Mentoring & Keluhan |
| 52 | Instruktur | Memberikan balasan pada diskusi | PB-13 Diskusi, Mentoring & Keluhan |
| 53 | Siswa | Mengirim tiket keluhan akademik | PB-13 Diskusi, Mentoring & Keluhan |
| 54 | Admin Akademik | Meninjau tiket keluhan yang masuk | PB-13 Diskusi, Mentoring & Keluhan |
| 55 | Admin Akademik | Memperbarui status tiket menjadi In Progress | PB-13 Diskusi, Mentoring & Keluhan |
| 56 | Admin Akademik | Menyelesaikan tiket keluhan | PB-13 Diskusi, Mentoring & Keluhan |
| 57 | Instruktur | Mengajukan peminjaman kit robotik | PB-12 Manajemen Aset Robotik |
| 58 | Admin Akademik | Meninjau permohonan peminjaman aset | PB-12 Manajemen Aset Robotik |
| 59 | Admin Akademik | Menyetujui peminjaman aset | PB-12 Manajemen Aset Robotik |
| 60 | Sistem | Mengurangi stok aset secara otomatis | PB-12 Manajemen Aset Robotik |
| 61 | Instruktur | Melihat riwayat peminjaman aset | PB-12 Manajemen Aset Robotik |
| 62 | Admin Akademik | Mengonfirmasi pengembalian aset | PB-12 Manajemen Aset Robotik |
| 63 | Sistem | Memperbarui stok aset setelah pengembalian | PB-12 Manajemen Aset Robotik |
| 64 | Sistem | Mencatat aktivitas pengguna ke audit log | PB-14 Audit Log & Evaluasi Kinerja |
| 65 | Siswa | Mengisi evaluasi instruktur setelah kelas selesai | PB-14 Audit Log & Evaluasi Kinerja |
| 66 | Sistem | Menghitung nilai rata-rata evaluasi instruktur | PB-14 Audit Log & Evaluasi Kinerja |
| 67 | Admin Akademik | Memverifikasi status kelulusan siswa | PB-09 Manajemen Sertifikat |
| 68 | Sistem | Menghasilkan sertifikat digital | PB-09 Manajemen Sertifikat |
| 69 | Admin Akademik | Menerbitkan sertifikat kepada siswa | PB-09 Manajemen Sertifikat |
| 70 | Siswa | Mengunduh sertifikat kelulusan | PB-09 Manajemen Sertifikat |
| 71 | Direktur | Login ke dashboard manajemen | PB-10 Laporan Manajemen |
| 72 | Direktur | Melihat statistik pendaftaran peserta | PB-10 Laporan Manajemen |
| 73 | Direktur | Melihat statistik pembayaran | PB-10 Laporan Manajemen |
| 74 | Direktur | Melihat statistik siswa aktif | PB-10 Laporan Manajemen |
| 75 | Direktur | Melihat progres pembelajaran seluruh kelas | PB-10 Laporan Manajemen |
| 76 | Direktur | Melihat laporan aset robotik | PB-10 Laporan Manajemen |
| 77 | Direktur | Melihat hasil evaluasi instruktur | PB-14 Audit Log & Evaluasi Kinerja |
| 78 | Direktur | Melihat audit log aktivitas sistem | PB-14 Audit Log & Evaluasi Kinerja |
| 79 | Direktur | Mengunduh laporan manajemen | PB-10 Laporan Manajemen |
| 80 | Direktur | Menutup sesi monitoring operasional | PB-10 Laporan Manajemen |

---

## Hasil Demonstrasi yang Diharapkan

- Seluruh proses bisnis utama dapat berjalan secara end-to-end.
- Seluruh peran utama sistem dapat diperagakan.
- Seluruh Product Backlog dari PB-01 sampai PB-14 memiliki representasi dalam demonstrasi.
- Stakeholder memahami aliran data dan keterkaitan antar modul sistem.
- Sistem mampu mendukung operasional Sekolah Robotik mulai dari publikasi program hingga pelaporan manajemen.