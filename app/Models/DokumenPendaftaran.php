<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Pendaftaran;

class DokumenPendaftaran extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'dokumen_pendaftaran';
    protected $fillable = ['id','pendaftaran_id','jenis_dokumen','nama_file','file_path','versi','status_verifikasi','catatan','uploaded_at','updated_at'];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
