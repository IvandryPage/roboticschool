<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SesiLive extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'sesi_live';

    protected $fillable = ['id', 'kelas_id', 'nomor_sesi', 'judul_sesi', 'tanggal', 'jam_mulai', 'jam_selesai', 'platform', 'link_akses', 'keterangan'];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function kehadiran(): HasMany
    {
        return $this->hasMany(Kehadiran::class, 'sesi_id');
    }

    public function materiPembelajaran(): HasMany
    {
        return $this->hasMany(MateriPembelajaran::class, 'sesi_id');
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'sesi_id');
    }
}
