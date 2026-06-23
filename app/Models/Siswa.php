<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Tambahan untuk relasi

class Siswa extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'siswa';

    protected $fillable = ['id', 'user_id', 'pendaftaran_id', 'tanggal_lahir', 'jenis_kelamin', 'alamat'];

    // --- AKSESORI ---
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->name : 'Nama Tidak Ditemukan';
    }

    // --- RELASI ---
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function enrollmentKelas(): HasMany
    {
        return $this->hasMany(EnrollmentKelas::class);
    }

    // TAMBAHAN BARU: Jembatan pencarian untuk relasi kelas
    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'enrollment_kelas', 'siswa_id', 'kelas_id');
    }

    public function kehadiran(): HasMany
    {
        return $this->hasMany(Kehadiran::class, 'siswa_id');
    }

    public function pengumpulanTugas(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class, 'siswa_id');
    }

    public function progressAkademik(): HasMany
    {
        return $this->hasMany(ProgressAkademik::class, 'siswa_id');
    }

    public function sertifikat(): HasMany
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function evaluasiInstruktur(): HasMany
    {
        return $this->hasMany(EvaluasiInstruktur::class);
    }

    // --- LOGIKA PBI 110: SINKRONISASI PERMANEN ---
    public function sinkronkanProgressAkademik()
    {
        // 1. Hitung Kehadiran
        $totalPertemuan = $this->kehadiran()->count();
        $jumlahHadir = $this->kehadiran()->where('status', 'Hadir')->count();
        $persentaseKehadiran = ($totalPertemuan > 0) ? ($jumlahHadir / $totalPertemuan) * 100 : 0;

        // 2. Hitung Rata-Rata Nilai
        $rataRataTugas = $this->pengumpulanTugas()->avg('nilai') ?? 0;

        // 3. Kalkulasi (30% Kehadiran, 70% Tugas)
        $progressFinal = ($persentaseKehadiran * 0.30) + ($rataRataTugas * 0.70);

        // 4. Update ke tabel progress_akademik
        // Catatan: Jika siswa memiliki lebih dari satu data di progress_akademik, 
        // gunakan where/first untuk memilih record yang tepat.
        return $this->progressAkademik()->updateOrCreate(
            ['siswa_id' => $this->id], // Syarat pencarian
            [
                'persentase_kehadiran' => $persentaseKehadiran,
                'nilai_rata_rata'      => $rataRataTugas,
                'nilai_progress_akhir' => $progressFinal,
            ]
        );
    }
}