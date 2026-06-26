<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentKelas;
use App\Models\Kehadiran;
use App\Models\PengumpulanTugas;
use App\Models\ProgressAkademik;
use App\Models\SesiLive;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;

class ProgresController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $siswa = $user?->siswa;

        if (!$siswa) {
            return redirect('/admin');
        }

        $enrollments = EnrollmentKelas::with([
                'kelas.batch.program',
                'kelas.instruktur',
            ])
            ->where('siswa_id', $siswa->id)
            ->get();

        $progresPerKelas = [];

        foreach ($enrollments as $enrollment) {
            $kelas   = $enrollment->kelas;
            $kelasId = $kelas->id;

            // Total sesi dan kehadiran
            $totalSesi  = SesiLive::where('kelas_id', $kelasId)->count();
            $hadir      = Kehadiran::where('siswa_id', $siswa->id)
                ->whereHas('sesiLive', fn($q) => $q->where('kelas_id', $kelasId))
                ->where('status_hadir', 'Hadir')
                ->count();

            $persentaseHadir = $totalSesi > 0
                ? round(($hadir / $totalSesi) * 100, 1)
                : 0;

            // Tugas dan nilai
            $sesiIds = SesiLive::where('kelas_id', $kelasId)->pluck('id');
            $tugasIds = Tugas::whereIn('sesi_id', $sesiIds)->pluck('id');

            $pengumpulan = PengumpulanTugas::where('siswa_id', $siswa->id)
                ->whereIn('tugas_id', $tugasIds)
                ->where('status_penilaian', 'Dinilai')
                ->get();

            $totalTugas = $tugasIds->count();
            $sudahDinilai = $pengumpulan->count();
            $rataNilai = $pengumpulan->avg('nilai') ?? 0;

            // Progress dari tabel ProgressAkademik jika ada
            $progAkademik = ProgressAkademik::where('siswa_id', $siswa->id)
                ->where('kelas_id', $kelasId)
                ->first();

            $persentaseSelesai = $progAkademik?->persentase_penyelesaian
                ?? ($totalTugas > 0 ? round(($sudahDinilai / $totalTugas) * 100, 1) : 0);

            $progresPerKelas[] = [
                'kelas'               => $kelas,
                'enrollment'          => $enrollment,
                'total_sesi'          => $totalSesi,
                'hadir'               => $hadir,
                'persentase_hadir'    => $persentaseHadir,
                'total_tugas'         => $totalTugas,
                'sudah_dikumpul'      => PengumpulanTugas::where('siswa_id', $siswa->id)
                                            ->whereIn('tugas_id', $tugasIds)->count(),
                'sudah_dinilai'       => $sudahDinilai,
                'rata_nilai'          => round($rataNilai, 1),
                'persentase_selesai'  => $persentaseSelesai,
            ];
        }

        return view('siswa.progres.index', compact('progresPerKelas'));
    }
}
