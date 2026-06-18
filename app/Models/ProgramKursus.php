<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKursus extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'program_kursus'; // INI KUNCI AGAR TIDAK ERROR "UNDEFINED TABLE"
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'nama_program', 'deskripsi', 'level', 'biaya', 'durasi_minggu', 'gambar', 'status_tampil',
    ];
}