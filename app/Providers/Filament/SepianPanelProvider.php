<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Panel ini tidak digunakan oleh role manapun (orphan panel).
 * Di-lock ke path /sepian-disabled dan hanya bisa diakses Admin
 * sebagai safety measure — tidak dihapus untuk menghindari
 * error pada AppServiceProvider yang mungkin sudah register provider ini.
 */
class SepianPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('sepian')
            ->path('sepian-disabled')   // Ubah path agar tidak bisa diakses via /sepian
            ->colors([
                'primary' => Color::Gray,
            ])
            ->brandName('RoboNesia')
            ->resources([])             // Kosongkan semua resource
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                // Hanya Admin yang boleh akses — untuk debugging jika perlu
                \App\Http\Middleware\CheckAdminRole::class,
            ]);
    }
}
