<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tugas extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'tugas';

    protected $fillable = ['id', 'sesi_id', 'judul_tugas', 'deskripsi', 'file_soal', 'batas_waktu', 'nilai_maksimum'];

    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiLive::class, 'sesi_id');
    }

    public function pengumpulanTugas(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }
}
