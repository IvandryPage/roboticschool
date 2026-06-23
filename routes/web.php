<?php

use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SiswaDashboardController;
use Illuminate\Support\Facades\Route;
#PB01 
// use App\Http\Controllers\Admin\UserController;

// Halaman utama
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('dashboard', function () {
//         if (auth()->user()->role && auth()->user()->role->nama_role === 'Admin Akademik') {
//             return redirect('/admin/aset');
//         }
//         return view('dashboard');
//     })->name('dashboard');

//     Route::get('peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'index'])
//         ->name('peminjaman.index');
//     Route::post('peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'store'])
//         ->name('peminjaman.store');

//     // Admin Custom Routes
//     Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
//         Route::get('aset', [\App\Http\Controllers\Admin\AdminAsetController::class, 'index'])->name('aset.index');
//         Route::get('aset/create', [\App\Http\Controllers\Admin\AdminAsetController::class, 'create'])->name('aset.create');
//         Route::post('aset', [\App\Http\Controllers\Admin\AdminAsetController::class, 'store'])->name('aset.store');
//         Route::get('aset/{aset}/edit', [\App\Http\Controllers\Admin\AdminAsetController::class, 'edit'])->name('aset.edit');
//         Route::put('aset/{aset}', [\App\Http\Controllers\Admin\AdminAsetController::class, 'update'])->name('aset.update');
//         Route::delete('aset/{aset}', [\App\Http\Controllers\Admin\AdminAsetController::class, 'destroy'])->name('aset.destroy');
        
//         // Item Kits inside Asset
//         Route::post('aset/{aset}/item-kit', [\App\Http\Controllers\Admin\AdminAsetController::class, 'storeItemKit'])->name('aset.item-kit.store');
//         Route::post('item-kit/{itemKit}/condition', [\App\Http\Controllers\Admin\AdminAsetController::class, 'updateItemKitCondition'])->name('item-kit.update-condition');
//         Route::delete('item-kit/{itemKit}', [\App\Http\Controllers\Admin\AdminAsetController::class, 'destroyItemKit'])->name('item-kit.destroy');
        
//         // Peminjaman Approval and Return
//         Route::get('peminjaman', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
//         Route::post('peminjaman/{peminjaman}/approve', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
//         Route::post('peminjaman/{peminjaman}/reject', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
//         Route::post('peminjaman/{peminjaman}/return', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'confirmReturn'])->name('peminjaman.return');
//     });
//     Route::view('dashboard', 'dashboard')->name('dashboard');
//     Route::view('/admin/dashboard', 'dashboard');
//     Route::view('/instruktur/dashboard', 'dashboard');
//     Route::view('/siswa/dashboard', 'dashboard');
//     Route::view('/publikasi/dashboard', 'dashboard');
//     Route::view('/direktur/dashboard', 'dashboard');
//     #buat jalur ke UserController
//     // Route::prefix('admin')->name('admin.')->group(function () {
//     //     Route::resource('users', UserController::class);
//     // });
// });

// // Social login (Google)
// use App\Http\Controllers\Auth\SocialAuthController;

// Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
// Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// require __DIR__.'/settings.php';
// // Route dashboard — dibutuhkan oleh Auth & DashboardTest
// // Guest akan di-redirect ke login oleh middleware auth
// // Authenticated user melihat halaman dashboard (200 OK)
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// // ============================================================
// // PBI-127: Halaman sertifikat milik siswa
// // Hanya bisa diakses oleh user dengan role Siswa
// // ============================================================
// Route::middleware(['auth'])->group(function () {
//     // PBI-127: Halaman sertifikat milik siswa
//     Route::get('/sertifikat/saya', [SertifikatController::class, 'milikku'])
//         ->name('sertifikat.saya');

//     // Dashboard Siswa — portal dengan sidebar modern
//     Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])
//         ->name('siswa.dashboard');
// });

// // PBI-128: Halaman verifikasi sertifikat (publik, tanpa login)
// Route::get('/sertifikat/verifikasi/{nomor}', [SertifikatController::class, 'verifikasi'])
//     ->name('sertifikat.verifikasi');
