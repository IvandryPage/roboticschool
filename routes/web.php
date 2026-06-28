<?php

use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SiswaDashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\ProgramKursus;
use App\Http\Controllers\Siswa\MateriController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ForumTopik;
use App\Models\ForumKomentar;
use App\Models\Kelas;
use App\Http\Controllers\Admin\UserController;

require __DIR__ . '/settings.php';

// Redirect semua panel login ke /login utama
Route::redirect('/admin/login', '/login')->name('filament.admin.auth.login');
Route::redirect('/publikasi/login', '/login')->name('filament.publikasi.auth.login');

Route::get('/', function () {
    $programs = \App\Models\ProgramKursus::where('status_tampil', true)
        ->with(['batches' => fn($q) => $q->where('status_aktif', true)->orderBy('tanggal_mulai'), 'materiProgram' => fn($q) => $q->orderBy('nomor_urut')])
        ->get();
    return view('welcome', compact('programs'));
})->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard — redirect berdasarkan role
    Route::get('dashboard', function () {
        $role = Auth::user()?->role?->nama_role;

        if ($role === 'Admin Akademik') {
            return redirect('/admin');
        }

        if ($role === 'Siswa') {
            return redirect()->route('siswa.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');

    // ── SISWA ONLY routes (protected by role.siswa) ────────────────────
    Route::middleware(['role.siswa'])->group(function () {

        // Dashboard Siswa
        Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])
            ->name('siswa.dashboard');

        // Jadwal Live Session
        Route::get('/siswa/jadwal', [\App\Http\Controllers\Siswa\JadwalController::class, 'index'])
            ->name('siswa.jadwal.index');

        // Penugasan
        Route::get('/siswa/tugas', [\App\Http\Controllers\Siswa\TugasController::class, 'index'])
            ->name('siswa.tugas.index');
        Route::post('/siswa/tugas/{tugas}/kumpul', [\App\Http\Controllers\Siswa\TugasController::class, 'kumpul'])
            ->name('siswa.tugas.kumpul');

        // Progres Belajar
        Route::get('/siswa/progres', [\App\Http\Controllers\Siswa\ProgresController::class, 'index'])
            ->name('siswa.progres.index');

        // Materi
        Route::get('/siswa/materi', [MateriController::class, 'index'])
            ->name('siswa.materi.index');

        // Sertifikat
        Route::get('/sertifikat/saya', [SertifikatController::class, 'milikku'])
            ->name('sertifikat.saya');

        // Peminjaman Aset
        Route::get('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'index'])
            ->name('peminjaman.index');
        Route::post('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'store'])
            ->name('peminjaman.store');

        // Keluhan
        Route::get('keluhan', [\App\Http\Controllers\KeluhanController::class, 'create'])
            ->name('keluhan.create');
        Route::post('keluhan', [\App\Http\Controllers\KeluhanController::class, 'store'])
            ->name('keluhan.store');
        Route::get('keluhan/saya', [\App\Http\Controllers\KeluhanController::class, 'index'])
            ->name('keluhan.saya');

        // Kuesioner Evaluasi Instruktur
        // Siswa hanya bisa mengisi evaluasi untuk kelas yang sudah selesai dan dia terdaftar
        Route::get('/evaluasi/{kelas}', function (\App\Models\Kelas $kelas) {
            return view('siswa.evaluasi.form', compact('kelas'));
        })->name('siswa.evaluasi.form');

        // Forum — hanya tampil thread dari kelas yang diikuti siswa
        Route::get('/forum', function () {
            $siswa = Auth::user()->siswa;

            $kelasIds = $siswa
                ? $siswa->enrollmentKelas()->pluck('kelas_id')
                : collect();

            $topiks = ForumTopik::with(['pembuat', 'komentar', 'kelas'])
                ->when($kelasIds->isNotEmpty(), fn ($q) => $q->whereIn('kelas_id', $kelasIds))
                ->latest()
                ->get();

            return view('forum.index', compact('topiks'));
        })->name('forum.index');

        Route::post('/forum', function (Request $request) {
            $siswa = Auth::user()->siswa;

            // Gunakan kelas pertama yang di-enroll siswa, bukan Kelas::first() global
            $kelasId = $siswa
                ? $siswa->enrollmentKelas()->latest()->value('kelas_id')
                : null;

            if (! $kelasId) {
                return redirect()->route('forum.index')
                    ->with('error', 'Kamu belum terdaftar di kelas manapun.');
            }

            ForumTopik::create([
                'kelas_id'   => $kelasId,
                'pembuat_id' => Auth::id(),
                'judul'      => $request->judul ?? ('Diskusi ' . now()->format('d M Y H:i')),
                'konten'     => $request->konten,
            ]);

            return redirect()->route('forum.index')->with('success', 'Topik berhasil dibuat.');
        })->name('forum.store');

        Route::post('/forum/{topik}/reply', function (ForumTopik $topik, Request $request) {
            ForumKomentar::create([
                'topik_id' => $topik->id,
                'user_id'  => Auth::id(),
                'komentar' => $request->komentar,
            ]);
            return redirect()->route('forum.show', $topik)->with('success', 'Balasan dikirim.');
        })->name('forum.reply');

        Route::get('/forum/{topik}', function (ForumTopik $topik) {
            $topik->load(['pembuat', 'komentar.user']);
            return view('forum.show', compact('topik'));
        })->name('forum.show');

    }); // end role.siswa group

});

/*
|--------------------------------------------------------------------------
| PENDAFTARAN (Publik — tidak perlu login)
|--------------------------------------------------------------------------
*/

Route::get('/daftar', [PendaftaranController::class, 'create'])
    ->name('pendaftaran.create');

Route::post('/daftar', [PendaftaranController::class, 'store'])
    ->name('pendaftaran.store');

Route::get('/pendaftaran/{pendaftaran}/edit', [PendaftaranController::class, 'edit'])
    ->name('pendaftaran.edit');

Route::put('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'update'])
    ->name('pendaftaran.update');

Route::get('/pendaftaran/{pendaftaran}/dokumen', [PendaftaranController::class, 'dokumen'])
    ->name('pendaftaran.dokumen');

Route::post('/pendaftaran/{pendaftaran}/dokumen', [PendaftaranController::class, 'storeDokumen'])
    ->name('pendaftaran.dokumen.store');

Route::get('/pendaftaran/{pendaftaran}/revisi', [PendaftaranController::class, 'formRevisi'])
    ->name('pendaftaran.revisi');

Route::post('/pendaftaran/{pendaftaran}/revisi', [PendaftaranController::class, 'storeRevisi'])
    ->name('pendaftaran.revisi.store');

Route::get('/pendaftaran/{pendaftaran}/sukses', [PendaftaranController::class, 'success'])
    ->name('pendaftaran.success');

/*
|--------------------------------------------------------------------------
| PEMBAYARAN
|--------------------------------------------------------------------------
*/

Route::get('/pembayaran/{pendaftaran}', [PembayaranController::class, 'index'])
    ->name('pembayaran.index');

Route::post('/pembayaran/{pendaftaran}', [PembayaranController::class, 'store'])
    ->name('pembayaran.store');

/*
|--------------------------------------------------------------------------
| LEGAL
|--------------------------------------------------------------------------
*/

Route::get('/syarat-ketentuan/{pendaftaran}', function (\App\Models\Pendaftaran $pendaftaran) {
    return view('legal.syarat', compact('pendaftaran'));
})->name('syarat');

Route::get('/kebijakan-refund/{pendaftaran}', function (\App\Models\Pendaftaran $pendaftaran) {
    return view('legal.refund', compact('pendaftaran'));
})->name('refund');

/*
|--------------------------------------------------------------------------
| CEK STATUS PENDAFTARAN (Publik)
|--------------------------------------------------------------------------
*/

Route::get('/cek-status', [PendaftaranController::class, 'cekStatus'])
    ->name('pendaftaran.status');

Route::post('/cek-status', [PendaftaranController::class, 'cariStatus'])
    ->name('pendaftaran.cari');

// Status pendaftaran milik user yang sedang login
Route::get('/pendaftaran-saya', [PendaftaranController::class, 'statusSaya'])
    ->middleware(['auth', 'verified'])
    ->name('pendaftaran.status.saya');

// Legacy success URL
Route::get('/daftar/sukses', [PendaftaranController::class, 'success'])
    ->name('pendaftaran.success.legacy');

/*
|--------------------------------------------------------------------------
| SERTIFIKAT — Verifikasi Publik
|--------------------------------------------------------------------------
*/

Route::get('/sertifikat/verifikasi/{nomor}', [SertifikatController::class, 'verifikasi'])
    ->name('sertifikat.verifikasi');

/*
|--------------------------------------------------------------------------
| BYPASS DUMMY (Dev only)
|--------------------------------------------------------------------------
*/

Route::get('/bypass-program', function () {
    if (ProgramKursus::count() == 0) {
        ProgramKursus::create([
            'nama_program'  => 'Robotik Dasar (Dummy)',
            'deskripsi'     => 'Data otomatis untuk testing',
            'biaya'         => 0,
            'status_tampil' => true,
        ]);
        return "Berhasil! Data dummy sudah dimasukkan. Silakan buka halaman Create Batch.";
    }
    return "Data sudah ada di database, tidak perlu ditambah lagi.";
});

/*
|--------------------------------------------------------------------------
| SUB-ROUTE FILES
|--------------------------------------------------------------------------
*/

require __DIR__.'/admin.php';
require __DIR__.'/siswa.php';
