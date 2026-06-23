<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ClassProgressOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return in_array(auth()->user()?->role?->nama_role, ['Admin Akademik', 'Instruktur', 'Direktur']);
    }
    protected function getStats(): array
    {
        $totalSiswaAktif = DB::table('enrollment_kelas')->distinct('siswa_id')->count();
        $totalKehadiranData = DB::table('kehadiran')->count();
        $totalHadir = DB::table('kehadiran')->where('status_hadir', 'hadir')->count();

        $persentase = $totalKehadiranData > 0 ? ($totalHadir / $totalKehadiranData) * 100 : 0;

        return [
            Stat::make('Siswa Aktif', $totalSiswaAktif)
                ->color('success'),
            Stat::make('Rata-rata Kehadiran', number_format($persentase, 2) . '%')
                ->color('info'),
        ];
    }
}