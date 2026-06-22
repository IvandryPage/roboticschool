<?php

namespace App\Providers;

use App\Listeners\LogSuccessfulLogin;
use App\Livewire\EvaluasiInstrukturForm;
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
