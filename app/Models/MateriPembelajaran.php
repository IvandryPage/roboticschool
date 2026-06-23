<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // WAJIB DITAMBAHKAN 1

class MateriPembelajaran extends Model
{
    use HasUuids; // WAJIB DITAMBAHKAN 2

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
        // Ubah Sesi::class menjadi SesiLive::class
        return $this->belongsTo(SesiLive::class, 'sesi_id'); 
    }
}