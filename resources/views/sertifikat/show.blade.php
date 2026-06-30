<x-layouts::app :title="'Sertifikat Saya'">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="font-bold">Sertifikat Saya</flux:heading>
            <flux:text class="text-zinc-500 mt-1">Bukti penyelesaian program robotika.</flux:text>
        </div>
    </div>

    @if($sertifikats->isEmpty())
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-14 text-center shadow-sm">
            <flux:icon name="document-text" class="size-12 text-zinc-300 mx-auto mb-4" />
            <flux:heading size="sm" class="font-semibold text-zinc-500">Belum ada sertifikat</flux:heading>
            <flux:text class="text-zinc-400 mt-1">Selesaikan program untuk mendapatkan sertifikat dari akademi.</flux:text>
        </div>
    @else
        <div class="space-y-4">
            @foreach($sertifikats as $s)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 size-12 rounded-xl bg-cyan-50 dark:bg-cyan-900/30 flex items-center justify-center">
                                <flux:icon name="trophy" class="size-6 text-cyan-500" />
                            </div>
                            <div>
                                <div class="font-semibold text-zinc-800 dark:text-zinc-100">
                                    {{ $s->kelas?->batch?->program?->nama_program ?? 'Program Robotika' }}
                                </div>
                                <div class="text-sm text-zinc-400 mt-0.5">
                                    No. Sertifikat: <span class="font-mono">{{ $s->nomor_sertifikat }}</span>
                                </div>
                                <div class="text-sm text-zinc-400">
                                    Terbit: {{ $s->tanggal_terbit
                                        ? \Carbon\Carbon::parse($s->tanggal_terbit)->translatedFormat('d F Y')
                                        : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="shrink-0 flex items-center gap-2">
                            @if($s->file_path)
                                <flux:button
                                    href="{{ asset('storage/' . $s->file_path) }}"
                                    target="_blank"
                                    variant="ghost"
                                    icon="arrow-top-right-on-square"
                                    size="sm"
                                >
                                    Lihat
                                </flux:button>
                                <flux:button
                                    href="{{ asset('storage/' . $s->file_path) }}"
                                    download
                                    variant="primary"
                                    icon="arrow-down-tray"
                                    size="sm"
                                >
                                    Unduh
                                </flux:button>
                            @endif
                            @if($s->verified_url)
                                <flux:button
                                    href="{{ $s->verified_url }}"
                                    target="_blank"
                                    variant="ghost"
                                    icon="shield-check"
                                    size="sm"
                                >
                                    Verifikasi
                                </flux:button>
                            @endif
                            <flux:button
                                onclick="window.print()"
                                variant="ghost"
                                icon="printer"
                                size="sm"
                            >
                                Cetak
                            </flux:button>
                        </div>
                    </div>

                    {{-- Print hint --}}
                    @if($s->file_path)
                        <div class="px-5 py-2 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-100 dark:border-zinc-800 text-xs text-zinc-400">
                            Untuk mencetak, buka sertifikat lalu tekan <kbd class="font-mono bg-zinc-200 dark:bg-zinc-700 px-1 rounded">Ctrl+P</kbd>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

</x-layouts::app>
