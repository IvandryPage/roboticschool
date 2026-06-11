<x-filament-panels::page>
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Arsip Laporan 📋</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola laporan operasional lembaga</p>
    </div>

    {{-- Form Tambah Laporan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Tambah Laporan Baru</h2>
        <form wire:submit="simpan">
            {{ $this->form }}
            <div class="mt-4">
                <button type="submit" class="fi-btn fi-btn-size-md fi-color-primary fi-btn-color-primary px-4 py-2 rounded-lg text-sm font-medium text-white" style="background-color: #0891b2;">
    Simpan Laporan
</button>
            </div>
        </form>
    </div>

    {{-- Daftar Laporan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Daftar Laporan</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Judul</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Tipe</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Periode</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Dibuat Oleh</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Tanggal</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($this->getLaporan() as $laporan)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $laporan->judul }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ ucfirst($laporan->tipe_laporan) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $laporan->periode ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $laporan->pembuat->nama_lengkap ?? $laporan->pembuat->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $laporan->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <button wire:click="hapus('{{ $laporan->id }}')"
                            wire:confirm="Yakin ingin menghapus laporan ini?"
                            class="text-red-500 hover:text-red-700 text-xs font-medium transition">
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada laporan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>