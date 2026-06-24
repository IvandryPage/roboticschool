<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentKelas;
use App\Models\MateriPembelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()?->siswa;

        if (!$siswa) {
            abort(403, 'Akun ini bukan siswa.');
        }

        $enrollments = EnrollmentKelas::with(['kelas.batch.program', 'kelas.sesiLive.materiPembelajaran'])
            ->where('siswa_id', $siswa->id)
            ->get();

        $kelasAktif      = [];
        $kelasBelumBuka  = [];

        foreach ($enrollments as $enrollment) {
            $kelas = $enrollment->kelas;
            $batch = $kelas?->batch;

            // Program dianggap terbuka jika batch sudah mulai
            $sudahBuka = $batch && $batch->tanggal_mulai
                ? Carbon::parse($batch->tanggal_mulai)->isPast()
                : false;

            if ($sudahBuka) {
                $kelasAktif[] = $kelas;
            } else {
                $kelasBelumBuka[] = [
                    'kelas'         => $kelas,
                    'tanggal_mulai' => $batch?->tanggal_mulai,
                    'program'       => $batch?->program,
                ];
            }
        }

        return view('siswa.materi.index', compact('kelasAktif', 'kelasBelumBuka'));
    }
}
