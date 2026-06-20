<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('keluhan', [\App\Http\Controllers\KeluhanController::class, 'create'])->name('keluhan.create');
    Route::post('keluhan', [\App\Http\Controllers\KeluhanController::class, 'store'])->name('keluhan.store');
    Route::get('keluhan/saya', [\App\Http\Controllers\KeluhanController::class, 'index'])->name('keluhan.saya');
});

require __DIR__.'/settings.php';
