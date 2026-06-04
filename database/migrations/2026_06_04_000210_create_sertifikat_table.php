<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};
