<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // MODUL AKUN & AKSES
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_role')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('no_hp')->nullable();
            $table->string('password');
            $table->string('foto_profil')->nullable();
            $table->foreignUuid('role_id')->constrained('roles');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        // MODUL PROGRAM
        Schema::create('program_kursus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_program');
            $table->text('deskripsi')->nullable();
            $table->string('level')->nullable();
            $table->decimal('biaya', 14, 2)->default(0);
            $table->integer('durasi_minggu')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('status_tampil')->default(true);
            $table->timestamps();
        });

        Schema::create('materi_program', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('program_id')->constrained('program_kursus');
            $table->integer('nomor_urut');
            $table->string('judul_materi');
            $table->text('deskripsi_materi')->nullable();
            $table->timestamps();

            $table->unique(['program_id', 'nomor_urut']);
        });

        Schema::create('batch', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('program_id')->constrained('program_kursus');
            $table->string('nama_batch');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->integer('kuota_max')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        // MODUL PENDAFTARAN
        Schema::create('calon_peserta', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('no_hp')->nullable();
            $table->string('asal_sekolah_atau_instansi')->nullable();
            $table->string('jenjang_pendidikan')->nullable();
            $table->timestamps();
        });

        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('calon_peserta_id')->constrained('calon_peserta');
            $table->foreignUuid('program_id')->constrained('program_kursus');
            $table->string('no_referensi')->unique();
            $table->timestamp('tanggal_daftar')->nullable();
            $table->string('status')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        Schema::create('riwayat_status_pendaftaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pendaftaran_id')->constrained('pendaftaran');
            $table->string('status_lama')->nullable();
            $table->string('status_baru')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignUuid('diubah_oleh')->constrained('users');
            $table->timestamps();
        });

        Schema::create('dokumen_pendaftaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pendaftaran_id')->constrained('pendaftaran');
            $table->string('jenis_dokumen');
            $table->string('nama_file');
            $table->string('file_path');
            $table->integer('versi')->default(1);
            $table->string('status_verifikasi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['pendaftaran_id', 'jenis_dokumen', 'versi']);
        });

        // MODUL PEMBAYARAN
        Schema::create('invoice', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pendaftaran_id')->constrained('pendaftaran')->unique();
            $table->string('no_invoice')->unique();
            $table->decimal('total_tagihan', 14, 2)->default(0);
            $table->timestamp('tanggal_terbit')->nullable();
            $table->timestamp('tanggal_jatuh_tempo')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('payment_reference')->unique()->nullable();
            $table->json('gateway_payload')->nullable();
            $table->timestamps();
        });

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')->constrained('invoice')->unique();
            $table->decimal('nominal', 14, 2)->default(0);
            $table->string('metode_pembayaran')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_reference')->unique()->nullable();
            $table->string('status')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('callback_payload')->nullable();
            $table->timestamps();
        });

        // MODUL SISWA
        Schema::create('siswa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->unique();
            $table->foreignUuid('pendaftaran_id')->constrained('pendaftaran')->unique();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('batch_id')->constrained('batch');
            $table->string('nama_kelas');
            $table->foreignUuid('instruktur_id')->constrained('users');
            $table->integer('kapasitas')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('enrollment_kelas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kelas_id')->constrained('kelas');
            $table->foreignUuid('siswa_id')->constrained('siswa');
            $table->timestamp('tanggal_bergabung')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->unique(['kelas_id', 'siswa_id']);
        });

        // MODUL PEMBELAJARAN
        Schema::create('sesi_live', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kelas_id')->constrained('kelas');
            $table->integer('nomor_sesi');
            $table->string('judul_sesi')->nullable();
            $table->date('tanggal')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('platform')->nullable();
            $table->string('link_akses')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['kelas_id', 'nomor_sesi']);
        });

        Schema::create('kehadiran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sesi_id')->constrained('sesi_live');
            $table->foreignUuid('siswa_id')->constrained('siswa');
            $table->string('status_hadir')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignUuid('dicatat_oleh')->constrained('users');
            $table->timestamp('waktu_pencatatan')->nullable();
            $table->timestamps();

            $table->unique(['sesi_id', 'siswa_id']);
        });

        Schema::create('materi_pembelajaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sesi_id')->constrained('sesi_live');
            $table->string('judul')->nullable();
            $table->string('tipe_konten')->nullable();
            $table->string('file_path_atau_url')->nullable();
            $table->integer('urutan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['sesi_id', 'urutan']);
        });

        Schema::create('tugas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sesi_id')->constrained('sesi_live');
            $table->string('judul_tugas');
            $table->text('deskripsi')->nullable();
            $table->string('file_soal')->nullable();
            $table->timestamp('batas_waktu')->nullable();
            $table->decimal('nilai_maksimum', 8, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tugas_id')->constrained('tugas');
            $table->foreignUuid('siswa_id')->constrained('siswa');
            $table->string('file_jawaban')->nullable();
            $table->text('catatan_siswa')->nullable();
            $table->timestamp('waktu_kumpul')->nullable();
            $table->decimal('nilai', 8, 2)->nullable();
            $table->text('umpan_balik')->nullable();
            $table->string('status_penilaian')->nullable();
            $table->timestamps();

            $table->unique(['tugas_id', 'siswa_id']);
        });

        Schema::create('progress_akademik', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('siswa_id')->constrained('siswa');
            $table->foreignUuid('kelas_id')->constrained('kelas');
            $table->decimal('persentase_kehadiran', 5, 2)->nullable();
            $table->decimal('rata_nilai_tugas', 8, 2)->nullable();
            $table->decimal('persentase_penyelesaian', 5, 2)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'kelas_id']);
        });

        Schema::create('forum_topik', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kelas_id')->constrained('kelas');
            $table->foreignUuid('pembuat_id')->constrained('users');
            $table->string('judul');
            $table->text('konten')->nullable();
            $table->timestamps();
        });

        Schema::create('forum_komentar', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('topik_id')->constrained('forum_topik');
            $table->foreignUuid('user_id')->constrained('users');
            $table->text('komentar');
            $table->timestamps();
        });

        // MODUL SERTIFIKAT
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('siswa_id')->constrained('siswa');
            $table->foreignUuid('kelas_id')->constrained('kelas');
            $table->string('nomor_sertifikat')->unique();
            $table->string('file_path')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('verified_url')->nullable();
            $table->timestamp('tanggal_terbit')->nullable();
            $table->foreignUuid('diterbitkan_oleh')->constrained('users');
            $table->timestamps();

            $table->unique(['siswa_id', 'kelas_id']);
        });

        // MODUL ASET ROBOTIK
        Schema::create('aset_robotik', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_aset')->unique();
            $table->string('nama_kit');
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->nullable();
            $table->integer('stok_minimal')->nullable();
            $table->timestamps();
        });

        Schema::create('item_kit_robotik', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('aset_id')->constrained('aset_robotik');
            $table->string('serial_number')->unique();
            $table->string('status_kondisi')->nullable();
            $table->string('lokasi_rak')->nullable();
            $table->timestamps();
        });

        Schema::create('peminjaman_item_aset', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('item_kit_id')->constrained('item_kit_robotik');
            $table->timestamp('tanggal_pinjam')->nullable();
            $table->timestamp('tanggal_jatuh_tempo')->nullable();
            $table->timestamp('tanggal_kembali')->nullable();
            $table->string('status')->nullable();
            $table->string('kondisi_awal')->nullable();
            $table->string('kondisi_akhir')->nullable();
            $table->foreignUuid('diverifikasi_oleh')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('maintenance_aset', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('item_kit_id')->constrained('item_kit_robotik');
            $table->foreignUuid('dilaporkan_oleh')->constrained('users');
            $table->foreignUuid('ditangani_oleh')->nullable()->constrained('users');
            $table->timestamp('tanggal_lapor')->nullable();
            $table->text('deskripsi_kerusakan')->nullable();
            $table->string('status')->nullable();
            $table->decimal('biaya', 12, 2)->nullable();
            $table->timestamp('selesai_pada')->nullable();
            $table->timestamps();
        });

        // MODUL OPERASIONAL
        Schema::create('arsip_laporan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->string('tipe_laporan')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignUuid('dibuat_oleh')->constrained('users');
            $table->string('periode')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('notifikasi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users');
            $table->string('tipe')->nullable();
            $table->string('judul')->nullable();
            $table->text('pesan')->nullable();
            $table->string('link_aksi')->nullable();
            $table->boolean('dibaca')->default(false);
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();
        });

        Schema::create('tiket_keluhan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pelapor_id')->constrained('users');
            $table->foreignUuid('ditangani_oleh')->nullable()->constrained('users');
            $table->string('kategori')->nullable();
            $table->string('prioritas')->nullable();
            $table->string('subjek')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users');
            $table->string('aksi')->nullable();
            $table->string('entity_type')->nullable();
            $table->uuid('entity_id')->nullable();
            $table->json('data_sebelum')->nullable();
            $table->json('data_sesudah')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluasi_instruktur', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kelas_id')->constrained('kelas');
            $table->foreignUuid('siswa_id')->constrained('siswa');
            $table->foreignUuid('instruktur_id')->constrained('users');
            $table->decimal('skor_rata_rata', 5, 2)->nullable();
            $table->json('jawaban_kuesioner')->nullable();
            $table->text('saran_ulasan')->nullable();
            $table->timestamps();

            $table->unique(['kelas_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluasi_instruktur');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('tiket_keluhan');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('arsip_laporan');
        Schema::dropIfExists('maintenance_aset');
        Schema::dropIfExists('peminjaman_item_aset');
        Schema::dropIfExists('item_kit_robotik');
        Schema::dropIfExists('aset_robotik');
        Schema::dropIfExists('sertifikat');
        Schema::dropIfExists('forum_komentar');
        Schema::dropIfExists('forum_topik');
        Schema::dropIfExists('progress_akademik');
        Schema::dropIfExists('pengumpulan_tugas');
        Schema::dropIfExists('tugas');
        Schema::dropIfExists('materi_pembelajaran');
        Schema::dropIfExists('kehadiran');
        Schema::dropIfExists('sesi_live');
        Schema::dropIfExists('enrollment_kelas');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('invoice');
        Schema::dropIfExists('dokumen_pendaftaran');
        Schema::dropIfExists('riwayat_status_pendaftaran');
        Schema::dropIfExists('pendaftaran');
        Schema::dropIfExists('calon_peserta');
        Schema::dropIfExists('batch');
        Schema::dropIfExists('materi_program');
        Schema::dropIfExists('program_kursus');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
