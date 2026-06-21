<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

use App\Http\Controllers\PendaftaranController;

Route::get('/daftar', [PendaftaranController::class, 'create'])
    ->name('pendaftaran.create');

Route::post('/daftar', [PendaftaranController::class, 'store'])
    ->name('pendaftaran.store');

Route::get(
    '/pendaftaran/{pendaftaran}/dokumen',
    [PendaftaranController::class, 'dokumen']
)->name('pendaftaran.dokumen');

Route::post(
    '/pendaftaran/{pendaftaran}/dokumen',
    [PendaftaranController::class, 'storeDokumen']
)->name('pendaftaran.dokumen.store');

Route::get('/daftar/sukses', [PendaftaranController::class, 'success'])
    ->name('pendaftaran.success');

require __DIR__.'/settings.php';
