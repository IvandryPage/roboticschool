<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
