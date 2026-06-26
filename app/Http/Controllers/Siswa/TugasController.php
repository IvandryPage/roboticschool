<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentKelas;
use App\Models\Kelas;
use App\Models\PengumpulanTugas;
use App\Models\SesiLive;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $siswa = $user?->siswa;

        if (!$siswa) {
            return redirect('/admin');
        }

        // Kelas aktif siswa
        $kelasIds = EnrollmentKelas::where('siswa_id', $siswa->id)
            ->where('status', 'Aktif')
            ->pluck('kelas_id');

        // Sesi live dari kelas yang diikuti
        $sesiIds = SesiLive::whereIn('kelas_id', $kelasIds)->pluck('id');

        // Semua tugas dari sesi tersebut
        $tugas = Tugas::with(['sesi.kelas.batch.program', 'pengumpulanTugas' => fn($q) =>
                $q->where('siswa_id', $siswa->id)
            ])
            ->whereIn('sesi_id', $sesiIds)
            ->orderByDesc('batas_waktu')
            ->get();

        return view('siswa.tugas.index', compact('tugas', 'siswa'));
    }

    public function kumpul(Request $request, Tugas $tugas)
    {
        $user  = Auth::user();
        $siswa = $user?->siswa;

        if (!$siswa) {
            abort(403);
        }

        // Cek deadline
        if ($tugas->batas_waktu && now()->greaterThan($tugas->batas_waktu)) {
            return back()->with('error', 'Deadline tugas sudah lewat. Pengumpulan ditutup.');
        }

        // Cek sudah pernah kumpul
        $existing = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah mengumpulkan tugas ini sebelumnya.');
        }

        $request->validate([
            'file_jawaban' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,zip',
            'catatan_siswa' => 'nullable|string|max:1000',
        ]);

        $filePath = null;
        if ($request->hasFile('file_jawaban')) {
            $filePath = $request->file('file_jawaban')
                ->store('pengumpulan-tugas', 'public');
        }

        PengumpulanTugas::create([
            'tugas_id'      => $tugas->id,
            'siswa_id'      => $siswa->id,
            'file_jawaban'  => $filePath,
            'catatan_siswa' => $request->catatan_siswa,
            'waktu_kumpul'  => now(),
            'status_penilaian' => 'Menunggu',
        ]);

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
