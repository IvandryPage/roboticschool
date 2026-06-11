<?php

namespace App\Services;

use App\Models\Sertifikat;
use App\Models\EnrollmentKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SertifikatService
{
    public function terbitkanSertifikat($siswaId, $kelasId, $penerbitId)
    {
        return DB::transaction(function () use ($siswaId, $kelasId, $penerbitId) {
            // PBI-126: Generate Nomor Sertifikat Otomatis (Format: RBN-TAHUN-URUTAN)
            $tahun = Carbon::now()->format('Y');
            $jumlahSertifikatTahunIni = Sertifikat::whereYear('tanggal_terbit', $tahun)->count();
            $nomorUrut = str_pad($jumlahSertifikatTahunIni + 1, 3, '0', STR_PAD_LEFT);
            $nomorSertifikat = "RBN-{$tahun}-{$nomorUrut}";

            // PBI-125: Simpan data sertifikat ke database
            return Sertifikat::create([
                'nomor_sertifikat' => $nomorSertifikat,
                'siswa_id' => $siswaId,
                'kelas_id' => $kelasId,
                'penerbit_id' => $penerbitId,
                'tanggal_terbit' => Carbon::now()->toDateString(),
            ]);
        });
    }
}