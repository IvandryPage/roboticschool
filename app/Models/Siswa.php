<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\EnrollmentKelas;
use App\Models\Kehadiran;
use App\Models\PengumpulanTugas;
use App\Models\ProgressAkademik;
use App\Models\Sertifikat;
use App\Models\EvaluasiInstruktur;

class Siswa extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'siswa';

    protected $fillable = ['id','user_id','pendaftaran_id','tanggal_lahir','jenis_kelamin','alamat'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function enrollmentKelas(): HasMany
    {
        return $this->hasMany(EnrollmentKelas::class);
    }

    public function kehadiran(): HasMany
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function pengumpulanTugas(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    public function progressAkademik(): HasMany
    {
        return $this->hasMany(ProgressAkademik::class);
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
