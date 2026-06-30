<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftaran;
use App\Models\Siswa;
use App\Models\PeminjamanItemAset;
use App\Models\Pembayaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * PBI-DashboardAdmin: Statistik Utama untuk Admin Akademik
 * Hanya tampil untuk role: Admin Akademik
 */
class StatistikAdminWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return \Illuminate\Support\Facades\Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

    protected function getStats(): array
    {
        // 1. Pendaftaran Pending
        $pendingPendaftaran = Pendaftaran::where('status', 'pending')->count();

        // 2. Siswa Aktif
        $siswaAktif = Siswa::whereHas('user', fn ($q) => $q->where('status_aktif', true))->count();

        // 3. Peminjaman Pending (Status 'Diajukan')
        $pendingPeminjaman = PeminjamanItemAset::where('status', 'Diajukan')->count();

        // 4. Pembayaran Pending
        $pendingPembayaran = Pembayaran::where('status', 'Pending')->count();

        return [
            Stat::make('Pendaftaran Pending', $pendingPendaftaran)
                ->description('Perlu verifikasi dokumen')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color($pendingPendaftaran > 0 ? 'warning' : 'success'),

            Stat::make('Siswa Aktif', $siswaAktif)
                ->description('Total siswa terdaftar aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Peminjaman Pending', $pendingPeminjaman)
                ->description('Menunggu persetujuan kit')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color($pendingPeminjaman > 0 ? 'warning' : 'success'),

            Stat::make('Pembayaran Pending', $pendingPembayaran)
                ->description('Menunggu validasi transfer')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color($pendingPembayaran > 0 ? 'warning' : 'success'),
        ];
    }
}
