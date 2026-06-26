<x-layouts::app :title="'Jadwal Live Session'">

    <div class="mb-6">
        <flux:heading size="xl" class="font-bold">Jadwal Live Session</flux:heading>
        <flux:text class="text-zinc-500 mt-1">Sesi live mendatang dan riwayat sesi kelasmu.</flux:text>
    </div>

    {{-- Mendatang --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-3">
            <flux:icon name="calendar" class="size-4 text-cyan-500" />
            <flux:heading size="sm" class="font-semibold">Sesi Mendatang</flux:heading>
        </div>

        @if($sesiMendatang->isEmpty())
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-8 text-center shadow-sm">
                <flux:text class="text-zinc-400">Belum ada sesi live yang dijadwalkan.</flux:text>
            </div>
        @else
            <div class="space-y-3">
                @foreach($sesiMendatang as $sesi)
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl px-5 py-4 shadow-sm flex items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 text-center min-w-[48px]">
                                <div class="text-xs text-zinc-400 uppercase">{{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('M') }}</div>
                                <div class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 leading-tight">
                                    {{ \Carbon\Carbon::parse($sesi->tanggal)->format('d') }}
                                </div>
                            </div>
                            <div class="min-w-0">
                                <div class="font-semibold text-zinc-800 dark:text-zinc-100">{{ $sesi->judul_sesi }}</div>
                                <div class="text-sm text-zinc-400 mt-0.5">
                                    {{ $sesi->kelas?->nama_kelas ?? '-' }} •
                                    {{ \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') }}
                                    –
                                    {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }} WIB
                                </div>
                                @if($sesi->kelas?->instruktur)
                                    <div class="text-xs text-zinc-400 mt-0.5">
                                        Instruktur: {{ $sesi->kelas->instruktur->nama ?? '-' }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($sesi->link_akses)
                            <a href="{{ $sesi->link_akses }}" target="_blank">
                                <flux:button variant="primary" size="sm" icon="video-camera">
                                    Bergabung
                                </flux:button>
                            </a>
                        @else
                            <flux:badge color="gray" size="sm">Link Belum Tersedia</flux:badge>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Riwayat --}}
    <div>
        <div class="flex items-center gap-2 mb-3">
            <flux:icon name="clock" class="size-4 text-zinc-400" />
            <flux:heading size="sm" class="font-semibold text-zinc-500">Riwayat Sesi</flux:heading>
        </div>

        @if($riwayatSesi->isEmpty())
            <flux:text class="text-zinc-400 text-sm">Belum ada riwayat sesi.</flux:text>
        @else
            <div class="space-y-2">
                @foreach($riwayatSesi as $sesi)
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl px-5 py-3 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="font-medium text-sm text-zinc-600 dark:text-zinc-300">{{ $sesi->judul_sesi }}</div>
                            <div class="text-xs text-zinc-400">
                                {{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d M Y') }} •
                                {{ $sesi->kelas?->nama_kelas ?? '-' }}
                            </div>
                        </div>
                        <flux:badge color="gray" size="sm">Selesai</flux:badge>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-layouts::app>
