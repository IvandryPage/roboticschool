<?php

namespace App\Filament\Admin\Resources\KemajuanBelajars\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use App\Models\Kehadiran;
use App\Models\PengumpulanTugas;

class KemajuanBelajarStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $siswaId = Auth::user()->siswa?->id;

        // 1. Hitung Kehadiran
        $totalHadir = Kehadiran::where('siswa_id', $siswaId)->count();
        $hadir = Kehadiran::where('siswa_id', $siswaId)->where('status_hadir', 'hadir')->count();
        $persentaseKehadiran = $totalHadir > 0 ? round(($hadir / $totalHadir) * 100, 1) . '%' : '0%';

        // 2. Hitung Rata-rata Nilai
        $rataNilai = PengumpulanTugas::where('siswa_id', $siswaId)->avg('nilai');
        $rataNilaiFormated = $rataNilai ? round($rataNilai, 1) : '0';

        return [
            Stat::make('Persentase Kehadiran', $persentaseKehadiran)
                ->description('Total kehadiran Anda di kelas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
            Stat::make('Rata-rata Nilai Tugas', $rataNilaiFormated)
                ->description('Rerata akumulasi nilai tugas Anda')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($rataNilai >= 75 ? 'success' : 'warning'),
        ];
    }
}