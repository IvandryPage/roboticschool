<?php

namespace App\Filament\Widgets;

use App\Models\EvaluasiInstruktur;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

/**
 * PBI-138: Dashboard Instruktur — Evaluasi Kelas
 * Hanya tampil untuk role: Instruktur
 */
class DashboardInstrukturWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-instruktur';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    /**
     * PBI-138: Widget ini hanya untuk Instruktur.
     */
    public static function canView(): bool
    {
        return auth()->user()?->role?->nama_role === 'Instruktur';
    }

    public function getViewData(): array
    {
        $evaluasi = EvaluasiInstruktur::with(['kelas', 'siswa.user'])
            ->where('instruktur_id', Auth::id())
            ->latest()
            ->get();

        return compact('evaluasi');
    }
}
