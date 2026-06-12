<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_kelas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kelas_id')->constrained('kelas');
            $table->foreignUuid('siswa_id')->constrained('siswa');
            $table->timestamp('tanggal_bergabung')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->unique(['kelas_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_kelas');
    }
};
