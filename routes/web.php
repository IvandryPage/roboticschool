<?php

use Illuminate\Support\Facades\Route;
#PB01 
// use App\Http\Controllers\Admin\UserController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('/admin/dashboard', 'dashboard');
    Route::view('/instruktur/dashboard', 'dashboard');
    Route::view('/siswa/dashboard', 'dashboard');
    Route::view('/publikasi/dashboard', 'dashboard');
    Route::view('/direktur/dashboard', 'dashboard');
    #buat jalur ke UserController
    // Route::prefix('admin')->name('admin.')->group(function () {
    //     Route::resource('users', UserController::class);
    // });
});

// Social login (Google)
use App\Http\Controllers\Auth\SocialAuthController;

Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

require __DIR__.'/settings.php';
