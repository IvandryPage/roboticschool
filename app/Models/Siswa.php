<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Tambahan untuk relasi

class Siswa extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'siswa';

    protected $fillable = ['id', 'user_id', 'pendaftaran_id', 'nama_lengkap', 'email', 'no_hp', 'asal_sekolah', 'tanggal_bergabung', 'status_akun', 'tanggal_lahir', 'jenis_kelamin', 'alamat'];

    protected static function booted()
    {
        static::saving(function ($siswa) {
            $virtualAttributes = [
                'nama_lengkap',
                'email',
                'no_hp',
                'status_akun',
                'asal_sekolah',
                'tanggal_bergabung',
            ];

            $extracted = [];
            foreach ($virtualAttributes as $key) {
                if (array_key_exists($key, $siswa->attributes)) {
                    $extracted[$key] = $siswa->attributes[$key];
                    unset($siswa->attributes[$key]);
                }
            }

            if (!empty($extracted)) {
                $user = $siswa->user ?: \App\Models\User::find($siswa->user_id);
                if ($user) {
                    $userUpdates = [];
                    if (isset($extracted['nama_lengkap'])) {
                        $user->nama_lengkap = $extracted['nama_lengkap'];
                        $userUpdates['nama_lengkap'] = $extracted['nama_lengkap'];
                    }
                    if (isset($extracted['email'])) {
                        $user->email = $extracted['email'];
                        $userUpdates['email'] = $extracted['email'];
                    }
                    if (isset($extracted['no_hp'])) {
                        $user->no_hp = $extracted['no_hp'];
                        $userUpdates['no_hp'] = $extracted['no_hp'];
                    }
                    if (isset($extracted['status_akun'])) {
                        $user->status_aktif = $extracted['status_akun'] === 'aktif';
                        $userUpdates['status_aktif'] = $extracted['status_akun'] === 'aktif';
                    }
                    if (!empty($userUpdates)) {
                        $user->save();
                    }
                }

                if (isset($extracted['asal_sekolah'])) {
                    $pendaftaran = $siswa->pendaftaran ?: \App\Models\Pendaftaran::find($siswa->pendaftaran_id);
                    if ($pendaftaran && $pendaftaran->calonPeserta) {
                        $pendaftaran->calonPeserta->asal_sekolah_atau_instansi = $extracted['asal_sekolah'];
                        $pendaftaran->calonPeserta->save();
                    }
                }
            }
        });
    }

    // --- AKSESORI ---
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->name : 'Nama Tidak Ditemukan';
    }

    public function getNamaLengkapAttribute()
    {
        return $this->user?->nama_lengkap ?? $this->user?->name;
    }

    public function getEmailAttribute()
    {
        return $this->user?->email;
    }

    public function getNoHpAttribute()
    {
        return $this->user?->no_hp;
    }

    public function getAsalSekolahAttribute()
    {
        return $this->pendaftaran?->calonPeserta?->asal_sekolah_atau_instansi;
    }

    public function getTanggalBergabungAttribute()
    {
        return $this->created_at;
    }

    public function getStatusAkunAttribute()
    {
        return ($this->user?->status_aktif ?? true) ? 'aktif' : 'nonaktif';
    }

    // --- RELASI ---
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

    // TAMBAHAN BARU: Jembatan pencarian untuk relasi kelas
    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'enrollment_kelas', 'siswa_id', 'kelas_id');
    }

    public function kehadiran(): HasMany
    {
        return $this->hasMany(Kehadiran::class, 'siswa_id');
    }

    public function pengumpulanTugas(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class, 'siswa_id');
    }

    public function progressAkademik(): HasMany
    {
        return $this->hasMany(ProgressAkademik::class, 'siswa_id');
    }

    public function sertifikat(): HasMany
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function evaluasiInstruktur(): HasMany
    {
        return $this->hasMany(EvaluasiInstruktur::class);
    }

    public function sinkronkanProgressAkademik($kelasId = null)
    {
        $kelasIds = $kelasId ? [$kelasId] : $this->kelas()->pluck('kelas.id')->toArray();

        foreach ($kelasIds as $kId) {
            // 1. Hitung Kehadiran
            $sesiIds = \App\Models\SesiLive::where('kelas_id', $kId)->pluck('id');
            $totalPertemuan = $this->kehadiran()->whereIn('sesi_id', $sesiIds)->count();
            $jumlahHadir = $this->kehadiran()->whereIn('sesi_id', $sesiIds)->where('status_hadir', 'Hadir')->count();
            $persentaseKehadiran = ($totalPertemuan > 0) ? ($jumlahHadir / $totalPertemuan) * 100 : 0;

            // 2. Hitung Rata-Rata Nilai
            $tugasIds = \App\Models\Tugas::whereIn('sesi_id', $sesiIds)->pluck('id');
            $rataRataTugas = $this->pengumpulanTugas()->whereIn('tugas_id', $tugasIds)->avg('nilai') ?? 0;

            // 3. Kalkulasi (30% Kehadiran, 70% Tugas)
            $progressFinal = ($persentaseKehadiran * 0.30) + ($rataRataTugas * 0.70);

            // 4. Update ke tabel progress_akademik
            $this->progressAkademik()->updateOrCreate(
                [
                    'siswa_id' => $this->id,
                    'kelas_id' => $kId,
                ],
                [
                    'persentase_kehadiran'    => $persentaseKehadiran,
                    'rata_nilai_tugas'        => $rataRataTugas,
                    'persentase_penyelesaian' => $progressFinal,
                ]
            );
        }
    }
}