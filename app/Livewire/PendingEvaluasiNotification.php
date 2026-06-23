<?php

namespace App\Livewire;

use App\Models\EnrollmentKelas;
use App\Models\EvaluasiInstruktur;
use Illuminate\Support\Collection;
use Livewire\Component;

class PendingEvaluasiNotification extends Component
{
    public Collection $pendingKelas;

    public function mount(): void
    {
        $siswa = auth()->user()->siswa;

        if (! $siswa) {
            $this->pendingKelas = collect();
            return;
        }

        $sudahDiisiIds = EvaluasiInstruktur::where('siswa_id', $siswa->id)
            ->pluck('kelas_id');

        $this->pendingKelas = EnrollmentKelas::with(['kelas.instruktur'])
            ->where('siswa_id', $siswa->id)
            ->whereHas('kelas', fn ($q) => $q->where('status', 'selesai'))
            ->whereNotIn('kelas_id', $sudahDiisiIds)
            ->get()
            ->map(fn ($enrollment) => $enrollment->kelas);
    }

    public function render()
    {
        return view('livewire.pending-evaluasi-notification');
    }
}
