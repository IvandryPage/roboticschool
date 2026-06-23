<?php

namespace App\Filament\Widgets;

use App\Models\ArsipLaporan;
use Filament\Widgets\Widget;

/**
 * PBI-140: Widget ringkasan arsip laporan terbaru di dashboard
 * Hanya tampil untuk role: Admin Akademik
 */
class ArsipLaporanWidget extends Widget
{
    protected string $view = 'filament.widgets.arsip-laporan';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    /**
     * PBI-140: Widget ini hanya untuk Admin Akademik.
     */
    public static function canView(): bool
    {
        return auth()->user()?->role?->nama_role === 'Admin Akademik';
    }

    public function getViewData(): array
    {
        $laporan = ArsipLaporan::with('pembuat')
            ->latest()
            ->limit(5)
            ->get();

        return compact('laporan');
    }
}
