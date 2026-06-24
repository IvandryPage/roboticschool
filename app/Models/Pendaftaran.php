<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Pendaftaran extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'pendaftaran';

    protected $fillable = ['id', 'calon_peserta_id', 'program_id', 'no_referensi', 'tanggal_daftar', 'status', 'catatan_admin'];

    /**
     * PBI-145: Otomatisasi Penerbitan Invoice
     * Saat status pendaftaran diubah menjadi 'Diterima' oleh Admin,
     * sistem otomatis menerbitkan invoice baru (jika belum ada).
     */
    protected static function booted(): void
    {
        static::updated(function (Pendaftaran $pendaftaran) {
            // Hanya jalan kalau kolom 'status' yang berubah, dan nilainya sekarang 'Diterima'
            if (! $pendaftaran->wasChanged('status')) {
                return;
            }

            // Trigger invoice saat status disetujui (konsisten dengan alur admin)
            if (! in_array($pendaftaran->status, ['disetujui', 'Diterima'])) {
                return;
            }

            // Cegah duplikasi: kalau invoice untuk pendaftaran ini sudah ada, jangan buat lagi
            if ($pendaftaran->invoice()->exists()) {
                return;
            }

            // Ambil biaya dari program kursus terkait
            $program = $pendaftaran->program;
            $totalTagihan = $program?->biaya ?? 0;

            Invoice::create([
                'id'                  => (string) Str::uuid(),
                'pendaftaran_id'      => $pendaftaran->id,
                'no_invoice'          => self::generateNomorInvoice(),
                'total_tagihan'       => $totalTagihan,
                'tanggal_terbit'      => now(),
                'tanggal_jatuh_tempo' => now()->addDays(7),
                'status_pembayaran'   => 'Pending',
            ]);
        });
    }

    /**
     * Generate nomor invoice berurutan, contoh: INV-000001, INV-000002, dst.
     * Diambil dari nomor invoice terakhir yang tersimpan di database.
     */
    protected static function generateNomorInvoice(): string
    {
        $invoiceTerakhir = Invoice::orderByDesc('created_at')->first();

        $nomorBerikutnya = 1;

        if ($invoiceTerakhir && preg_match('/(\d+)$/', $invoiceTerakhir->no_invoice, $match)) {
            $nomorBerikutnya = ((int) $match[1]) + 1;
        }

        return 'INV-' . str_pad((string) $nomorBerikutnya, 6, '0', STR_PAD_LEFT);
    }

    public function calonPeserta(): BelongsTo
    {
        return $this->belongsTo(CalonPeserta::class, 'calon_peserta_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramKursus::class, 'program_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'pendaftaran_id');
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'pendaftaran_id');
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatusPendaftaran::class, 'pendaftaran_id');
    }

    public function dokumenPendaftaran(): HasMany
    {
        return $this->hasMany(DokumenPendaftaran::class, 'pendaftaran_id');
    }
}