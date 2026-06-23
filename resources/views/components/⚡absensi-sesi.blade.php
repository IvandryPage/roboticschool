<?php

use Livewire\Component;
use App\Models\Sesi;
use App\Models\Siswa;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\Auth;

new class extends Component
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
                    'dicatat_oleh'     => Auth::id(), // ID instruktur/admin yang sedang login
                    'waktu_pencatatan' => now(),
                ]
            );
        }

        // Kirim sinyal flash message sukses ke halaman depan
        session()->flash('message', 'Absensi berhasil disimpan!');
    }
};
?>

<div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b border-gray-200 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Absensi Kelas</h2>
        <div class="mt-2 flex items-center text-sm text-gray-600">
            <span class="bg-indigo-100 text-indigo-800 py-1 px-3 rounded-full font-medium mr-3">
                Sesi: {{ $sesi->nama_sesi ?? '-' }}
            </span>
            <span class="bg-gray-100 text-gray-800 py-1 px-3 rounded-full font-medium">
                Kelas: {{ $sesi->kelas->nama_kelas ?? '-' }}
            </span>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-6 text-sm text-green-700 bg-green-50 rounded-lg border border-green-200 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">Berhasil!</span>&nbsp;{{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="simpan">
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">No</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Siswa</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status Kehadiran</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Catatan Tambahan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        // Mengambil daftar siswa berdasarkan kelas dari sesi saat ini
                        $siswaList = \App\Models\Siswa::where('kelas_id', $sesi->kelas_id)->get();
                    @endphp

                    @forelse($siswaList as $index => $siswa)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                {{ $index + 1 }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $siswa->nama }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <div class="flex justify-center items-center space-x-6">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="radio" wire:model="statusHadir.{{ $siswa->id }}" value="hadir" class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300">
                                        <span class="ml-2 text-gray-700 group-hover:text-green-600 font-medium">Hadir</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="radio" wire:model="statusHadir.{{ $siswa->id }}" value="izin" class="w-4 h-4 text-yellow-500 focus:ring-yellow-400 border-gray-300">
                                        <span class="ml-2 text-gray-700 group-hover:text-yellow-600 font-medium">Izin</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="radio" wire:model="statusHadir.{{ $siswa->id }}" value="alpa" class="w-4 h-4 text-red-600 focus:ring-red-500 border-gray-300">
                                        <span class="ml-2 text-gray-700 group-hover:text-red-600 font-medium">Tidak Hadir</span>
                                    </label>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="text" 
                                       wire:model="catatan.{{ $siswa->id }}" 
                                       placeholder="Tulis alasan jika izin/sakit..." 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm placeholder-gray-400">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada siswa</h3>
                                <p class="mt-1 text-sm text-gray-500">Belum ada siswa yang terdaftar di kelas ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" 
                    wire:loading.attr="disabled"
                    wire:target="simpan"
                    class="inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                
                <svg wire:loading wire:target="simpan" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                
                <span wire:loading.remove wire:target="simpan">Simpan Data Absensi</span>
                <span wire:loading wire:target="simpan">Menyimpan...</span>
            </button>
        </div>
    </form>
</div>