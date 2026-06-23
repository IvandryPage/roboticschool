<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_program', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('program_id')
                ->constrained('program_kursus')
                ->cascadeOnDelete();
            $table->integer('nomor_urut');
            $table->string('judul_materi');
            $table->text('deskripsi_materi')->nullable();
            $table->timestamps();

            $table->unique(['program_id', 'nomor_urut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_program');
    }
};
