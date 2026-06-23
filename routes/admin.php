<?php

use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\AdminAsetController;
use App\Http\Controllers\Admin\AdminPeminjamanController;
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
        
        // PBI-068 & PBI-069
        Route::get('/siswa', [SiswaController::class, 'index'])
            ->name('siswa.index');

        // PBI-067
        Route::get('/pendaftaran/{pendaftaranId}/buat-akun', [SiswaController::class, 'createAkun'])
            ->name('siswa.create-akun');

        Route::post('/pendaftaran/{pendaftaranId}/buat-akun', [SiswaController::class, 'storeAkun'])
            ->name('siswa.store-akun');

        // PBI-070
        Route::get('/siswa/{id}', [SiswaController::class, 'show'])
            ->name('siswa.show');

        Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])
            ->name('siswa.edit');

        Route::put('/siswa/{id}', [SiswaController::class, 'update'])
            ->name('siswa.update');

        // PBI-071: Nonaktifkan / Aktifkan akun siswa

        // Toggle cepat dari tabel daftar siswa
        Route::post('/siswa/{id}/toggle-status', [SiswaController::class, 'toggleStatus'])
           ->name('siswa.toggle-status');

        // Nonaktifkan dari halaman detail
        Route::post('/siswa/{id}/nonaktifkan', [SiswaController::class, 'nonaktifkan'])
          ->name('siswa.nonaktifkan');

        // Aktifkan kembali akun siswa
        Route::post('/siswa/{id}/aktifkan', [SiswaController::class, 'aktifkan'])
            ->name('siswa.aktifkan');
        
        // Aset Robotik
        Route::get('/aset', [AdminAsetController::class, 'index'])->name('aset.index');
        Route::get('/aset/create', [AdminAsetController::class, 'create'])->name('aset.create');
        Route::post('/aset', [AdminAsetController::class, 'store'])->name('aset.store');
        Route::get('/aset/{aset}/edit', [AdminAsetController::class, 'edit'])->name('aset.edit');
        Route::put('/aset/{aset}', [AdminAsetController::class, 'update'])->name('aset.update');
        Route::delete('/aset/{aset}', [AdminAsetController::class, 'destroy'])->name('aset.destroy');
        Route::post('/aset/{aset}/item-kit', [AdminAsetController::class, 'storeItemKit'])->name('aset.item-kit.store');
        Route::post('/item-kit/{itemKit}/update-condition', [AdminAsetController::class, 'updateItemKitCondition'])->name('item-kit.update-condition');
        Route::delete('/item-kit/{itemKit}', [AdminAsetController::class, 'destroyItemKit'])->name('item-kit.destroy');

        // Peminjaman Aset
        Route::get('/peminjaman-aset', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman-aset/{peminjaman}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman-aset/{peminjaman}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::post('/peminjaman-aset/{peminjaman}/confirm-return', [AdminPeminjamanController::class, 'confirmReturn'])->name('peminjaman.return');

    });
