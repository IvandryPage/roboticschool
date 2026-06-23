<?php

use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SiswaDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ForumTopik;
use App\Models\ForumKomentar;
use App\Models\Kelas;


require __DIR__ . '/settings.php';

// Halaman utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');
    // Route dashboard — dibutuhkan oleh Auth & DashboardTest
    // Guest akan di-redirect ke login oleh middleware auth
    // Authenticated user melihat halaman dashboard (200 OK)
    //     Route::get('/dashboard', function () {
    //         return view('dashboard');
    //     })->middleware(['auth'])->name('dashboard');

    // PBI-127: Halaman sertifikat milik siswa
    Route::get('/sertifikat/saya', [SertifikatController::class, 'milikku'])
        ->name('sertifikat.saya');

    // Dashboard Siswa — portal dengan sidebar modern
    Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])
        ->name('siswa.dashboard');

    Route::get('keluhan', [\App\Http\Controllers\KeluhanController::class, 'create'])
        ->name('keluhan.create');

    Route::post('keluhan', [\App\Http\Controllers\KeluhanController::class, 'store'])
        ->name('keluhan.store');

    Route::get('keluhan/saya', [\App\Http\Controllers\KeluhanController::class, 'index'])
        ->name('keluhan.saya');



    Route::get('/forum', function () {

        $topiks = ForumTopik::with(['pembuat', 'komentar'])
            ->latest()
            ->get();

        return view('forum.index', compact('topiks'));

    })->name('forum.index');


    Route::post('/forum', function (Request $request) {

        ForumTopik::create([
            'kelas_id' => Kelas::first()->id,
            'pembuat_id' => Auth::id(),
            'judul' => 'Diskusi ' . now()->format('d M Y H:i'),
            'konten' => $request->konten,
        ]);

        return back();

    })->name('forum.store');


    Route::post('/forum/{topik}/reply', function (ForumTopik $topik, Request $request) {

        ForumKomentar::create([
            'topik_id' => $topik->id,
            'user_id' => Auth::id(),
            'komentar' => $request->komentar,
        ]);

        return back();

    })->name('forum.reply');


    Route::get('/forum/{topik}', function (ForumTopik $topik) {

        $topik->load([
            'pembuat',
            'komentar.user'
        ]);

        return view('forum.show', compact('topik'));

    })->name('forum.show');


});


// PBI-128: Halaman verifikasi sertifikat (publik, tanpa login)
Route::get('/sertifikat/verifikasi/{nomor}', [SertifikatController::class, 'verifikasi'])
    ->name('sertifikat.verifikasi');
