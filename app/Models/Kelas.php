<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kelas extends Model
{
    protected $table = 'kelas';
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
        'id', 'batch_id', 'nama_kelas',
        'instruktur_id', 'kapasitas', 'status',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function instruktur()
    {
        return $this->belongsTo(User::class, 'instruktur_id');
    }

    public function enrollments()
    {
        return $this->hasMany(EnrollmentKelas::class, 'kelas_id');
    }

    public function sertifikat()
    {
        return $this->hasMany(Sertifikat::class, 'kelas_id');
    }
}