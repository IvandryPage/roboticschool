<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix schema tabel sertifikat:
 * Production DB masih pakai schema lama (id bigint, penerbit_id, tanpa uuid/verified_url/qr_code/file_path)
 * Migration ini drop & recreate agar sesuai dengan model & service.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Drop tabel lama terlebih dahulu (aman karena data test akan diisi ulang oleh seeder)
        Schema::dropIfExists('sertifikat');

        Schema::create('sertifikat', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignUuid('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('nomor_sertifikat')->unique();
            $table->string('file_path')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('verified_url')->nullable();
            $table->timestamp('tanggal_terbit')->nullable();
            $table->foreignUuid('diterbitkan_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['siswa_id', 'kelas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};
