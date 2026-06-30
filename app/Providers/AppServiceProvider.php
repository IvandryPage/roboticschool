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
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
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
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
        // Listen to migrations starting event to create a dummy 'sesi' table
        // to satisfy a foreign key constraint in the group's migration files.
        Event::listen(\Illuminate\Database\Events\MigrationsStarted::class, function () {
            if (!\Illuminate\Support\Facades\Schema::hasTable('sesi')) {
                \Illuminate\Support\Facades\Schema::create('sesi', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->uuid('id')->primary();
                    $table->timestamps();
                });
            }
        });

        // PERBAIKAN TOTAL: Menghilangkan paksa fitur sanitizer yang butuh PHP 8.4
        // Agar tidak terjadi error Class Dom\HTMLDocument not found
        Config::set('filament.html_sanitizer', false);

        // Membersihkan macro yang mungkin mencoba memanggil sanitizer
        if (View::hasMacro('sanitizeHtml')) {
            View::flushMacros();
        }

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
            return $model->belongsTo(User::class, 'user_id');
        });

        \App\Models\PeminjamanItemAset::resolveRelationUsing('verifikator', function ($model) {
            return $model->belongsTo(User::class, 'diverifikasi_oleh');
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
