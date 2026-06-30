<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengumpulanTugas extends Model
{
    use HasUuids; // Wajib untuk UUID

    protected $table = 'pengumpulan_tugas';

    // Pastikan semua kolom dari migrasi masuk ke sini
    protected $fillable = [
        'tugas_id', 
        'siswa_id', 
        'file_jawaban', 
        'catatan_siswa', 
        'waktu_kumpul', 
        'nilai', 
        'umpan_balik', 
        'status_penilaian'
    ];

    protected static function booted()
    {
        // Setiap kali nilai tugas diinput, diupdate, atau dihapus, jalankan hitung ulang
        static::saved(function ($pengumpulanTugas) {
            $kelasId = $pengumpulanTugas->tugas?->sesi?->kelas_id;
            $pengumpulanTugas->siswa?->sinkronkanProgressAkademik($kelasId);
        });

        static::deleted(function ($pengumpulanTugas) {
            $kelasId = $pengumpulanTugas->tugas?->sesi?->kelas_id;
            $pengumpulanTugas->siswa?->sinkronkanProgressAkademik($kelasId);
        });
    }

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    // Relasi ke Siswa (Asumsi tabel 'siswa' ada model 'Siswa')
    // Jika siswa adalah User, ganti 'Siswa::class' menjadi 'User::class'
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}