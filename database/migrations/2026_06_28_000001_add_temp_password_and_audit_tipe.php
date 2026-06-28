<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Simpan password sementara di pendaftaran untuk ditampilkan ke calon peserta
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->string('temp_password')->nullable()->after('status');
        });

        // Kolom tipe untuk filter Direktur (hanya lihat log bisnis)
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('tipe')->default('bisnis')->after('aksi');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn('temp_password');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
