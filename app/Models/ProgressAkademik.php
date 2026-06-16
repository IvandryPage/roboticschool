<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressAkademik extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'progress_akademik';

    // TAMBAHKAN kolom-kolom hasil hitungan PBI 110 ke $fillable
    protected $fillable = [
        'id', 
        'siswa_id', 
        'program_id', 
        'kelas_id', 
        'status', 
        'nilai_rata_rata', 
        'catatan',
        'persentase_kehadiran', // Tambahan PBI 110
        'nilai_progress_akhir'   // Tambahan PBI 110
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramKursus::class, 'program_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}