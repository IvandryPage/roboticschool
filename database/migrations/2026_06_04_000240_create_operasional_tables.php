<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_instruktur');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('tiket_keluhan');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('arsip_laporan');
    }
};
