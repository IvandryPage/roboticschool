<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriPembelajaran extends Model
{
    // Memaksa Laravel agar menggunakan nama tabel tanpa imbuhan 's'
    protected $table = 'materi_pembelajaran';

    // Mendaftarkan kolom yang diizinkan untuk diisi
    protected $fillable = [
        'sesi_id',
        'judul',
        'tipe_konten',
        'file_path_atau_url',
        'urutan',
        'keterangan',
    ];

    /**
     * Relasi ke tabel Sesi
     * (Sesuaikan 'Sesi::class' dengan nama model sesimu yang sebenarnya, misalnya 'SesiLive::class' atau 'Jadwal::class')
     */
    public function sesi()
    {
        return $this->belongsTo(Sesi::class, 'sesi_id');
    }
}