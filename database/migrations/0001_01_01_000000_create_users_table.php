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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('no_hp')->nullable();
            $table->string('password');
            $table->string('foto_profil')->nullable();
            $table->uuid('role_id')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        // Note: password reset tokens and sessions tables removed —
        // not required by the current database specification.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
