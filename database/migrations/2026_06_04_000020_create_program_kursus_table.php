<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kursus');
    }
};
