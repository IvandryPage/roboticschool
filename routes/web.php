<?php

use Illuminate\Support\Facades\Route;
use App\Models\ProgramKursus; // Tambahkan ini

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

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