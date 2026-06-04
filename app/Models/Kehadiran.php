<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SesiLive;
use App\Models\Siswa;
use App\Models\User;

class Kehadiran extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'kehadiran';
    protected $fillable = ['id','sesi_id','siswa_id','status_hadir','catatan','dicatat_oleh','waktu_pencatatan'];

    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiLive::class, 'sesi_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
