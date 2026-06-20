<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('instruktur', function (Blueprint $table) {
        $table->id();
        $table->string('nama_lengkap'); // Kolom wajib
        $table->string('email')->unique()->nullable();
        $table->string('spesialisasi')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instruktur');
    }
};
