<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('kehadiran', function (Blueprint $table) {
        // Menggunakan UUID (menyesuaikan dengan tabel Siswa/User kamu)
        $table->uuid('id')->primary();
        
        // Relasi ke tabel sesi (jadwal kelas/pertemuan)
        $table->foreignUuid('sesi_id')->constrained('sesi')->cascadeOnDelete();
        
        // Relasi ke tabel siswa
        $table->foreignUuid('siswa_id')->constrained('siswa')->cascadeOnDelete();
        
        // Enum untuk status kehadiran
        $table->enum('status_hadir', ['Hadir', 'Izin', 'Sakit', 'Alpa'])->default('Hadir');
        
        // Kolom teks opsional untuk alasan izin/sakit
        $table->text('catatan')->nullable();
        
        // Siapa instruktur/admin yang mencatat (relasi ke tabel users)
        $table->foreignUuid('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
        
        // Waktu spesifik pencatatan
        $table->timestamp('waktu_pencatatan')->useCurrent();
        
        // Kolom default bawaan Laravel (created_at & updated_at)
        $table->timestamps();
    
         $table->unique(['sesi_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};
