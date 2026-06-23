<?php

namespace App\Providers;

use App\Listeners\LogSuccessfulLogin;
use App\Livewire\EvaluasiInstrukturForm;
use App\Models\User;
use App\Observers\AuditObserver;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerRoutes();

        // PBI-165: mencatat log saat login
        Event::listen(
            Login::class,
            LogSuccessfulLogin::class,
        );

        // PBI-165: mencatat log saat data diupdate atau dihapus
        User::observe(AuditObserver::class);

        // Dynamic relations dari main
        \App\Models\AsetRobotik::resolveRelationUsing('itemKits', function ($model) {
            return $model->hasMany(\App\Models\ItemKitRobotik::class, 'aset_id');
        });

        \App\Models\ItemKitRobotik::resolveRelationUsing('aset', function ($model) {
            return $model->belongsTo(\App\Models\AsetRobotik::class, 'aset_id');
        });

        \App\Models\ItemKitRobotik::resolveRelationUsing('peminjamans', function ($model) {
            return $model->hasMany(\App\Models\PeminjamanItemAset::class, 'item_kit_id');
        });

        \App\Models\PeminjamanItemAset::resolveRelationUsing('borrower', function ($model) {
            return $model->belongsTo(\App\Models\User::class, 'user_id');
        });

        \App\Models\PeminjamanItemAset::resolveRelationUsing('verifikator', function ($model) {
            return $model->belongsTo(\App\Models\User::class, 'diverifikasi_oleh');
        });
    }

    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'verified'])
            ->get('evaluasi-instruktur/{kelas}', EvaluasiInstrukturForm::class)
            ->name('evaluasi.instruktur');
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
