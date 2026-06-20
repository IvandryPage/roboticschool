<?php

namespace App\Http\Controllers;

use App\Models\TiketKeluhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeluhanController extends Controller
{
    /**
     * Menampilkan daftar riwayat keluhan milik user yang login.
     */
    public function index()
    {
        // Gunakan Auth::user()->id untuk memastikan UUID terambil sebagai string murni
        // tanpa risiko di-cast menjadi integer oleh helper Auth::id()
        $tiketKeluhans = TiketKeluhan::where('pelapor_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('keluhan.riwayat', compact('tiketKeluhans'));
    }

    /**
     * Menampilkan form untuk membuat keluhan baru.
     */
    public function create()
    {
        return view('keluhan.index');
    }

    /**
     * Menyimpan keluhan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:255',
            'subjek' => 'required|string|max:255',
            // Default prioritas 'Sedang' jika tidak dikirim dari form
            'prioritas' => 'nullable|string|in:Rendah,Sedang,Tinggi',
            'deskripsi' => 'required|string',
        ]);

        TiketKeluhan::create([
            'pelapor_id' => Auth::user()->id,
            'kategori' => $validated['kategori'],
            'prioritas' => $validated['prioritas'] ?? 'Sedang',
            'subjek' => $validated['subjek'],
            'deskripsi' => $validated['deskripsi'],
            'status' => 'Open',
        ]);

        // Sesuai Figma, munculkan modal sukses di halaman yang sama
        return back()->with('success_modal', true);
    }
}
