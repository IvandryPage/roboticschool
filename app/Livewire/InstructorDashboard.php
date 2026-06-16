<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SesiLive;
use Illuminate\Support\Facades\Auth;

class InstructorDashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        $sesiMendatang = SesiLive::whereHas('kelas', function ($query) use ($user) {
            $query->where('instruktur_id', $user->id);
        })->where('tanggal', '>=', now()->toDateString())
          ->orderBy('tanggal', 'asc')
          ->orderBy('jam_mulai', 'asc')
          ->get();

        return view('livewire.instructor-dashboard', [
            'sesiMendatang' => $sesiMendatang,
        ]);
    }
}
