<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengumpulanTugas extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'pengumpulan_tugas';

    // Kolom disesuaikan dengan file migration PBI-094
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

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}