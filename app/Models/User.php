<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements PasskeyUser, FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'name',
        'email',
        'no_hp',
        'password',
        'foto_profil',
        'role_id',
        'status_aktif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status_aktif' => 'boolean',
    ];

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $source = $this->nama_lengkap = $this->name ?? '';

        return Str::of($source)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Tentukan apakah user bisa mengakses Filament panel.
     * Semua user yang status_aktif = true boleh masuk ke admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->status_aktif;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(RiwayatStatusPendaftaran::class, 'diubah_oleh');
    }

    public function forumTopik(): HasMany
    {
        return $this->hasMany(ForumTopik::class, 'pembuat_id');
    }

    public function forumKomentar(): HasMany
    {
        return $this->hasMany(ForumKomentar::class, 'user_id');
    }

    public function kelasInstruktur(): HasMany
    {
        return $this->hasMany(Kelas::class, 'instruktur_id');
    }

    public function peminjamanItemAset(): HasMany
    {
        return $this->hasMany(PeminjamanItemAset::class);
    }

    public function maintenanceDilaporkan(): HasMany
    {
        return $this->hasMany(MaintenanceAset::class, 'dilaporkan_oleh');
    }

    public function maintenanceDitangani(): HasMany
    {
        return $this->hasMany(MaintenanceAset::class, 'ditangani_oleh');
    }

    public function arsipLaporan(): HasMany
    {
        return $this->hasMany(ArsipLaporan::class, 'dibuat_oleh');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function tiketDilaporkan(): HasMany
    {
        return $this->hasMany(TiketKeluhan::class, 'pelapor_id');
    }

    public function tiketDitangani(): HasMany
    {
        return $this->hasMany(TiketKeluhan::class, 'ditangani_oleh');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function evaluasiInstruktur(): HasMany
    {
        return $this->hasMany(EvaluasiInstruktur::class, 'instruktur_id');
    }
}
