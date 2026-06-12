<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_live', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kelas_id')->constrained('kelas');
            $table->integer('nomor_sesi');
            $table->string('judul_sesi')->nullable();
            $table->date('tanggal')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('platform')->nullable();
            $table->string('link_akses')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['kelas_id', 'nomor_sesi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_live');
    }
};
