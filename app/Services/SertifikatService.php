<?php

namespace App\Services;

use App\Models\Sertifikat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SertifikatService
{
    /**
     * PBI-121: Syarat kelulusan
     * - Persentase kehadiran minimal 75%
     * - Rata-rata nilai tugas minimal 70
     * - Status enrollment harus 'Selesai'
     */
    public const SYARAT_KEHADIRAN_MIN = 75;
    public const SYARAT_NILAI_MIN     = 70;

    /**
     * PBI-125 & PBI-126: Terbitkan sertifikat dengan nomor otomatis.
     */
    public function terbitkanSertifikat(string $siswaId, string $kelasId, string $penerbitId): Sertifikat
    {
        return DB::transaction(function () use ($siswaId, $kelasId, $penerbitId) {
            // Cek apakah sertifikat sudah pernah diterbitkan
            $sudahAda = Sertifikat::where('siswa_id', $siswaId)
                ->where('kelas_id', $kelasId)
                ->exists();

            if ($sudahAda) {
                throw new \Exception('Sertifikat untuk siswa ini di kelas ini sudah pernah diterbitkan.');
            }

            // PBI-126: Generate Nomor Sertifikat Otomatis (Format: RBN-TAHUN-URUTAN)
            // Gunakan subquery untuk menghindari FOR UPDATE + aggregate yang tidak didukung PostgreSQL
            $tahun = Carbon::now()->format('Y');
            $jumlahSertifikatTahunIni = Sertifikat::whereYear('tanggal_terbit', $tahun)->count();
            $nomorUrut       = str_pad($jumlahSertifikatTahunIni + 1, 3, '0', STR_PAD_LEFT);
            $nomorSertifikat = "RBN-{$tahun}-{$nomorUrut}";

            $verifiedUrl = url('/sertifikat/verifikasi/' . urlencode($nomorSertifikat));

            // PBI-125: Simpan data sertifikat ke database
            return Sertifikat::create([
                'nomor_sertifikat' => $nomorSertifikat,
                'siswa_id'         => $siswaId,
                'kelas_id'         => $kelasId,
                'diterbitkan_oleh' => $penerbitId,  // FIX: nama kolom yang benar
                'tanggal_terbit'   => Carbon::now()->toDateTimeString(),
                'verified_url'     => $verifiedUrl,
            ]);
        });
    }
}