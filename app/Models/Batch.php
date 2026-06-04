<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProgramKursus;
use App\Models\Kelas;

class Batch extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'batch';

    protected $fillable = [
        'id', 'program_id', 'nama_batch', 'tanggal_mulai', 'tanggal_selesai', 'kuota_max', 'status_aktif'
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramKursus::class, 'program_id');
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'batch_id');
    }
}
