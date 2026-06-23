<?php

namespace App\Models;

use Database\Factories\SertifikatFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertifikat extends Model
{
    /** @use HasFactory<SertifikatFactory> */
    use HasFactory, HasUuids;

    protected $table = 'sertifikat';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'siswa_id', 'kelas_id',
        'nomor_sertifikat', 'file_path', 'qr_code',
        'verified_url', 'tanggal_terbit', 'diterbitkan_oleh',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function penerbit(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterbitkan_oleh');
    }
}