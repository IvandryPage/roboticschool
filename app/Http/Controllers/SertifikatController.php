<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Support\Facades\Auth;

class SertifikatController extends Controller
{
    /**
     * PBI-127: Halaman sertifikat milik siswa yang sedang login.
     * Hanya bisa diakses oleh user dengan role Siswa.
     * Non-siswa akan di-redirect ke /admin dengan pesan peringatan.
     */
    public function milikku()
    {
        $user = Auth::user();

        // PBI-127 Revisi: Pisahkan akses berdasarkan role
        // Non-siswa (Admin/Direktur/Instruktur) redirect ke admin panel
        if ($user && !$user->siswa) {
            return redirect('/admin')->with(
                'warning',
                'Halaman sertifikat hanya dapat diakses oleh siswa. Anda login sebagai ' .
                ($user->role?->nama_role ?? 'Non-Siswa') . '.'
            );
        }

        $sertifikats = collect();

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
        }

        $sertifikat = $sertifikats->first();

        return view('sertifikat.show', compact('sertifikat', 'sertifikats'));
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
