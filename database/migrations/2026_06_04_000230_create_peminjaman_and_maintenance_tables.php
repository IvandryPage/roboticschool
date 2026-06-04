<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_item_aset', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('item_kit_id')->constrained('item_kit_robotik');
            $table->timestamp('tanggal_pinjam')->nullable();
            $table->timestamp('tanggal_jatuh_tempo')->nullable();
            $table->timestamp('tanggal_kembali')->nullable();
            $table->string('status')->nullable();
            $table->string('kondisi_awal')->nullable();
            $table->string('kondisi_akhir')->nullable();
            $table->foreignUuid('diverifikasi_oleh')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('maintenance_aset', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('item_kit_id')->constrained('item_kit_robotik');
            $table->foreignUuid('dilaporkan_oleh')->constrained('users');
            $table->foreignUuid('ditangani_oleh')->nullable()->constrained('users');
            $table->timestamp('tanggal_lapor')->nullable();
            $table->text('deskripsi_kerusakan')->nullable();
            $table->string('status')->nullable();
            $table->decimal('biaya', 12, 2)->nullable();
            $table->timestamp('selesai_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_aset');
        Schema::dropIfExists('peminjaman_item_aset');
    }
};
