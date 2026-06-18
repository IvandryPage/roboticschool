<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

// ===== TAMBAHAN KODE UNTUK PBI-165 (START) =====
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\LogSuccessfulLogin;
use App\Observers\AuditObserver;
// use App\Models\User; // Nanti buka tanda "//" ini jika Model User sudah dibuat tim lain
// use App\Models\Siswa; // Nanti buka tanda "//" ini jika Model Siswa sudah dibuat tim lain
// ===== TAMBAHAN KODE UNTUK PBI-165 (END) =====

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
        // Pengaturan bawaan proyek jangan dihapus
        $this->configureDefaults();

        // ===== TAMBAHAN KODE UNTUK PBI-165 (START) =====
        
        // 1. Mengaktifkan Pendeteksi Login
        Event::listen(
            Login::class,
            LogSuccessfulLogin::class,
        );

        // 2. Menempelkan Observer (CCTV) ke Model
        // (Catatan: Hapus tanda "//" di bawah ini kalau temanmu sudah mengonfirmasi nama Model mereka)
        
        // User::observe(AuditObserver::class);
        // Siswa::observe(AuditObserver::class);

        // ===== TAMBAHAN KODE UNTUK PBI-165 (END) =====
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