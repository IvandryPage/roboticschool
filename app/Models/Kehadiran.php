<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// ==========================================
// IMPORT MODEL YANG BENAR SESUAI SCREENSHOT
// ==========================================
use App\Models\SesiLive; // <--- INI BIANG KEROKNYA, kita ganti jadi SesiLive
use App\Models\Siswa;
use App\Models\User;

class Kehadiran extends Model
{
    use HasFactory;

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

    // --- RELASI PBI 111 ---

    public function sesi(): BelongsTo
    {
        // Ubah Sesi::class menjadi SesiLive::class di sini
        return $this->belongsTo(SesiLive::class, 'sesi_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}