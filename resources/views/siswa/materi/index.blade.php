<x-layouts::app :title="'Modul Pembelajaran'">

    <div class="mb-6">
        <flux:heading size="xl" class="font-bold">Modul Pembelajaran</flux:heading>
        <flux:text class="text-zinc-500 mt-1">Materi pembelajaran yang diunggah instruktur untuk kelasmu.</flux:text>
    </div>

    {{-- Kelas Belum Buka --}}
    @foreach($kelasBelumBuka as $item)
        <flux:callout variant="warning" icon="clock" class="mb-4">
            Program <strong>{{ $item['program']?->nama_program ?? $item['kelas']->nama_kelas }}</strong>
            belum dibuka. Materi tersedia mulai
            {{ $item['tanggal_mulai']
                ? \Carbon\Carbon::parse($item['tanggal_mulai'])->translatedFormat('d F Y')
                : 'tanggal yang ditentukan' }}.
        </flux:callout>
    @endforeach

    @if(empty($kelasAktif) && empty($kelasBelumBuka))
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-12 text-center shadow-sm">
            <flux:icon name="book-open" class="size-12 text-zinc-200 dark:text-zinc-700 mx-auto mb-4" />
            <flux:heading size="sm" class="font-semibold text-zinc-500">Belum ada materi</flux:heading>
            <flux:text class="text-zinc-400 mt-1">Daftarkan dirimu ke kelas terlebih dahulu.</flux:text>
        </div>
    @else
        <div class="space-y-6">
            @foreach($kelasAktif as $kelas)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">

                    {{-- Header Kelas --}}
                    <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-800 flex items-center gap-2">
                        <flux:icon name="academic-cap" class="size-4 text-zinc-400" />
                        <flux:heading size="sm" class="font-semibold">
                            {{ $kelas->nama_kelas }}
                        </flux:heading>
                        @if($kelas->batch?->program)
                            <flux:badge color="teal" size="sm">
                                {{ $kelas->batch->program->nama_program }}
                            </flux:badge>
                        @endif
                    </div>

                    @php
                        $semuaMateri = $kelas->sesiLive
                            ->flatMap(fn($sesi) => $sesi->materiPembelajaran
                                ->map(fn($m) => ['materi' => $m, 'sesi' => $sesi])
                            );
                    @endphp

                    @if($semuaMateri->isEmpty())
                        <div class="px-5 py-8 text-center">
                            <flux:text class="text-zinc-400 text-sm">
                                Instruktur belum mengunggah materi untuk kelas ini.
                            </flux:text>
                        </div>
                    @else
                        {{-- Grup per Sesi --}}
                        @foreach($kelas->sesiLive as $sesi)
                            @if($sesi->materiPembelajaran->isNotEmpty())
                                <div class="border-b border-zinc-50 dark:border-zinc-800 last:border-0">

                                    {{-- Label Sesi --}}
                                    <div class="px-5 py-2 bg-zinc-50 dark:bg-zinc-800/50 flex items-center gap-2">
                                        <flux:icon name="calendar-days" class="size-3.5 text-zinc-400" />
                                        <flux:text class="text-xs font-semibold text-zinc-500">
                                            {{ $sesi->judul_sesi ?? 'Sesi' }} ·
                                            {{ $sesi->tanggal
                                                ? \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d M Y')
                                                : '' }}
                                        </flux:text>
                                    </div>

                                    {{-- Materi Items --}}
                                    <div class="divide-y divide-zinc-50 dark:divide-zinc-800">
                                        @foreach($sesi->materiPembelajaran as $materi)
                                            <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                                                <div class="min-w-0 flex-1">
                                                    <div class="font-semibold text-sm text-zinc-800 dark:text-zinc-100 truncate">
                                                        {{ $materi->judul }}
                                                    </div>
                                                    @if($materi->keterangan)
                                                        <flux:text class="text-xs text-zinc-400 mt-0.5 line-clamp-2">
                                                            {{ $materi->keterangan }}
                                                        </flux:text>
                                                    @endif
                                                </div>

                                                <div class="shrink-0 flex items-center gap-2">
                                                    @if($materi->file_path)
                                                        <flux:button
                                                            href="{{ asset('storage/' . $materi->file_path) }}"
                                                            download
                                                            size="sm"
                                                            variant="ghost"
                                                            icon="document-arrow-down"
                                                        >
                                                            Unduh
                                                        </flux:button>
                                                    @endif
                                                    @if($materi->tautan_link)
                                                        <flux:button
                                                            href="{{ $materi->tautan_link }}"
                                                            target="_blank"
                                                            size="sm"
                                                            variant="ghost"
                                                            icon="arrow-top-right-on-square"
                                                        >
                                                            Buka Tautan
                                                        </flux:button>
                                                    @endif
                                                    @if(!$materi->file_path && !$materi->tautan_link)
                                                        <flux:badge color="gray" size="sm">Belum Tersedia</flux:badge>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>
    @endif

</x-layouts::app>
