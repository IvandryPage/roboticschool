<x-filament-panels::page>
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Instruktur 🎓</h1>
        <p class="text-sm text-gray-500 mt-1">Evaluasi kelas dan progress siswa</p>
    </div>

    {{-- Daftar Kelas --}}
    @forelse($this->getKelasSaya() as $kelas)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="bg-cyan-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 8v-4a1 1 0 011-1h2a1 1 0 011 1v4m-4 0h4"/>
                </svg>
            </div>
            <h2 class="text-base font-semibold text-gray-800">{{ $kelas->nama_kelas }}</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Nama Siswa</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Kehadiran</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Rata-rata Nilai</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Tugas Dikumpul</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($this->getEvaluasiKelas($kelas->id) as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $row['nama'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $row['total_hadir'] }}/{{ $row['total_sesi'] }} sesi ({{ $row['persen_hadir'] }}%)</td>
                    <td class="px-6 py-4 text-gray-600">{{ $row['rata_nilai'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $row['tugas_kumpul'] }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $row['status'] === 'Lulus' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $row['status'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada siswa di kelas ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
        Kamu belum memiliki kelas yang ditugaskan
    </div>
    @endforelse
</x-filament-panels::page>