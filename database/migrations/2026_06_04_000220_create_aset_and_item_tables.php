<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset_robotik', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_aset')->unique();
            $table->string('nama_kit');
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->nullable();
            $table->integer('stok_minimal')->nullable();
            $table->timestamps();
        });

        Schema::create('item_kit_robotik', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('aset_id')->constrained('aset_robotik');
            $table->string('serial_number')->unique();
            $table->string('status_kondisi')->nullable();
            $table->string('lokasi_rak')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_kit_robotik');
        Schema::dropIfExists('aset_robotik');
    }
};
