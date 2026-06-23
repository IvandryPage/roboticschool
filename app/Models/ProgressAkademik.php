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

    // FIX ERROR 9: sesuaikan dengan kolom yang ada di migration
    // Hapus: program_id, nilai_rata_rata, catatan (tidak ada di tabel)
    // Tambah: persentase_kehadiran, rata_nilai_tugas, persentase_penyelesaian
    protected $fillable = [
        'id',
        'siswa_id',
        'kelas_id',
        'persentase_kehadiran',
        'rata_nilai_tugas',
        'persentase_penyelesaian',
        'status',
    ];

    protected $casts = [
        'persentase_kehadiran'    => 'decimal:2',
        'rata_nilai_tugas'        => 'decimal:2',
        'persentase_penyelesaian' => 'decimal:2',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
