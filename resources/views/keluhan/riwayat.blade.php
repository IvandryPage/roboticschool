<x-layouts::app :title="'Riwayat Keluhan'">

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <flux:heading size="xl" class="font-bold">Riwayat Keluhan</flux:heading>
            <flux:text class="text-zinc-500 mt-1">Daftar tiket keluhan yang pernah kamu buat beserta statusnya.</flux:text>
        </div>
        <flux:button href="{{ route('keluhan.create') }}" variant="primary" icon="plus" size="sm">
            Buat Keluhan
        </flux:button>
    </div>

    @php
        $statusColor = fn($s) => match($s) {
            'Open'        => 'teal',
            'In Progress' => 'yellow',
            'Resolved'    => 'green',
            'Closed'      => 'gray',
            default       => 'gray',
        };
        $prioritasColor = fn($p) => match($p) {
            'Tinggi' => 'red',
            'Sedang' => 'yellow',
            'Rendah' => 'green',
            default  => 'gray',
        };
        $kategoriColor = fn($k) => match($k) {
            'Pembelajaran'              => 'teal',
            'Error Sistem'              => 'red',
            'Pendaftaran & Pembayaran'  => 'yellow',
            default                     => 'green',
        };
    @endphp

    @if($tiketKeluhans->isEmpty())
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-12 text-center shadow-sm">
            <flux:icon name="inbox" class="size-12 text-zinc-200 dark:text-zinc-700 mx-auto mb-4" />
            <flux:heading size="sm" class="font-semibold text-zinc-500">Belum ada keluhan</flux:heading>
            <flux:text class="text-zinc-400 mt-1">Kamu belum pernah membuat tiket keluhan.</flux:text>
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-zinc-50 dark:bg-zinc-800/50">
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Tanggal</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Subjek</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Kategori</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Prioritas</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800">
                        @foreach($tiketKeluhans as $tiket)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-zinc-800 dark:text-zinc-100">
                                        {{ $tiket->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-zinc-400">{{ $tiket->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-5 py-3 max-w-xs">
                                    <div class="font-semibold text-zinc-800 dark:text-zinc-100 truncate" title="{{ $tiket->subjek }}">
                                        {{ $tiket->subjek }}
                                    </div>
                                    @if($tiket->deskripsi)
                                        <div class="text-xs text-zinc-400 truncate mt-0.5">{{ $tiket->deskripsi }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <flux:badge color="{{ $kategoriColor($tiket->kategori) }}" size="sm">
                                        {{ $tiket->kategori }}
                                    </flux:badge>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <flux:badge color="{{ $prioritasColor($tiket->prioritas) }}" size="sm">
                                        {{ $tiket->prioritas ?? 'Sedang' }}
                                    </flux:badge>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <flux:badge color="{{ $statusColor($tiket->status) }}" size="sm">
                                        {{ $tiket->status }}
                                    </flux:badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</x-layouts::app>
