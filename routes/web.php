<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PembayaranController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| PENDAFTARAN
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| PEMBAYARAN
|--------------------------------------------------------------------------
*/

Route::get(
    '/pembayaran/{pendaftaran}',
    [PembayaranController::class, 'index']
)->name('pembayaran.index');

Route::post(
    '/pembayaran/{pendaftaran}',
    [PembayaranController::class, 'store']
)->name('pembayaran.store');

Route::get(
    '/syarat-ketentuan/{pendaftaran}',
    function (\App\Models\Pendaftaran $pendaftaran) {
        return view('legal.syarat', compact('pendaftaran'));
    }
)->name('syarat');
Route::get(
    '/kebijakan-refund/{pendaftaran}',
    function (\App\Models\Pendaftaran $pendaftaran) {
        return view('legal.refund', compact('pendaftaran'));
    }
)->name('refund');
/*
|--------------------------------------------------------------------------
| SELESAI
|--------------------------------------------------------------------------
*/

Route::get(
    '/pendaftaran/{pendaftaran}/selesai',
    [PendaftaranController::class, 'selesai']
)->name('pendaftaran.selesai');

/*
|--------------------------------------------------------------------------
| LEGACY SUCCESS (boleh dihapus nanti)
|--------------------------------------------------------------------------
*/

Route::get('/daftar/sukses', [PendaftaranController::class, 'success'])
    ->name('pendaftaran.success');

require __DIR__.'/settings.php';