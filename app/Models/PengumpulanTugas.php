<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PengumpulanTugas extends Model
{
    protected $table = 'pengumpulan_tugas';
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
        'id', 'tugas_id', 'siswa_id',
        'file_jawaban', 'catatan_siswa', 'waktu_kumpul',
        'nilai', 'umpan_balik', 'status_penilaian',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}