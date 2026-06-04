<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Batch;
use App\Models\User;
use App\Models\EnrollmentKelas;
use App\Models\SesiLive;
use App\Models\ProgressAkademik;
use App\Models\ForumTopik;
use App\Models\Sertifikat;
use App\Models\EvaluasiInstruktur;

class Kelas extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'kelas';

    protected $fillable = ['id','batch_id','nama_kelas','instruktur_id','kapasitas','status'];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function instruktur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instruktur_id');
    }

    public function enrollmentKelas(): HasMany
    {
        return $this->hasMany(EnrollmentKelas::class);
    }

    public function sesiLive(): HasMany
    {
        return $this->hasMany(SesiLive::class);
    }

    public function progressAkademik(): HasMany
    {
        return $this->hasMany(ProgressAkademik::class);
    }

    public function forumTopik(): HasMany
    {
        return $this->hasMany(ForumTopik::class);
    }

    public function sertifikat(): HasMany
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function evaluasiInstruktur(): HasMany
    {
        return $this->hasMany(EvaluasiInstruktur::class);
    }
}
