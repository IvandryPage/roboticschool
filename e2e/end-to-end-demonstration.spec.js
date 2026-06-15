import { test, expect } from '@playwright/test';

test.describe('Demonstrasi Sistem End-to-End: Sekolah Robotik', () => {
    
    test('Skenario Lengkap 80 Langkah Simulasi Bisnis Sekolah Robotik', async ({ browser }) => {
        // Setup Actor Contexts
        const adminContext = await browser.newContext();
        const publikasiContext = await browser.newContext();
        const pesertaContext = await browser.newContext(); // Dipakai juga untuk Siswa
        const instrukturContext = await browser.newContext();
        const direkturContext = await browser.newContext();

        // Setup Pages
        const adminPage = await adminContext.newPage();
        const publikasiPage = await publikasiContext.newPage();
        const pesertaPage = await pesertaContext.newPage();
        const instrukturPage = await instrukturContext.newPage();
        const direkturPage = await direkturContext.newPage();

        const baseUrl = 'http://127.0.0.1:8000';

        await test.step('Fase 1: Manajemen Akun & Setup Program', async () => {
            // 1. Admin Akademik Login ke sistem menggunakan akun administrator
            await adminPage.goto(`${baseUrl}/login`);
            await adminPage.fill('input[name="email"]', 'admin@example.test');
            await adminPage.fill('input[name="password"]', 'admin123');
            await adminPage.click('button[type="submit"]');
            await expect(adminPage).toHaveURL(/.*dashboard/);

            // 2. Admin Akademik Mengelola role dan akun pengguna sistem
            await adminPage.click('text=Manajemen Akun');
            await adminPage.click('text=Pengguna');
            await expect(adminPage.locator('h1')).toContainText('Daftar Pengguna');

            // 3. Tim Publikasi Login ke sistem
            await publikasiPage.goto(`${baseUrl}/login`);
            await publikasiPage.fill('input[name="email"]', 'publikasi@example.test');
            await publikasiPage.fill('input[name="password"]', 'password123');
            await publikasiPage.click('button[type="submit"]');

            // 4. Tim Publikasi Mengelola banner dan konten landing page
            await publikasiPage.click('text=Manajemen Konten');
            await publikasiPage.click('text=Landing Page');

            // 5. Tim Publikasi Membuat program Robotika Dasar
            await publikasiPage.click('text=Program Pelatihan');
            await publikasiPage.click('text=Tambah Program');
            await publikasiPage.fill('input[name="nama_program"]', 'Robotika Dasar');

            // 6. Tim Publikasi Menambahkan deskripsi program, biaya, dan durasi pelatihan
            await publikasiPage.fill('textarea[name="deskripsi"]', 'Program dasar untuk pemula');
            await publikasiPage.fill('input[name="biaya"]', '500000');
            await publikasiPage.fill('input[name="durasi_minggu"]', '8');
            await publikasiPage.click('button:has-text("Simpan")');

            // 7. Tim Publikasi Menyusun kurikulum dan daftar materi program
            await publikasiPage.click('text=Kurikulum');
            await publikasiPage.click('text=Tambah Materi');
            await publikasiPage.fill('input[name="judul_materi"]', 'Pengenalan Komponen');
            await publikasiPage.click('button:has-text("Simpan")');

            // 8. Tim Publikasi Membuat Batch 2026 Gelombang 1
            await publikasiPage.click('text=Batch Pelatihan');
            await publikasiPage.click('text=Buka Batch Baru');
            await publikasiPage.fill('input[name="nama_batch"]', 'Batch 2026 Gelombang 1');
            await publikasiPage.fill('input[name="kuota_max"]', '20');
            await publikasiPage.click('button:has-text("Simpan")');
        });

        await test.step('Fase 2: Pendaftaran Peserta', async () => {
            // 9. Sistem Menampilkan program pada halaman publik
            // 10. Calon Peserta Mengakses website sekolah robotik
            await pesertaPage.goto(baseUrl);
            await expect(pesertaPage.locator('h1')).toContainText('Sekolah Robotik');

            // 11. Calon Peserta Menelusuri daftar program pelatihan
            await pesertaPage.click('text=Daftar Program');

            // 12. Calon Peserta Melihat detail program Robotika Dasar
            await pesertaPage.click('text=Robotika Dasar');
            await expect(pesertaPage.locator('h1')).toContainText('Robotika Dasar');

            // 13. Calon Peserta Mengisi formulir pendaftaran
            await pesertaPage.click('text=Daftar Sekarang');
            await pesertaPage.fill('input[name="nama_lengkap"]', 'Calon Peserta Demo');
            await pesertaPage.fill('input[name="email"]', 'calon@example.test');
            await pesertaPage.fill('input[name="no_hp"]', '081234567890');

            // 14. Calon Peserta Mengunggah dokumen persyaratan
            // await pesertaPage.setInputFiles('input[type="file"]', 'dokumen.pdf');
            await pesertaPage.click('button:has-text("Kirim Pendaftaran")');

            // 15. Sistem Menyimpan data pendaftaran peserta
            await expect(pesertaPage.locator('.alert-success')).toContainText('Pendaftaran berhasil dikirim');

            // 16. Admin Akademik Meninjau data pendaftaran masuk
            await adminPage.click('text=Pendaftaran Masuk');
            await adminPage.click('text=Calon Peserta Demo');

            // 17. Admin Akademik Memverifikasi dokumen pendaftaran
            await adminPage.click('text=Verifikasi Dokumen');

            // 18. Admin Akademik Menyetujui pendaftaran peserta
            await adminPage.click('button:has-text("Setujui Pendaftaran")');
            await expect(adminPage.locator('.badge')).toContainText('Diterima');
        });

        await test.step('Fase 3: Pembayaran & Aktivasi', async () => {
            // 19. Sistem Membuat invoice pembayaran secara otomatis
            // Terverifikasi dari database atau halaman invoice
            
            // 20. Calon Peserta Login ke portal pendaftaran
            await pesertaPage.goto(`${baseUrl}/portal-pendaftaran`);

            // 21. Calon Peserta Melihat detail tagihan pembayaran
            await pesertaPage.click('text=Tagihan Pembayaran');
            await expect(pesertaPage.locator('.invoice-details')).toBeVisible();

            // 22. Calon Peserta Melakukan pembayaran program
            await pesertaPage.click('button:has-text("Bayar Sekarang")');

            // 23. Sistem Menerima callback pembayaran dari payment gateway
            await pesertaPage.click('text=Simulasi Bayar Sukses'); // Mock button

            // 24. Sistem Memperbarui status pembayaran menjadi berhasil
            await expect(pesertaPage.locator('.status-pembayaran')).toContainText('Lunas');

            // 25. Sistem Mengaktifkan akun siswa secara otomatis
            // Akun otomatis terbuat untuk email calon@example.test
        });

        await test.step('Fase 4: Setup Kelas & Penugasan Instruktur', async () => {
            // 26. Siswa Login ke dashboard siswa
            await pesertaPage.goto(`${baseUrl}/login`);
            await pesertaPage.fill('input[name="email"]', 'calon@example.test');
            await pesertaPage.fill('input[name="password"]', 'password123'); // Asumsi default pass
            await pesertaPage.click('button[type="submit"]');

            // 27. Siswa Melengkapi data profil siswa
            await pesertaPage.click('text=Profil Saya');
            await pesertaPage.fill('input[name="alamat"]', 'Jl. Simulasi No 1');
            await pesertaPage.click('button:has-text("Simpan Profil")');

            // 28. Admin Akademik Mengelola data siswa aktif
            await adminPage.click('text=Manajemen Siswa');

            // 29. Admin Akademik Membuat kelas Robotika Dasar A
            await adminPage.click('text=Manajemen Kelas');
            await adminPage.click('text=Buat Kelas Baru');
            await adminPage.fill('input[name="nama_kelas"]', 'Robotika Dasar A');

            // 30. Admin Akademik Menentukan instruktur kelas
            await adminPage.selectOption('select[name="instruktur_id"]', { label: 'Instruktur Demo' });
            await adminPage.click('button:has-text("Simpan Kelas")');

            // 31. Admin Akademik Menambahkan siswa ke kelas
            await adminPage.click('text=Kelola Siswa');
            await adminPage.click('button:has-text("Tambahkan Siswa")');
            await adminPage.check('input[value="calon@example.test"]');
            await adminPage.click('button:has-text("Masukkan ke Kelas")');

            // 32. Admin Akademik Menjadwalkan sesi live pembelajaran
            await adminPage.click('text=Jadwal Sesi');
            await adminPage.click('text=Buat Jadwal Sesi Live');
            await adminPage.click('button:has-text("Simpan")');
        });

        await test.step('Fase 5: Pelaksanaan Pembelajaran', async () => {
            // 33. Instruktur Login ke dashboard instruktur
            await instrukturPage.goto(`${baseUrl}/login`);
            await instrukturPage.fill('input[name="email"]', 'instruktur@example.test');
            await instrukturPage.fill('input[name="password"]', 'password123');
            await instrukturPage.click('button[type="submit"]');

            // 34. Instruktur Melihat daftar kelas yang diampu
            await instrukturPage.click('text=Kelas Saya');
            await instrukturPage.click('text=Robotika Dasar A');

            // 35. Instruktur Membuat sesi pembelajaran
            await instrukturPage.click('text=Sesi Pembelajaran');
            await instrukturPage.click('text=Buat Sesi');
            await instrukturPage.fill('input[name="judul_sesi"]', 'Sesi 1: Intro');
            await instrukturPage.click('button:has-text("Simpan")');

            // 36. Instruktur Mengunggah materi pembelajaran
            await instrukturPage.click('text=Materi Pembelajaran');
            await instrukturPage.click('text=Tambah Materi');

            // 37. Instruktur Menambahkan video atau file pendukung
            // await instrukturPage.setInputFiles('input[name="file"]', 'video.mp4');
            await instrukturPage.click('button:has-text("Unggah")');

            // 38. Instruktur Membuat tugas pembelajaran
            await instrukturPage.click('text=Manajemen Tugas');
            await instrukturPage.click('text=Buat Tugas Baru');
            await instrukturPage.fill('input[name="judul_tugas"]', 'Tugas 1: Merakit LED');
            await instrukturPage.click('button:has-text("Publikasikan")');

            // 39. Siswa Mengakses dashboard pembelajaran
            await pesertaPage.click('text=Dashboard Pembelajaran');

            // 40. Siswa Melihat jadwal sesi live
            await pesertaPage.click('text=Jadwal Kelas');
            await expect(pesertaPage.locator('.jadwal-item')).toContainText('Sesi 1: Intro');

            // 41. Siswa Mengikuti sesi pembelajaran
            await pesertaPage.click('button:has-text("Gabung Live Session")');

            // 42. Siswa Mengakses dan mengunduh materi pembelajaran
            await pesertaPage.click('text=Materi');
            await pesertaPage.click('text=Unduh Materi');

            // 43. Siswa Mengumpulkan tugas
            await pesertaPage.click('text=Tugas Saya');
            await pesertaPage.click('text=Tugas 1: Merakit LED');
            // await pesertaPage.setInputFiles('input[type="file"]', 'tugas.zip');
            await pesertaPage.click('button:has-text("Kumpulkan Tugas")');

            // 44. Instruktur Melihat daftar pengumpulan tugas siswa
            await instrukturPage.click('text=Pengumpulan Tugas');
            await instrukturPage.click('text=Calon Peserta Demo');

            // 45. Instruktur Memberikan nilai tugas
            await instrukturPage.fill('input[name="nilai"]', '90');

            // 46. Instruktur Memberikan umpan balik kepada siswa
            await instrukturPage.fill('textarea[name="umpan_balik"]', 'Kerja yang bagus!');
            await instrukturPage.click('button:has-text("Simpan Nilai")');
        });

        await test.step('Fase 6: Monitoring, Diskusi, & Dukungan', async () => {
            // 47. Sistem Menghitung progres akademik siswa
            // 48. Siswa Melihat progres akademik pribadi
            await pesertaPage.click('text=Progres Belajar');
            await expect(pesertaPage.locator('.progress-bar')).toBeVisible();

            // 49. Instruktur Memantau perkembangan belajar siswa
            await instrukturPage.click('text=Progres Siswa');
            await expect(instrukturPage.locator('.student-progress')).toBeVisible();

            // 50. Siswa Membuka forum diskusi kelas
            await pesertaPage.click('text=Forum Kelas');

            // 51. Siswa Membuat topik diskusi baru
            await pesertaPage.click('text=Buat Topik');
            await pesertaPage.fill('input[name="judul"]', 'Pertanyaan Komponen');
            await pesertaPage.click('button:has-text("Kirim")');

            // 52. Instruktur Memberikan balasan pada diskusi
            await instrukturPage.click('text=Forum Kelas');
            await instrukturPage.click('text=Pertanyaan Komponen');
            await instrukturPage.fill('textarea[name="komentar"]', 'Ini penjelasan dari saya.');
            await instrukturPage.click('button:has-text("Balas")');

            // 53. Siswa Mengirim tiket keluhan akademik
            await pesertaPage.click('text=Bantuan / Keluhan');
            await pesertaPage.click('text=Kirim Tiket Baru');
            await pesertaPage.fill('input[name="subjek"]', 'Kendala Akses Materi');
            await pesertaPage.click('button:has-text("Kirim Tiket")');

            // 54. Admin Akademik Meninjau tiket keluhan yang masuk
            await adminPage.click('text=Tiket Keluhan');
            await adminPage.click('text=Kendala Akses Materi');

            // 55. Admin Akademik Memperbarui status tiket menjadi In Progress
            await adminPage.click('button:has-text("Proses Tiket")');

            // 56. Admin Akademik Menyelesaikan tiket keluhan
            await adminPage.click('button:has-text("Tandai Selesai")');
        });

        await test.step('Fase 7: Manajemen Aset Robotik', async () => {
            // 57. Instruktur Mengajukan peminjaman kit robotik
            await instrukturPage.click('text=Peminjaman Aset');
            await instrukturPage.click('text=Ajukan Peminjaman');
            await instrukturPage.selectOption('select[name="item_kit_id"]', { index: 1 });
            await instrukturPage.click('button:has-text("Kirim Pengajuan")');

            // 58. Admin Akademik Meninjau permohonan peminjaman aset
            await adminPage.click('text=Peminjaman Aset');
            await expect(adminPage.locator('.table')).toContainText('Menunggu Persetujuan');

            // 59. Admin Akademik Menyetujui peminjaman aset
            await adminPage.click('button:has-text("Setujui")');

            // 60. Sistem Mengurangi stok aset secara otomatis
            
            // 61. Instruktur Melihat riwayat peminjaman aset
            await instrukturPage.click('text=Riwayat Peminjaman');
            await expect(instrukturPage.locator('.badge')).toContainText('Disetujui');

            // 62. Admin Akademik Mengonfirmasi pengembalian aset
            await adminPage.click('button:has-text("Konfirmasi Pengembalian")');

            // 63. Sistem Memperbarui stok aset setelah pengembalian
        });

        await test.step('Fase 8: Evaluasi & Sertifikasi', async () => {
            // 64. Sistem Mencatat aktivitas pengguna ke audit log
            // 65. Siswa Mengisi evaluasi instruktur setelah kelas selesai
            await pesertaPage.click('text=Evaluasi Instruktur');
            await pesertaPage.click('text=Isi Evaluasi');
            await pesertaPage.check('input[value="5"]'); // Beri bintang 5
            await pesertaPage.click('button:has-text("Kirim Evaluasi")');

            // 66. Sistem Menghitung nilai rata-rata evaluasi instruktur

            // 67. Admin Akademik Memverifikasi status kelulusan siswa
            await adminPage.click('text=Manajemen Kelulusan');

            // 68. Sistem Menghasilkan sertifikat digital
            // 69. Admin Akademik Menerbitkan sertifikat kepada siswa
            await adminPage.click('button:has-text("Generate & Terbitkan Sertifikat")');
            await expect(adminPage.locator('.alert-success')).toContainText('berhasil diterbitkan');

            // 70. Siswa Mengunduh sertifikat kelulusan
            await pesertaPage.click('text=Sertifikat Saya');
            await expect(pesertaPage.locator('text=Unduh Sertifikat')).toBeVisible();
        });

        await test.step('Fase 9: Laporan Manajemen (Direktur)', async () => {
            // 71. Direktur Login ke dashboard manajemen
            await direkturPage.goto(`${baseUrl}/login`);
            await direkturPage.fill('input[name="email"]', 'direktur@example.test');
            await direkturPage.fill('input[name="password"]', 'password123');
            await direkturPage.click('button[type="submit"]');

            await expect(direkturPage.locator('h1')).toContainText('Dashboard Manajemen');

            // 72. Direktur Melihat statistik pendaftaran peserta
            await direkturPage.click('text=Statistik Pendaftaran');
            await expect(direkturPage.locator('canvas')).toBeVisible();
            
            // 73. Direktur Melihat statistik pembayaran
            await direkturPage.click('text=Statistik Pembayaran');
            
            // 74. Direktur Melihat statistik siswa aktif
            await direkturPage.click('text=Siswa Aktif');

            // 75. Direktur Melihat progres pembelajaran seluruh kelas
            await direkturPage.click('text=Progres Pembelajaran');

            // 76. Direktur Melihat laporan aset robotik
            await direkturPage.click('text=Laporan Aset');

            // 77. Direktur Melihat hasil evaluasi instruktur
            await direkturPage.click('text=Evaluasi Instruktur');

            // 78. Direktur Melihat audit log aktivitas sistem
            await direkturPage.click('text=Audit Log');
            await expect(direkturPage.locator('table')).toContainText('Tandai Selesai'); // aktivitas sistem admin tadi

            // 79. Direktur Mengunduh laporan manajemen
            // const [download] = await Promise.all([
            //     direkturPage.waitForEvent('download'),
            //     direkturPage.click('button:has-text("Unduh Laporan PDF")')
            // ]);
            // await expect(download.suggestedFilename()).toContain('laporan');

            // 80. Direktur Menutup sesi monitoring operasional
            await direkturPage.click('text=Logout');
        });

        // Cleanup Contexts
        await adminContext.close();
        await publikasiContext.close();
        await pesertaContext.close();
        await instrukturContext.close();
        await direkturContext.close();
    });
});
