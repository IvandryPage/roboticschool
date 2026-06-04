<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SesiLive;

class MateriPembelajaran extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'materi_pembelajaran';
    protected $fillable = ['id','sesi_id','judul','tipe_konten','file_path_atau_url','urutan','keterangan'];

    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiLive::class, 'sesi_id');
    }
}
