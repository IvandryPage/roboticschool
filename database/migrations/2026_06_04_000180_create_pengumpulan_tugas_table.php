<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
