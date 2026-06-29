<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use App\Models\Sesi;
use App\Models\Siswa;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\Auth;

class AbsensiSesi extends Component
{
    public $sesiId;
    public $sesi;
    
    // Menampung input status dan catatan dari form (key berupa siswa_id)
    public $statusHadir = [];
    public $catatan = [];

    public function mount($sesiId)
    {
        $this->sesiId = $sesiId;
        
        // Mengambil data sesi beserta relasi kelasnya
        $this->sesi = Sesi::with('kelas')->findOrFail($sesiId);
        
        // Ambil daftar siswa untuk inisialisasi awal status kehadiran
        $siswaList = Siswa::where('kelas_id', $this->sesi->kelas_id)->get();

        // Set default status 'hadir' atau ambil data lama jika sudah pernah absen
        foreach ($siswaList as $siswa) {
            $kehadiranLama = Kehadiran::where('sesi_id', $this->sesiId)
                                      ->where('siswa_id', $siswa->id)
                                      ->first();

            $this->statusHadir[$siswa->id] = $kehadiranLama ? $kehadiranLama->status_hadir : 'hadir';
            $this->catatan[$siswa->id] = $kehadiranLama ? $kehadiranLama->catatan : '';
        }
    }

    public function simpan()
    {
        // Loop untuk menyimpan atau memperbarui absensi setiap siswa ke database
        foreach ($this->statusHadir as $siswaId => $status) {
            Kehadiran::updateOrCreate(
                [
                    'sesi_id'  => $this->sesiId,
                    'siswa_id' => $siswaId,
                ],
                [
                    'status_hadir'     => $status,
                    'catatan'          => $this->catatan[$siswaId] ?? null,
                    'dicatat_oleh'     => Auth::id(), // Mengambil ID admin/instruktur yang sedang login
                    'waktu_pencatatan' => now(),
                ]
            );
        }

        // Kirim sinyal flash message sukses ke halaman depan
        session()->flash('message', 'Absensi berhasil disimpan!');
    }

    public function render()
    {
        // Mengambil daftar siswa secara realtime berdasarkan kelas_id dari sesi ini
        $siswaList = Siswa::where('kelas_id', $this->sesi->kelas_id)->get();

        return view('livewire.absensi-sesi', [
            'siswaList' => $siswaList
        ]);
    }
}