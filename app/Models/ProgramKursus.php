<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProgramKursus extends Model
{
    protected $table = 'program_kursus';
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
        'id', 'nama_program', 'deskripsi',
        'level', 'biaya', 'durasi_minggu',
        'gambar', 'status_tampil',
    ];

    public function batch()
    {
        return $this->hasMany(Batch::class, 'program_id');
    }
}