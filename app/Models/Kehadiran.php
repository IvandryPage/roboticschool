<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Import Model
use App\Models\SesiLive;
use App\Models\Siswa;
use App\Models\User;

class Kehadiran extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'kehadiran';

    protected $fillable = [
        'id',
        'sesi_id',
        'siswa_id',
        'status_hadir',
        'catatan',
        'dicatat_oleh',
        'waktu_pencatatan',
    ];

    // --- LOGIKA OTOMATIS (PBI 111) ---
    protected static function booted()
    {
        // Setiap kali data kehadiran disimpan atau dihapus, update progress akademik siswanya
        static::saved(function ($kehadiran) {
            $kelasId = $kehadiran->sesi?->kelas_id;
            $kehadiran->siswa?->sinkronkanProgressAkademik($kelasId);
        });

        static::deleted(function ($kehadiran) {
            $kelasId = $kehadiran->sesi?->kelas_id;
            $kehadiran->siswa?->sinkronkanProgressAkademik($kelasId);
        });
    }   

    // --- RELASI MODEL ---

    // Disediakan dua versi (sesi & sesiLive) agar ProgresController dan Filament sama-sama work!
    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiLive::class, 'sesi_id');
    }

    public function sesiLive(): BelongsTo
    {
        return $this->belongsTo(SesiLive::class, 'sesi_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Disediakan dua versi (pencatat & dicatatOleh) agar tidak merusak komponen Filament yang memanggilnya
    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}