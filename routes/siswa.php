<?php

use App\Http\Controllers\Siswa\ProfilSiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Siswa Routes — PBI-072
|--------------------------------------------------------------------------
| Tambahkan require __DIR__.'/siswa.php'; di routes/web.php
|
| Middleware 'siswa' perlu dibuat atau ganti dengan middleware role yang
| sudah ada di project (misal: 'role:siswa').
| Minimal gunakan middleware 'auth' + 'verified'.
*/

Route::prefix('siswa')
    ->name('siswa.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        // ── PBI-072: Profil siswa (self-service) ─────────────────────────

        // Lihat profil
        Route::get('/profil', [ProfilSiswaController::class, 'show'])
            ->name('profil.show');

        // Edit profil
        Route::get('/profil/edit', [ProfilSiswaController::class, 'edit'])
            ->name('profil.edit');

        Route::put('/profil', [ProfilSiswaController::class, 'update'])
            ->name('profil.update');

        // Ganti password
        Route::get('/profil/ganti-password', [ProfilSiswaController::class, 'editPassword'])
            ->name('profil.ganti-password');

        Route::put('/profil/ganti-password', [ProfilSiswaController::class, 'updatePassword'])
            ->name('profil.update-password');
    });
