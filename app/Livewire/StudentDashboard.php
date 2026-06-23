<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SesiLive;
use Illuminate\Support\Facades\Auth;

class StudentDashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $siswaId = $user->siswa?->id;
        
        $sesiMendatang = SesiLive::whereHas('kelas.enrollmentKelas', function ($query) use ($siswaId) {
            $query->where('siswa_id', $siswaId)
                  ->where('status', 'Aktif');
        })->where('tanggal', '>=', now()->toDateString())
          ->orderBy('tanggal', 'asc')
          ->orderBy('jam_mulai', 'asc')
          ->get();

        $riwayatSesi = SesiLive::whereHas('kelas.enrollmentKelas', function ($query) use ($siswaId) {
            $query->where('siswa_id', $siswaId)
                  ->where('status', 'Aktif');
        })->where('tanggal', '<', now()->toDateString())
          ->orderBy('tanggal', 'desc')
          ->orderBy('jam_mulai', 'desc')
          ->get();

        return view('livewire.student-dashboard', [
            'sesiMendatang' => $sesiMendatang,
            'riwayatSesi' => $riwayatSesi,
        ]);
    }
}
