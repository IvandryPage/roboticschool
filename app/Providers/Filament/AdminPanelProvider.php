<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\ArsipLaporanWidget;
use App\Filament\Widgets\DashboardInstrukturWidget;
use App\Filament\Widgets\RekapKelulusanWidget;
use App\Filament\Widgets\SertifikatSiswaWidget;
use App\Filament\Widgets\StatistikDirekturWidget;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->defaultThemeMode(ThemeMode::Light)
            ->colors([
                'primary' => [
                    50  => '#E0F7FA',
                    100 => '#B2EBF2',
                    200 => '#80DEEA',
                    300 => '#4DD0E1',
                    400 => '#26C6DA',
                    500 => '#00BCD4',
                    600 => '#00ACC1',
                    700 => '#0097A7',
                    800 => '#00838F',
                    900 => '#006064',
                    950 => '#004D52',
                ],
                'gray' => [
                    50  => '#F8FAFC',
                    100 => '#F1F5F9',
                    200 => '#E2E8F0',
                    300 => '#CBD5E1',
                    400 => '#94A3B8',
                    500 => '#64748B',
                    600 => '#475569',
                    700 => '#334155',
                    800 => '#1E293B',
                    900 => '#0F172A',
                    950 => '#080F1F',
                ],
                'danger' => [
                    50  => '#FFEBEE',
                    100 => '#FFCDD2',
                    200 => '#EF9A9A',
                    300 => '#E57373',
                    400 => '#EF5350',
                    500 => '#F44336',
                    600 => '#E53935',
                    700 => '#D32F2F',
                    800 => '#C62828',
                    900 => '#B71C1C',
                    950 => '#7F0000',
                ],
                'warning' => [
                    50  => '#FFF8E1',
                    100 => '#FFECB3',
                    200 => '#FFE082',
                    300 => '#FFD54F',
                    400 => '#FFCA28',
                    500 => '#FF9800',
                    600 => '#FB8C00',
                    700 => '#F57C00',
                    800 => '#EF6C00',
                    900 => '#E65100',
                    950 => '#BF360C',
                ],
                'success' => [
                    50  => '#E8F5E9',
                    100 => '#C8E6C9',
                    200 => '#A5D6A7',
                    300 => '#81C784',
                    400 => '#66BB6A',
                    500 => '#4CAF50',
                    600 => '#43A047',
                    700 => '#388E3C',
                    800 => '#2E7D32',
                    900 => '#1B5E20',
                    950 => '#0A3D0C',
                ],
                'info' => [
                    50  => '#E0F2FE',
                    100 => '#BAE6FD',
                    200 => '#7DD3FC',
                    300 => '#38BDF8',
                    400 => '#0EA5E9',
                    500 => '#0284C7',
                    600 => '#0369A1',
                    700 => '#075985',
                    800 => '#0C4A6E',
                    900 => '#082F49',
                    950 => '#051E30',
                ],
            ])
            ->font('Plus Jakarta Sans', provider: \Filament\FontProviders\GoogleFontProvider::class)
            ->brandName('RoboNesia')
            ->favicon(asset('favicon.ico'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                StatistikDirekturWidget::class,
                RekapKelulusanWidget::class,
                DashboardInstrukturWidget::class,
                ArsipLaporanWidget::class,
                SertifikatSiswaWidget::class,
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
            ]);
    }
}
