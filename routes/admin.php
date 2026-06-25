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
    ->middleware(['auth', 'verified'])
    ->group(function () {

        // Pendaftaran → PendaftaranResource (Filament)
        Route::get('/pendaftaran',          fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.index');
        Route::get('/pendaftaran/{id}',     fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.show');
        Route::post('/pendaftaran/{a}/dokumen/{b}/verifikasi', fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.verifikasi-dokumen');
        Route::post('/pendaftaran/{id}/setujui',  fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.setujui');
        Route::post('/pendaftaran/{id}/tolak',    fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.tolak');
        Route::post('/pendaftaran/{id}/revisi',   fn () => redirect('/admin/pendaftarans'))->name('pendaftaran.revisi');
        Route::get('/pendaftaran/{id}/buat-akun', fn () => redirect('/admin/siswas'))->name('siswa.create-akun');
        Route::post('/pendaftaran/{id}/buat-akun', fn () => redirect('/admin/siswas'))->name('siswa.store-akun');

        // Siswa → SiswaResource (Filament)
        Route::get('/siswa',               fn () => redirect('/admin/siswas'))->name('siswa.index');
        Route::get('/siswa/{id}',          fn () => redirect('/admin/siswas'))->name('siswa.show');
        Route::get('/siswa/{id}/edit',     fn () => redirect('/admin/siswas'))->name('siswa.edit');
        Route::put('/siswa/{id}',          fn () => redirect('/admin/siswas'))->name('siswa.update');
        Route::post('/siswa/{id}/toggle-status', fn () => redirect('/admin/siswas'))->name('siswa.toggle-status');
        Route::post('/siswa/{id}/nonaktifkan',   fn () => redirect('/admin/siswas'))->name('siswa.nonaktifkan');
        Route::post('/siswa/{id}/aktifkan',      fn () => redirect('/admin/siswas'))->name('siswa.aktifkan');

        // Aset → AsetRobotikResource (Filament)
        Route::get('/aset',                fn () => redirect('/admin/aset-robotiks'))->name('aset.index');
        Route::get('/aset/create',         fn () => redirect('/admin/aset-robotiks/create'))->name('aset.create');
        Route::post('/aset',               fn () => redirect('/admin/aset-robotiks'))->name('aset.store');
        Route::get('/aset/{id}/edit',      fn () => redirect('/admin/aset-robotiks'))->name('aset.edit');
        Route::put('/aset/{id}',           fn () => redirect('/admin/aset-robotiks'))->name('aset.update');
        Route::delete('/aset/{id}',        fn () => redirect('/admin/aset-robotiks'))->name('aset.destroy');
        Route::post('/aset/{id}/item-kit', fn () => redirect('/admin/aset-robotiks'))->name('aset.item-kit.store');
        Route::post('/item-kit/{id}/update-condition', fn () => redirect('/admin/aset-robotiks'))->name('item-kit.update-condition');
        Route::delete('/item-kit/{id}',    fn () => redirect('/admin/aset-robotiks'))->name('item-kit.destroy');

        // Peminjaman → PeminjamanItemAsetResource (Filament)
        Route::get('/peminjaman-aset',              fn () => redirect('/admin/peminjaman-item-asets'))->name('peminjaman.index');
        Route::post('/peminjaman-aset/{id}/approve',        fn () => redirect('/admin/peminjaman-item-asets'))->name('peminjaman.approve');
        Route::post('/peminjaman-aset/{id}/reject',         fn () => redirect('/admin/peminjaman-item-asets'))->name('peminjaman.reject');
        Route::post('/peminjaman-aset/{id}/confirm-return', fn () => redirect('/admin/peminjaman-item-asets'))->name('peminjaman.return');

    });
