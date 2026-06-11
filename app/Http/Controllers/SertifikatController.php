<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    public function verifikasi($nomor)
    {
        // Cari sertifikat berdasarkan nomor uniknya
        $sertifikat = Sertifikat::with(['siswa.user', 'kelas.batch.programKursus'])
            ->where('nomor_sertifikat', $nomor)
            ->firstOrFail(); // Kalau tidak ketemu langsung otomatis error 404

        // Mengembalikan tampilan ke folder resources/views/sertifikat/verifikasi.blade.php
        return view('sertifikat.verifikasi', compact('sertifikat'));
    }
}
