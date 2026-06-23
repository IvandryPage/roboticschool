<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // Dynamic relations to avoid modifying model files
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

    /**
     * Configure default behaviors for production-ready applications.
     */
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
