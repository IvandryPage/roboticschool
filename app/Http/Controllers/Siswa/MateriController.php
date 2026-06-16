<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\MateriPembelajaran; // Sesuaikan dengan nama Model kamu

class MateriController extends Controller
{
    public function index()
    {
        // Mengambil semua data materi pembelajaran
        $materi = MateriPembelajaran::all(); 

        // Mengirim data ke view siswa
        return view('siswa.materi.index', compact('materi'));
    }
}