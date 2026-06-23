<?php

use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SiswaDashboardController;
use Illuminate\Support\Facades\Route;

<<<<<<< HEAD
// --- RUTE ASLI KELOMPOK ---
Route::view('/', 'welcome')->name('home');
=======
use App\Http\Controllers\Admin\UserController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

use Illuminate\Support\Facades\Auth;
>>>>>>> main

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        if (Auth::user()->role && Auth::user()->role->nama_role === 'Admin Akademik') {
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
        Route::get('aset', function() {
            return redirect('/admin/aset-robotiks');
        })->name('aset.index');
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
        Route::get('peminjaman', function() {
            return redirect('/admin/peminjaman-item-asets');
        })->name('peminjaman.index');
        Route::post('peminjaman/{peminjaman}/approve', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('peminjaman/{peminjaman}/reject', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::post('peminjaman/{peminjaman}/return', [\App\Http\Controllers\Admin\AdminPeminjamanController::class, 'confirmReturn'])->name('peminjaman.return');
    });
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

// --- RUTE REVIEW UI INVOICE (LANGSUNG DARI WEB.PHP) ---
Route::get('/dashboard/invoice', function () {
    $html = <<<HTML
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Review UI Invoice - PBI-145</title>
        <style>
            * { box-sizing: border-box; }
            body {
                font-family: 'Segoe UI', system-ui, sans-serif;
                background: #f8fafc;
                margin: 0;
                padding: 40px 20px;
            }
            .wrapper { max-width: 560px; margin: 0 auto; }
            .invoice-card {
                background: #fff;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                border: 1px solid #e2e8f0;
            }
            .invoice-header {
                background: linear-gradient(135deg, #00b4d8, #0077b6);
                color: #fff;
                padding: 24px;
            }
            .invoice-header .label {
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                opacity: 0.9;
                margin: 0 0 4px 0;
            }
            .invoice-header .no-invoice {
                font-size: 24px;
                font-weight: 700;
                margin: 0;
            }
            .invoice-body { padding: 24px; }
            .row {
                display: flex;
                justify-content: space-between;
                padding: 12px 0;
                border-bottom: 1px solid #f1f5f9;
                font-size: 14px;
            }
            .row:last-of-type { border-bottom: none; }
            .row .key { color: #64748b; }
            .row .val { color: #0f172a; font-weight: 600; text-align: right; }
            .total-box {
                background: #e6f7ff;
                border: 1px solid #bae7ff;
                border-radius: 8px;
                padding: 16px;
                margin-top: 16px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .total-box .key { font-size: 13px; color: #0050b3; font-weight: 600; }
            .total-box .val { font-size: 20px; font-weight: 700; color: #0050b3; }
            .status-pill {
                display: inline-block;
                background: #fef3c7;
                color: #92400e;
                font-size: 12px;
                font-weight: 700;
                padding: 4px 10px;
                border-radius: 999px;
            }
            .btn-dashboard {
                display: block;
                width: 100%;
                background: #00b4d8;
                color: white;
                text-align: center;
                text-decoration: none;
                padding: 14px;
                border-radius: 8px;
                font-weight: 600;
                margin-top: 20px;
                transition: background 0.2s;
            }
            .btn-dashboard:hover {
                background: #0077b6;
            }
            .footnote {
                font-size: 12px;
                color: #94a3b8;
                text-align: center;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="invoice-card">
                <div class="invoice-header">
                    <p class="label">Nomor Invoice</p>
                    <p class="no-invoice">INV-53631814</p>
                </div>
                <div class="invoice-body">
                    <div class="row">
                        <span class="key">No. Referensi Pendaftaran</span>
                        <span class="val">RBN-53631814</span>
                    </div>
                    <div class="row">
                        <span class="key">Program Kursus</span>
                        <span class="val">IoT Development</span>
                    </div>
                    <div class="row">
                        <span class="key">Tanggal Terbit</span>
                        <span class="val">21 Juni 2026</span>
                    </div>
                    <div class="row">
                        <span class="key">Status Pembayaran</span>
                        <span class="val"><span class="status-pill">Pending</span></span>
                    </div>
 
                     <div class="total-box">
                        <span class="key">Total Tagihan</span>
                        <span class="val">Rp 1.500.000</span>
                    </div>
 
                     <a href="/dashboard" class="btn-dashboard">Lanjut ke Dashboard &rarr;</a>
                </div>
            </div>
            <p class="footnote">Pratinjau Halaman Invoice &middot; PBI-145</p>
        </div>
    </body>
    </html>
    HTML;

    return response($html)->header('Content-Type', 'text/html');
});

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
