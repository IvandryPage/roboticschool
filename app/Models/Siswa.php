<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'siswa';

    protected $fillable = ['id', 'user_id', 'pendaftaran_id', 'tanggal_lahir', 'jenis_kelamin', 'alamat'];

    // --- TAMBAHAN: Aksesori untuk mempermudah panggil nama ---
    // Sekarang Anda bisa memanggil $siswa->nama dan akan otomatis mengambil dari user->name
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

    public function kehadiran(): HasMany
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function pengumpulanTugas(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    public function progressAkademik(): HasMany
    {
        return $this->hasMany(ProgressAkademik::class);
    }

    public function sertifikat(): HasMany
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function evaluasiInstruktur(): HasMany
    {
        return $this->hasMany(EvaluasiInstruktur::class);
    }
}