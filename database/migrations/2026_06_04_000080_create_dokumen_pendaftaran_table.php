<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_pendaftaran');
    }
};
