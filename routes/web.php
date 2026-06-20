<?php

use Illuminate\Support\Facades\Route;
#PB01 
// use App\Http\Controllers\Admin\UserController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    #buat jalur ke UserController
    // Route::prefix('admin')->name('admin.')->group(function () {
    //     Route::resource('users', UserController::class);
    // });
});

require __DIR__.'/settings.php';
