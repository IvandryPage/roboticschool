<?php

use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SiswaDashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\ProgramKursus; // Tambahkan ini
use App\Http\Controllers\Siswa\MateriController;

use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ForumTopik;
use App\Models\ForumKomentar;
use App\Models\Kelas;


require __DIR__ . '/settings.php';

Route::get('/siswa/materi', [MateriController::class, 'index'])->name('siswa.materi.index');
// Route dasar PB-14
// Route::view('/', 'welcome')->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::view('dashboard', 'dashboard')->name('dashboard');
// });

// require __DIR__.'/settings.php';

// use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\UserController;

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

        return redirect()->route('forum.index');

    })->name('forum.store');


    Route::post('/forum/{topik}/reply', function (ForumTopik $topik, Request $request) {

        ForumKomentar::create([
            'topik_id' => $topik->id,
            'user_id' => Auth::id(),
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('forum.index');

    })->name('forum.reply');


    Route::get('/forum/{topik}', function (ForumTopik $topik) {

        $topik->load([
            'pembuat',
            'komentar.user'
        ]);

        return view('forum.show', compact('topik'));

    })->name('forum.show');


});

<<<<<<< HEAD
Route::get('/bypass-program', function () {
    // Kita cek dulu apa tabelnya beneran kosong
    if (ProgramKursus::count() == 0) {
        ProgramKursus::create([
            'nama_program' => 'Robotik Dasar (Dummy)',
            'deskripsi'    => 'Data otomatis untuk testing',
            'biaya'        => 0,
            'status_tampil'=> true,
        ]);
        return "Berhasil! Data dummy sudah dimasukkan. Silakan buka halaman Create Batch.";
    }
    return "Data sudah ada di database, tidak perlu ditambah lagi.";
});

require __DIR__.'/settings.php';
=======
/*
|--------------------------------------------------------------------------
| PENDAFTARAN
|--------------------------------------------------------------------------
*/

Route::get('/daftar', [PendaftaranController::class, 'create'])
    ->name('pendaftaran.create');

Route::post('/daftar', [PendaftaranController::class, 'store'])
    ->name('pendaftaran.store');

Route::get('/pendaftaran/{pendaftaran}/edit',[PendaftaranController::class, 'edit'])
    ->name('pendaftaran.edit');

Route::put('/pendaftaran/{pendaftaran}',[PendaftaranController::class, 'update'])
    ->name('pendaftaran.update');

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
    '/pendaftaran/{pendaftaran}/sukses',
    [PendaftaranController::class, 'success']
)->name('pendaftaran.success');

/*
|--------------------------------------------------------------------------
| CEK STATUS
|--------------------------------------------------------------------------
*/

Route::get('/cek-status', [PendaftaranController::class, 'cekStatus'])
    ->name('pendaftaran.status');

Route::post('/cek-status', [PendaftaranController::class, 'cariStatus'])
    ->name('pendaftaran.cari');

/*
|--------------------------------------------------------------------------
| HALAMAN REVISI
|--------------------------------------------------------------------------
*/
    Route::get('/pendaftaran/{pendaftaran}/revisi', 
    [PendaftaranController::class, 'formRevisi']
)->name('pendaftaran.revisi');

/*
|--------------------------------------------------------------------------
| REVISI DOKUMEN
|--------------------------------------------------------------------------
*/
Route::get('/pendaftaran/{pendaftaran}/revisi', [PendaftaranController::class, 'revisi'])
    ->name('pendaftaran.revisi');

Route::post('/pendaftaran/{pendaftaran}/revisi', [PendaftaranController::class, 'storeRevisi'])
    ->name('pendaftaran.revisi.store');

   
    Route::get(
    '/pendaftaran/{pendaftaran}/revisi',
    [PendaftaranController::class, 'revisi']
)->name('pendaftaran.revisi');

Route::post(
    '/pendaftaran/{pendaftaran}/revisi',
    [PendaftaranController::class, 'storeRevisi']
)->name('pendaftaran.revisi.store');

/*
|--------------------------------------------------------------------------
| LEGACY SUCCESS (boleh dihapus nanti)
|--------------------------------------------------------------------------
*/

Route::get('/daftar/sukses', [PendaftaranController::class, 'success'])
    ->name('pendaftaran.success');

require __DIR__.'/settings.php';

// PBI-128: Halaman verifikasi sertifikat (publik, tanpa login)
Route::get('/sertifikat/verifikasi/{nomor}', [SertifikatController::class, 'verifikasi'])
    ->name('sertifikat.verifikasi');
>>>>>>> main
