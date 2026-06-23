<?php

namespace App\Filament\Widgets;

use App\Models\EnrollmentKelas;
use App\Models\ProgramKursus;
use App\Models\Sertifikat;
use App\Models\Siswa;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * PBI-135: Dashboard Direktur — Statistik Utama
 * Hanya tampil untuk role: Direktur
 */
class StatistikDirekturWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    /**
     * PBI-135: Widget ini hanya untuk Direktur.
     */
    public static function canView(): bool
    {
        return auth()->user()?->role?->nama_role === 'Direktur';
    }

    protected function getStats(): array
    {
        $totalSiswaAktif = Siswa::query()
            ->whereHas('enrollmentKelas', fn ($q) => $q->where('status', 'Aktif'))
            ->count();

        $totalSertifikat = Sertifikat::count();

        $totalSiswaSelesai = EnrollmentKelas::where('status', 'Selesai')->count();

        $totalProgram = ProgramKursus::where('status_tampil', true)->count();

        $sertifikatBulanIni = Sertifikat::whereMonth('tanggal_terbit', now()->month)
            ->whereYear('tanggal_terbit', now()->year)
            ->count();

        $totalInstruktur = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Instruktur'))
            ->where('status_aktif', true)
            ->count();

        return [
            Stat::make('Siswa Aktif', $totalSiswaAktif)
                ->description('Sedang mengikuti kelas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Total Sertifikat Terbit', $totalSertifikat)
                ->description("{$sertifikatBulanIni} diterbitkan bulan ini")
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Siswa Telah Selesai', $totalSiswaSelesai)
                ->description('Berhasil menyelesaikan kelas')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),

            Stat::make('Program Aktif', $totalProgram)
                ->description('Program tersedia untuk publik')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('warning'),

            Stat::make('Total Instruktur', $totalInstruktur)
                ->description('Instruktur aktif')
                ->descriptionIcon('heroicon-m-identification')
                ->color('gray'),
        ];
    }
}
