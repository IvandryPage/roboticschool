<?php

use App\Http\Controllers\Admin\PendaftaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes — PBI-057, 060, 061, 062, 063, 064, 065, 066
|--------------------------------------------------------------------------
| Tambahkan require __DIR__.'/admin.php'; di routes/web.php
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        // PBI-060 & PBI-061: Daftar pendaftaran + filter + search
        Route::get('/pendaftaran', [PendaftaranController::class, 'index'])
            ->name('pendaftaran.index');

        // PBI-062: Detail pendaftaran + pratinjau dokumen
        Route::get('/pendaftaran/{id}', [PendaftaranController::class, 'show'])
            ->name('pendaftaran.show');

        // PBI-063: Verifikasi per dokumen (valid / tidak valid + catatan)
        Route::post('/pendaftaran/{pendaftaranId}/dokumen/{dokumenId}/verifikasi', [PendaftaranController::class, 'verifikasiDokumen'])
            ->name('pendaftaran.verifikasi-dokumen');

        // PBI-064: Setujui pendaftaran (semua dokumen harus valid)
        Route::post('/pendaftaran/{id}/setujui', [PendaftaranController::class, 'setujui'])
            ->name('pendaftaran.setujui');

        // PBI-065: Tolak pendaftaran + alasan
        Route::post('/pendaftaran/{id}/tolak', [PendaftaranController::class, 'tolak'])
            ->name('pendaftaran.tolak');

        // PBI-066: Kirim catatan revisi (pilih dokumen bermasalah)
        Route::post('/pendaftaran/{id}/revisi', [PendaftaranController::class, 'revisi'])
            ->name('pendaftaran.revisi');
    });
