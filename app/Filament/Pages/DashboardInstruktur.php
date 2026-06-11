<?php
namespace App\Filament\Pages;

use App\Models\EnrollmentKelas;
use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\PengumpulanTugas;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class DashboardInstruktur extends Page
{
    public function getView(): string
{
    return 'filament.pages.dashboard-instruktur';
}

public static function getNavigationLabel(): string
{
    return 'Dashboard Instruktur';
}

    public function getKelasSaya()
    {
        return Kelas::where('instruktur_id', Auth::id())->get();
    }

    public function getEvaluasiKelas($kelasId)
    {
        $enrollments = EnrollmentKelas::where('kelas_id', $kelasId)
            ->with('siswa.user')
            ->get();

        return $enrollments->map(function ($enrollment) use ($kelasId) {
            $siswa = $enrollment->siswa;

            $totalSesi = \App\Models\SesiLive::where('kelas_id', $kelasId)->count();
            $totalHadir = Kehadiran::where('siswa_id', $siswa->id)
                ->whereHas('sesi', fn($q) => $q->where('kelas_id', $kelasId))
                ->where('status_hadir', 'hadir')
                ->count();

            $tugasKumpul = PengumpulanTugas::where('siswa_id', $siswa->id)->count();
            $rataRataNilai = PengumpulanTugas::where('siswa_id', $siswa->id)
                ->whereNotNull('nilai')
                ->avg('nilai');

            $persenHadir = $totalSesi > 0 ? round(($totalHadir / $totalSesi) * 100) : 0;
            $lulus = $persenHadir >= 75 && ($rataRataNilai ?? 0) >= 70;

            return [
                'nama' => $siswa->user->nama_lengkap ?? $siswa->user->name ?? '-',
                'total_hadir' => $totalHadir,
                'total_sesi' => $totalSesi,
                'persen_hadir' => $persenHadir,
                'rata_nilai' => $rataRataNilai ? round($rataRataNilai, 1) : '-',
                'tugas_kumpul' => $tugasKumpul,
                'status' => $lulus ? 'Lulus' : 'Belum Lulus',
            ];
        });
    }
}