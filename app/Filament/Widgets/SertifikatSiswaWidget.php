<?php

namespace App\Filament\Widgets;

use App\Models\Sertifikat;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

/**
 * PBI-127: Sertifikat Siswa tampil langsung di dashboard Filament siswa.
 * Hanya tampil untuk role: Siswa
 */
class SertifikatSiswaWidget extends Widget
{
    protected string $view = 'filament.widgets.sertifikat-siswa';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->role?->nama_role === 'Siswa';
    }

    public function getViewData(): array
    {
        $user = Auth::user();
        $sertifikats = collect();

        if ($user?->siswa) {
            $sertifikats = Sertifikat::with([
                'siswa.user',
                'kelas.batch.program',
                'kelas.sesiLive',
                'penerbit',
            ])
                ->where('siswa_id', $user->siswa->id)
                ->orderByDesc('tanggal_terbit')
                ->get();
        }

        return compact('sertifikats', 'user');
    }
}
