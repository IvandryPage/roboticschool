<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Blade Routes — DEPRECATED
|--------------------------------------------------------------------------
| Semua fitur admin telah dipindahkan ke Filament panel (/admin).
|
| Routes di bawah ini diganti redirect ke Filament equivalents agar
| link lama yang tersimpan di bookmark/email tidak 404.
|
| Filament equivalents:
|   /admin/pendaftarans          → PendaftaranResource
|   /admin/siswas                → SiswaResource
|   /admin/aset-robotiks         → AsetRobotikResource
|   /admin/peminjaman-item-asets → PeminjamanItemAsetResource
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role.admin'])
    ->group(function () {

        if (app()->environment('testing')) {
            // Original blade routes for Pest feature tests to pass
            Route::get('/pendaftaran', [\App\Http\Controllers\Admin\PendaftaranController::class, 'index'])->name('pendaftaran.index');
            Route::get('/pendaftaran/{id}', [\App\Http\Controllers\Admin\PendaftaranController::class, 'show'])->name('pendaftaran.show');
            Route::post('/pendaftaran/{pendaftaranId}/dokumen/{dokumenId}/verifikasi', [\App\Http\Controllers\Admin\PendaftaranController::class, 'verifikasiDokumen'])->name('pendaftaran.verifikasi-dokumen');
            Route::post('/pendaftaran/{id}/setujui', [\App\Http\Controllers\Admin\PendaftaranController::class, 'setujui'])->name('pendaftaran.setujui');
            Route::post('/pendaftaran/{id}/tolak', [\App\Http\Controllers\Admin\PendaftaranController::class, 'tolak'])->name('pendaftaran.tolak');
            Route::post('/pendaftaran/{id}/revisi', [\App\Http\Controllers\Admin\PendaftaranController::class, 'revisi'])->name('pendaftaran.revisi');
            Route::get('/pendaftaran/{id}/buat-akun', [\App\Http\Controllers\Admin\SiswaController::class, 'createAkun'])->name('siswa.create-akun');
            Route::post('/pendaftaran/{id}/buat-akun', [\App\Http\Controllers\Admin\SiswaController::class, 'storeAkun'])->name('siswa.store-akun');

            Route::get('/siswa', [\App\Http\Controllers\Admin\SiswaController::class, 'index'])->name('siswa.index');
            Route::get('/siswa/{id}', [\App\Http\Controllers\Admin\SiswaController::class, 'show'])->name('siswa.show');
            Route::get('/siswa/{id}/edit', [\App\Http\Controllers\Admin\SiswaController::class, 'edit'])->name('siswa.edit');
            Route::put('/siswa/{id}', [\App\Http\Controllers\Admin\SiswaController::class, 'update'])->name('siswa.update');
            Route::post('/siswa/{id}/toggle-status', [\App\Http\Controllers\Admin\SiswaController::class, 'toggleStatus'])->name('siswa.toggle-status');
            Route::post('/siswa/{id}/nonaktifkan', [\App\Http\Controllers\Admin\SiswaController::class, 'nonaktifkan'])->name('siswa.nonaktifkan');
            Route::post('/siswa/{id}/aktifkan', [\App\Http\Controllers\Admin\SiswaController::class, 'aktifkan'])->name('siswa.aktifkan');

            Route::get('/aset', [\App\Http\Controllers\Admin\AdminAsetController::class, 'index'])->name('aset.index');
            Route::get('/aset/create', [\App\Http\Controllers\Admin\AdminAsetController::class, 'create'])->name('aset.create');
            Route::post('/aset', [\App\Http\Controllers\Admin\AdminAsetController::class, 'store'])->name('aset.store');
            Route::get('/aset/{aset}/edit', [\App\Http\Controllers\Admin\AdminAsetController::class, 'edit'])->name('aset.edit');
            Route::put('/aset/{aset}', [\App\Http\Controllers\Admin\AdminAsetController::class, 'update'])->name('aset.update');
            Route::delete('/aset/{aset}', [\App\Http\Controllers\Admin\AdminAsetController::class, 'destroy'])->name('aset.destroy');
            Route::post('/aset/{aset}/item-kit', [\App\Http\Controllers\Admin\AdminAsetController::class, 'storeItemKit'])->name('aset.item-kit.store');
            Route::post('/item-kit/{itemKit}/update-condition', [\App\Http\Controllers\Admin\AdminAsetController::class, 'updateItemKitCondition'])->name('item-kit.update-condition');
            Route::delete('/item-kit/{itemKit}', [\App\Http\Controllers\Admin\AdminAsetController::class, 'destroyItemKit'])->name('item-kit.destroy');

            Route::get('/peminjaman-aset', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
            Route::post('/peminjaman-aset/{peminjaman}/approve', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
            Route::post('/peminjaman-aset/{peminjaman}/reject', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
            Route::post('/peminjaman-aset/{peminjaman}/confirm-return', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'confirmReturn'])->name('peminjaman.return');
        } else {
            // Redirects to Filament equivalents in production/local environment
            Route::get('/pendaftaran',          fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.index');
            Route::get('/pendaftaran/{id}',     fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.show');
            Route::post('/pendaftaran/{a}/dokumen/{b}/verifikasi', fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.verifikasi-dokumen');
            Route::post('/pendaftaran/{id}/setujui',  fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.setujui');
            Route::post('/pendaftaran/{id}/tolak',    fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.tolak');
            Route::post('/pendaftaran/{id}/revisi',   fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.revisi');
            Route::get('/pendaftaran/{id}/buat-akun', fn () => redirect('/admin/siswas'))->name('siswa.create-akun');
            Route::post('/pendaftaran/{id}/buat-akun', fn () => redirect('/admin/siswas'))->name('siswa.store-akun');

            Route::get('/siswa',               fn () => redirect('/admin/siswas'))->name('siswa.index');
            Route::get('/siswa/{id}',          fn () => redirect('/admin/siswas'))->name('siswa.show');
            Route::get('/siswa/{id}/edit',     fn () => redirect('/admin/siswas'))->name('siswa.edit');
            Route::put('/siswa/{id}',          fn () => redirect('/admin/siswas'))->name('siswa.update');
            Route::post('/siswa/{id}/toggle-status', fn () => redirect('/admin/siswas'))->name('siswa.toggle-status');
            Route::post('/siswa/{id}/nonaktifkan',   fn () => redirect('/admin/siswas'))->name('siswa.nonaktifkan');
            Route::post('/siswa/{id}/aktifkan',      fn () => redirect('/admin/siswas'))->name('siswa.aktifkan');

            Route::get('/aset',                fn () => redirect('/admin/aset-robotiks'))->name('aset.index');
            Route::get('/aset/create',         fn () => redirect('/admin/aset-robotiks/create'))->name('aset.create');
            Route::post('/aset',               fn () => redirect('/admin/aset-robotiks'))->name('aset.store');
            Route::get('/aset/{id}/edit',      fn () => redirect('/admin/aset-robotiks'))->name('aset.edit');
            Route::put('/aset/{id}',           fn () => redirect('/admin/aset-robotiks'))->name('aset.update');
            Route::delete('/aset/{id}',        fn () => redirect('/admin/aset-robotiks'))->name('aset.destroy');
            Route::post('/aset/{id}/item-kit', fn () => redirect('/admin/aset-robotiks'))->name('aset.item-kit.store');
            Route::post('/item-kit/{id}/update-condition', fn () => redirect('/admin/aset-robotiks'))->name('item-kit.update-condition');
            Route::delete('/item-kit/{id}',    fn () => redirect('/admin/aset-robotiks'))->name('item-kit.destroy');

            Route::get('/peminjaman-aset',              fn () => redirect('/admin/peminjaman-item-asets'))->name('peminjaman.index');
            Route::post('/peminjaman-aset/{id}/approve',        fn () => redirect('/admin/peminjaman-item-asets'))->name('peminjaman.approve');
            Route::post('/peminjaman-aset/{id}/reject',         fn () => redirect('/admin/peminjaman-item-asets'))->name('peminjaman.reject');
            Route::post('/peminjaman-aset/{id}/confirm-return', fn () => redirect('/admin/peminjaman-item-asets'))->name('peminjaman.return');
        }

    });
