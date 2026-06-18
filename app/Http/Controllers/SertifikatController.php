<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Support\Facades\Auth;

class SertifikatController extends Controller
{
    /**
     * PBI-127: Halaman sertifikat milik siswa yang sedang login.
     */
    public function milikku()
    {
        $user        = Auth::user();
        $sertifikats = collect();
        $bukanSiswa  = false;

        if ($user && $user->siswa) {
            $sertifikats = Sertifikat::with([
                'siswa.user',
                'kelas.batch.program',
                'kelas.sesiLive',
                'penerbit',
            ])
                ->where('siswa_id', $user->siswa->id)
                ->orderByDesc('tanggal_terbit')
                ->get();
        } elseif ($user && !$user->siswa) {
            // User login tapi bukan siswa (admin/direktur/instruktur)
            $bukanSiswa = true;
        }

        $sertifikat = $sertifikats->first();

        return view('sertifikat.show', compact('sertifikat', 'sertifikats', 'bukanSiswa'));
    }

    /**
     * PBI-128: Verifikasi publik sertifikat berdasarkan nomor.
     */
    public function verifikasi(string $nomor)
    {
        $sertifikat = Sertifikat::with(['siswa.user', 'kelas.batch.program', 'penerbit'])
            ->where('nomor_sertifikat', $nomor)
            ->firstOrFail();

        return view('sertifikat.verifikasi', compact('sertifikat'));
    }
}
