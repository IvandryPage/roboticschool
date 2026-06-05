<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramKursus extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'program_kursus';

    protected $fillable = [
        'id', 'nama_program', 'deskripsi', 'level', 'biaya', 'durasi_minggu', 'gambar', 'status_tampil',
    ];

    public function materiProgram(): HasMany
    {
        return $this->hasMany(MateriProgram::class, 'program_id');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class, 'program_id');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'program_id');
    }
}
