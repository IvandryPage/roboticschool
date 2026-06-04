<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
