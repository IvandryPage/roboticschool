<x-filament-panels::page>
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Selamat datang, Direktur! 👋</h1>
        <p class="text-sm text-gray-500 mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Filter --}}
    <div class="flex gap-3 mb-6">
        <select wire:model.live="filterProgram" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            <option value="">Semua Program</option>
            @foreach($this->getProgramOptions() as $id => $nama)
                <option value="{{ $id }}">{{ $nama }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterPeriode" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            <option value="">Semua Periode</option>
            @foreach($this->getPeriodeOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{-- Kartu Statistik --}}
    @php $stats = $this->getStats(); @endphp
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="bg-cyan-100 p-3 rounded-xl">
                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6 5.87a4 4 0 10-8 0m8 0a4 4 0 00-8 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Siswa</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_siswa_aktif'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-xl">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Program Aktif</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_program'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="bg-yellow-100 p-3 rounded-xl">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Sertifikat</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_sertifikat'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="bg-purple-100 p-3 rounded-xl">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Enrollment</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_enrollment'] }}</p>
            </div>
        </div>
    </div>

    {{-- Tabel Rekap Program --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Rekap Kelulusan per Program</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Program</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Total Siswa</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Total Lulus</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Tingkat Kelulusan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($this->getRekapProgram() as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $row['nama_program'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $row['total_siswa'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $row['total_lulus'] }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $row['tingkat_kelulusan'] >= 70 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $row['tingkat_kelulusan'] }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada data program</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>