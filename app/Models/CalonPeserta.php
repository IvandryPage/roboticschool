<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Pendaftaran;

class CalonPeserta extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'calon_peserta';

    protected $fillable = ['id','nama_lengkap','email','no_hp','asal_sekolah_atau_instansi','jenjang_pendidikan'];

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'calon_peserta_id');
    }
}
