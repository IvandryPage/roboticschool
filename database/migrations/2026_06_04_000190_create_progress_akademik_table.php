<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_akademik');
    }
};
