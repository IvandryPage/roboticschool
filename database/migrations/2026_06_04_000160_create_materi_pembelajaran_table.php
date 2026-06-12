<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_pembelajaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sesi_id')->constrained('sesi_live');
            $table->string('judul')->nullable();
            $table->string('tipe_konten')->nullable();
            $table->string('file_path_atau_url')->nullable();
            $table->integer('urutan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['sesi_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_pembelajaran');
    }
};
