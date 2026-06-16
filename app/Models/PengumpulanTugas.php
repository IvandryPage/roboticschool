<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    protected $fillable = [
        'tugas_id', 'siswa_id', 'file_jawaban', 'nilai', 'umpan_balik', 'status_penilaian'
    ];

    public function tugas() {
        return $this->belongsTo(Tugas::class);
    }

    public function siswa() {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}