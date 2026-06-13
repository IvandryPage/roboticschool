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
}
