<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        if (auth()->user()->role && auth()->user()->role->nama_role === 'Admin Akademik') {
            return redirect('/admin/aset');
        }
        return view('dashboard');
    })->name('dashboard');

    Route::get('peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'index'])
        ->name('peminjaman.index');
    Route::post('peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'store'])
        ->name('peminjaman.store');

    // Admin Custom Routes
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('aset', [\App\Http\Controllers\Admin\AdminAsetController::class, 'index'])->name('aset.index');
        Route::get('aset/create', [\App\Http\Controllers\Admin\AdminAsetController::class, 'create'])->name('aset.create');
        Route::post('aset', [\App\Http\Controllers\Admin\AdminAsetController::class, 'store'])->name('aset.store');
        Route::get('aset/{aset}/edit', [\App\Http\Controllers\Admin\AdminAsetController::class, 'edit'])->name('aset.edit');
        Route::put('aset/{aset}', [\App\Http\Controllers\Admin\AdminAsetController::class, 'update'])->name('aset.update');
        Route::delete('aset/{aset}', [\App\Http\Controllers\Admin\AdminAsetController::class, 'destroy'])->name('aset.destroy');
        
        // Item Kits inside Asset
        Route::post('aset/{aset}/item-kit', [\App\Http\Controllers\Admin\AdminAsetController::class, 'storeItemKit'])->name('aset.item-kit.store');
        Route::post('item-kit/{itemKit}/condition', [\App\Http\Controllers\Admin\AdminAsetController::class, 'updateItemKitCondition'])->name('item-kit.update-condition');
        Route::delete('item-kit/{itemKit}', [\App\Http\Controllers\Admin\AdminAsetController::class, 'destroyItemKit'])->name('item-kit.destroy');
        
        // Peminjaman Approval and Return
        Route::get('peminjaman', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('peminjaman/{peminjaman}/approve', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('peminjaman/{peminjaman}/reject', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::post('peminjaman/{peminjaman}/return', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'confirmReturn'])->name('peminjaman.return');
    });
});

require __DIR__.'/settings.php';
