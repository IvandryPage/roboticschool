<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ForumTopik;
use App\Models\ForumKomentar;
use App\Models\Kelas;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');

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
        $topik->load(['pembuat', 'komentar.user']);

        return view('forum.show', compact('topik'));
    })->name('forum.show');

});

require __DIR__ . '/settings.php';