<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sertifikat extends Model
{
    protected $table = 'sertifikat';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'id', 'siswa_id', 'kelas_id',
        'nomor_sertifikat', 'file_path', 'qr_code',
        'verified_url', 'tanggal_terbit', 'diterbitkan_oleh',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function penerbit()
    {
        return $this->belongsTo(User::class, 'diterbitkan_oleh');
    }
}