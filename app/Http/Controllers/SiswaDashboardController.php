<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentKelas;
use App\Models\ProgressAkademik;
use App\Models\Sertifikat;
use App\Models\SesiLive;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $siswa = $user?->siswa;

        // Redirect non-siswa ke admin panel
        if (!$siswa) {
            return redirect('/admin');
        }

        // ── Kelas yang diikuti ────────────────────────────
        $enrollments = EnrollmentKelas::with([
            'kelas.batch.program',
            'kelas.instruktur',
            'kelas.sesiLive',
        ])
            ->where('siswa_id', $siswa->id)
            ->get();

        $kelasAktif   = $enrollments->where('status', 'Aktif');
        $kelasSelesai = $enrollments->where('status', 'Selesai');

        // ── Progress akademik ─────────────────────────────
        $progressList = ProgressAkademik::where('siswa_id', $siswa->id)->get();

        $rataKehadiran = $progressList->avg('persentase_kehadiran') ?? 0;
        $rataNilai     = $progressList->avg('rata_nilai_tugas') ?? 0;
        $rataSelesai   = $progressList->avg('persentase_penyelesaian') ?? 0;

        // ── Sertifikat ────────────────────────────────────
        $sertifikat = Sertifikat::where('siswa_id', $siswa->id)
            ->with('kelas.batch.program')
            ->get();

        // ── Sesi hari ini ─────────────────────────────────
        $kelasIds = $kelasAktif->pluck('kelas_id')->toArray();
        $sesiHariIni = SesiLive::whereIn('kelas_id', $kelasIds)
            ->whereDate('tanggal', today())
            ->with('kelas.batch.program')
            ->orderBy('jam_mulai')
            ->get();

        $sesiBerikutnya = SesiLive::whereIn('kelas_id', $kelasIds)
            ->whereDate('tanggal', '>', today())
            ->with('kelas.batch.program')
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->limit(3)
            ->get();

        // ── Stat cards ────────────────────────────────────
        $stats = [
            'kelas_aktif'      => $kelasAktif->count(),
            'kelas_selesai'    => $kelasSelesai->count(),
            'total_sertifikat' => $sertifikat->count(),
            'rata_kehadiran'   => round($rataKehadiran, 1),
            'rata_nilai'       => round($rataNilai, 1),
        ];

        $greeting = $this->greeting();

        return view('siswa.dashboard', compact(
            'user', 'siswa', 'enrollments', 'kelasAktif', 'kelasSelesai',
            'progressList', 'sertifikat', 'sesiHariIni', 'sesiBerikutnya',
            'stats', 'greeting'
        ));
    }

    private function greeting(): string
    {
        $hour = now()->hour;
        if ($hour < 11)  return 'Selamat Pagi';
        if ($hour < 15)  return 'Selamat Siang';
        if ($hour < 18)  return 'Selamat Sore';
        return 'Selamat Malam';
    }
}
