<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pendaftaran extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'pendaftaran';

    protected $fillable = ['id', 'calon_peserta_id', 'program_id', 'no_referensi', 'tanggal_daftar', 'status', 'catatan_admin'];

    public function calonPeserta(): BelongsTo
    {
        return $this->belongsTo(CalonPeserta::class, 'calon_peserta_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramKursus::class, 'program_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'pendaftaran_id');
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'pendaftaran_id');
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatusPendaftaran::class, 'pendaftaran_id');
    }

    public function dokumenPendaftaran(): HasMany
    {
        return $this->hasMany(DokumenPendaftaran::class, 'pendaftaran_id');
    }
}
