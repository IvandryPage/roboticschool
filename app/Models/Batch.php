<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Batch extends Model
{
    protected $table = 'batch';
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
        'id', 'program_id', 'nama_batch',
        'tanggal_mulai', 'tanggal_selesai', 'status',
    ];

    public function program()
    {
        return $this->belongsTo(ProgramKursus::class, 'program_id');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'batch_id');
    }
}