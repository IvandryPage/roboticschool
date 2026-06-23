<?php

namespace App\Filament\Widgets;

use App\Models\ItemKitRobotik;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AsetKondisiWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return in_array(auth()->user()?->role?->nama_role, ['Admin Akademik', 'Direktur']);
    }

    protected ?string $heading = 'Rekap Kondisi Aset Robotik';

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $hilang = ItemKitRobotik::where('status_kondisi', 'Hilang')->count();
        $rusak  = ItemKitRobotik::where('status_kondisi', 'Rusak')->count();
        $baik   = ItemKitRobotik::where('status_kondisi', 'Baik')->count();
        $total  = ItemKitRobotik::count();

        return [
            Stat::make('Aset Hilang', $hilang)
                ->description('Unit tidak ditemukan')
                ->color('danger'),

            Stat::make('Aset Rusak', $rusak)
                ->description('Unit perlu perbaikan')
                ->color('warning'),

            Stat::make('Aset Baik', $baik)
                ->description('Unit dalam kondisi baik')
                ->color('success'),

            Stat::make('Total Aset', $total)
                ->description('Seluruh unit terdaftar')
                ->color('info'),
        ];
    }
}
