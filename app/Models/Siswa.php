<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Siswa extends Model
{
    protected $table = 'siswa';
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
        'id', 'user_id', 'pendaftaran_id',
        'tanggal_lahir', 'jenis_kelamin', 'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(EnrollmentKelas::class, 'siswa_id');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'siswa_id');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'siswa_id');
    }

    public function sertifikat()
    {
        return $this->hasMany(Sertifikat::class, 'siswa_id');
    }
}
