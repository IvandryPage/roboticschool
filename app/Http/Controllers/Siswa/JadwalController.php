<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\SesiLive;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $siswa = $user?->siswa;

        if (!$siswa) {
            return redirect('/admin');
        }

        $siswaId = $siswa->id;

        $sesiMendatang = SesiLive::with(['kelas.batch.program', 'kelas.instruktur'])
            ->whereHas('kelas.enrollmentKelas', fn($q) =>
                $q->where('siswa_id', $siswaId)->where('status', 'Aktif')
            )
            ->whereDate('tanggal', '>=', today())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        $riwayatSesi = SesiLive::with(['kelas.batch.program'])
            ->whereHas('kelas.enrollmentKelas', fn($q) =>
                $q->where('siswa_id', $siswaId)->where('status', 'Aktif')
            )
            ->whereDate('tanggal', '<', today())
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_mulai')
            ->get();

        return view('siswa.jadwal.index', compact('sesiMendatang', 'riwayatSesi'));
    }
}
