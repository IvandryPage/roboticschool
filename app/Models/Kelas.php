<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = []; 

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function sesiLive(): HasMany
    {
        return $this->hasMany(SesiLive::class, 'kelas_id');
    }

    public function instruktur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instruktur_id');
    }

    /** * Jembatan Otomatis Hubungan Kelas ke Pendaftaran Siswa
     * Kode ini otomatis mendeteksi nama model pendaftaran di sistem kamu agar anti-error.
     */
    public function enrollmentKelas(): HasMany
    {
        $modelTarget = \App\Models\EnrollmentKelas::class; // Opsi 1

        if (!class_exists($modelTarget)) {
            if (class_exists(\App\Models\Enrollment::class)) {
                $modelTarget = \App\Models\Enrollment::class; // Opsi 2
            } elseif (class_exists(\App\Models\Pendaftaran::class)) {
                $modelTarget = \App\Models\Pendaftaran::class; // Opsi 3
            }
        }

        return $this->hasMany($modelTarget, 'kelas_id');
    }
}