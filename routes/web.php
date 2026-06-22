<?php

use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SiswaDashboardController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route dashboard — dibutuhkan oleh Auth & DashboardTest
// Guest akan di-redirect ke login oleh middleware auth
// Authenticated user melihat halaman dashboard (200 OK)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// ============================================================
// PBI-127: Halaman sertifikat milik siswa
// Hanya bisa diakses oleh user dengan role Siswa
// ============================================================
Route::middleware(['auth'])->group(function () {
    // PBI-127: Halaman sertifikat milik siswa
    Route::get('/sertifikat/saya', [SertifikatController::class, 'milikku'])
        ->name('sertifikat.saya');

    // Dashboard Siswa — portal dengan sidebar modern
    Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])
        ->name('siswa.dashboard');
});

// PBI-128: Halaman verifikasi sertifikat (publik, tanpa login)
Route::get('/sertifikat/verifikasi/{nomor}', [SertifikatController::class, 'verifikasi'])
    ->name('sertifikat.verifikasi');
