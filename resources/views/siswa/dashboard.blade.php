<x-layouts::app :title="'Dashboard'">

    {{-- ── Greeting ── --}}
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <flux:heading size="xl" class="font-bold">
                {{ $greeting }}, {{ explode(' ', $siswa->nama_lengkap)[0] }}! 👋
            </flux:heading>
            <flux:text class="text-zinc-500 mt-1">
                {{ now()->translatedFormat('l, d F Y') }}
            </flux:text>
        </div>
        <flux:button href="{{ route('sertifikat.saya') }}" variant="primary" icon="document-text" size="sm">
            Sertifikat Saya
        </flux:button>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm px-5 py-4">
            <div class="flex items-center justify-between mb-3">
                <flux:text class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Kelas Aktif</flux:text>
                <div class="size-8 rounded-lg bg-teal-50 dark:bg-teal-900/30 flex items-center justify-center">
                    <flux:icon name="book-open" class="size-4 text-teal-500" />
                </div>
            </div>
            <div class="text-3xl font-black text-zinc-800 dark:text-zinc-100">{{ $stats['kelas_aktif'] }}</div>
            <flux:text class="text-xs text-zinc-400 mt-1">{{ $stats['kelas_selesai'] }} kelas selesai</flux:text>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm px-5 py-4">
            <div class="flex items-center justify-between mb-3">
                <flux:text class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Kehadiran</flux:text>
                <div class="size-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                    <flux:icon name="user-group" class="size-4 text-blue-500" />
                </div>
            </div>
            <div class="text-3xl font-black text-zinc-800 dark:text-zinc-100">{{ $stats['rata_kehadiran'] }}%</div>
            <flux:text class="text-xs mt-1 {{ $stats['rata_kehadiran'] >= 75 ? 'text-green-500' : 'text-amber-500' }}">
                {{ $stats['rata_kehadiran'] >= 75 ? '✓ Memenuhi syarat (≥75%)' : '⚠ Di bawah syarat minimum' }}
            </flux:text>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm px-5 py-4">
            <div class="flex items-center justify-between mb-3">
                <flux:text class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Rata Nilai</flux:text>
                <div class="size-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center">
                    <flux:icon name="star" class="size-4 text-amber-500" />
                </div>
            </div>
            <div class="text-3xl font-black text-zinc-800 dark:text-zinc-100">{{ $stats['rata_nilai'] }}</div>
            <flux:text class="text-xs mt-1 {{ $stats['rata_nilai'] >= 70 ? 'text-green-500' : 'text-amber-500' }}">
                {{ $stats['rata_nilai'] >= 70 ? '✓ Memenuhi syarat (≥70)' : '⚠ Di bawah syarat minimum' }}
            </flux:text>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm px-5 py-4">
            <div class="flex items-center justify-between mb-3">
                <flux:text class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Sertifikat</flux:text>
                <div class="size-8 rounded-lg bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center">
                    <flux:icon name="document-text" class="size-4 text-purple-500" />
                </div>
            </div>
            <div class="text-3xl font-black text-zinc-800 dark:text-zinc-100">{{ $stats['total_sertifikat'] }}</div>
            <flux:text class="text-xs text-zinc-400 mt-1">
                @if($stats['total_sertifikat'] > 0)
                    <a href="{{ route('sertifikat.saya') }}" class="text-teal-500 hover:underline">Lihat semua →</a>
                @else
                    Belum ada sertifikat
                @endif
            </flux:text>
        </div>
    </div>

    {{-- ── Middle Grid: Sesi Hari Ini + Progress ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

        {{-- Sesi Hari Ini --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <flux:icon name="video-camera" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Sesi Hari Ini</flux:heading>
                </div>
                <a href="{{ route('siswa.jadwal.index') }}" class="text-xs font-semibold text-teal-500 hover:underline">
                    Lihat Semua
                </a>
            </div>

            @if($sesiHariIni->isEmpty())
                <div class="px-5 py-10 text-center">
                    <flux:icon name="calendar" class="size-10 text-zinc-200 dark:text-zinc-700 mx-auto mb-3" />
                    <flux:text class="text-zinc-400 text-sm">Tidak ada sesi live hari ini.</flux:text>
                </div>
            @else
                @php $sesi = $sesiHariIni->first(); @endphp
                <div class="px-5 py-4 bg-teal-50 dark:bg-teal-900/20 border-b border-teal-100 dark:border-teal-800">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="flex items-center gap-1 text-xs font-bold text-red-500 uppercase">
                                    <span class="size-1.5 rounded-full bg-red-500 animate-pulse inline-block"></span>
                                    Live
                                </span>
                            </div>
                            <div class="font-semibold text-zinc-800 dark:text-zinc-100">
                                {{ $sesi->judul_sesi ?? 'Sesi Kelas' }}
                            </div>
                            <div class="text-xs text-teal-600 dark:text-teal-400 mt-0.5">
                                {{ $sesi->kelas?->batch?->program?->nama_program ?? $sesi->kelas?->nama_kelas }}
                            </div>
                        </div>
                        <flux:text class="text-xs font-bold text-zinc-500 shrink-0 mt-1">
                            {{ $sesi->jam_mulai ? \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') : '' }}
                            @if($sesi->jam_selesai) — {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }} WIB @endif
                        </flux:text>
                    </div>
                    @if($sesi->link_akses)
                        <a href="{{ $sesi->link_akses }}" target="_blank">
                            <flux:button variant="primary" size="sm" icon="arrow-top-right-on-square">
                                Bergabung Sekarang
                            </flux:button>
                        </a>
                    @else
                        <flux:badge color="gray" size="sm">Link Belum Tersedia</flux:badge>
                    @endif
                </div>
            @endif

            {{-- Sesi Berikutnya --}}
            @if($sesiBerikutnya->isNotEmpty())
                <div class="divide-y divide-zinc-50 dark:divide-zinc-800">
                    @foreach($sesiBerikutnya as $next)
                        @php $tgl = \Carbon\Carbon::parse($next->tanggal); @endphp
                        <div class="px-5 py-3 flex items-center gap-3">
                            <div class="shrink-0 text-center w-10 bg-zinc-50 dark:bg-zinc-800 rounded-lg py-1.5">
                                <div class="text-xs font-bold text-teal-500 uppercase">{{ $tgl->format('M') }}</div>
                                <div class="text-lg font-black text-zinc-800 dark:text-zinc-100 leading-none">{{ $tgl->format('d') }}</div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-semibold text-zinc-700 dark:text-zinc-200 truncate">
                                    {{ $next->judul_sesi ?? 'Sesi Kelas' }}
                                </div>
                                <div class="text-xs text-zinc-400">
                                    {{ $next->kelas?->nama_kelas }} ·
                                    {{ $tgl->translatedFormat('l') }},
                                    {{ $next->jam_mulai ? \Carbon\Carbon::parse($next->jam_mulai)->format('H:i') : '' }} WIB
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif($sesiHariIni->isEmpty())
                {{-- already showed empty state --}}
            @endif
        </div>

        {{-- Progress Belajar --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <flux:icon name="chart-bar" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Progress Belajar</flux:heading>
                </div>
                <a href="{{ route('siswa.progres.index') }}" class="text-xs font-semibold text-teal-500 hover:underline">
                    Detail
                </a>
            </div>

            @if($progressList->isEmpty())
                <div class="px-5 py-10 text-center">
                    <flux:icon name="chart-bar" class="size-10 text-zinc-200 dark:text-zinc-700 mx-auto mb-3" />
                    <flux:text class="text-zinc-400 text-sm">Belum ada data progress.</flux:text>
                </div>
            @else
                <div class="divide-y divide-zinc-50 dark:divide-zinc-800">
                    @foreach($progressList->take(4) as $prog)
                        @php
                            $pct = $prog->persentase_penyelesaian ?? $prog->persentase_kehadiran ?? 0;
                            $kls = $kelasAktif->firstWhere('kelas_id', $prog->kelas_id)
                                ?? $kelasSelesai->firstWhere('kelas_id', $prog->kelas_id);
                            $nm  = $kls?->kelas?->batch?->program?->nama_program
                                ?? $kls?->kelas?->nama_kelas
                                ?? 'Kelas';
                            $barColor = $pct >= 75 ? 'bg-teal-500' : ($pct >= 50 ? 'bg-amber-400' : 'bg-red-400');
                        @endphp
                        <div class="px-5 py-3.5">
                            <div class="flex items-center justify-between mb-1.5">
                                <flux:text class="text-sm font-semibold text-zinc-700 dark:text-zinc-200 truncate max-w-[70%]">
                                    {{ $nm }}
                                </flux:text>
                                <flux:text class="text-sm font-bold text-teal-600">{{ number_format($pct, 0) }}%</flux:text>
                            </div>
                            <div class="h-1.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                <div class="h-full {{ $barColor }} rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                            </div>
                            <flux:text class="text-xs text-zinc-400 mt-1">
                                Kehadiran: {{ number_format($prog->persentase_kehadiran ?? 0, 1) }}% ·
                                Nilai: {{ number_format($prog->rata_nilai_tugas ?? 0, 1) }}
                            </flux:text>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ── Kelas yang Diikuti ── --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <flux:icon name="academic-cap" class="size-4 text-zinc-400" />
                <flux:heading size="sm" class="font-semibold">Kelas yang Diikuti</flux:heading>
            </div>
            <flux:text class="text-xs text-zinc-400">{{ $enrollments->count() }} kelas</flux:text>
        </div>

        @if($enrollments->isEmpty())
            <div class="px-5 py-10 text-center">
                <flux:icon name="academic-cap" class="size-10 text-zinc-200 dark:text-zinc-700 mx-auto mb-3" />
                <flux:text class="text-zinc-400 text-sm">Belum terdaftar di kelas manapun.</flux:text>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-zinc-50 dark:bg-zinc-800/50">
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Kelas</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Program</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Instruktur</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Kehadiran</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-2.5 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800">
                        @foreach($enrollments as $enroll)
                            @php
                                $kls       = $enroll->kelas;
                                $prog      = $progressList->firstWhere('kelas_id', $kls?->id);
                                $kehadiran = $prog?->persentase_kehadiran ?? 0;
                                $hasSert   = $sertifikat->firstWhere('kelas_id', $kls?->id);
                                $statusColor = match($enroll->status) {
                                    'Aktif'   => 'green',
                                    'Selesai' => 'blue',
                                    default   => 'yellow',
                                };
                            @endphp
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-zinc-800 dark:text-zinc-100">{{ $kls?->nama_kelas ?? '–' }}</div>
                                    <div class="text-xs text-zinc-400">
                                        Sejak {{ \Carbon\Carbon::parse($enroll->tanggal_bergabung)->translatedFormat('M Y') }}
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-zinc-600 dark:text-zinc-300">
                                    {{ $kls?->batch?->program?->nama_program ?? '–' }}
                                </td>
                                <td class="px-5 py-3 text-zinc-600 dark:text-zinc-300">
                                    {{ $kls?->instruktur?->nama_lengkap ?? '–' }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="text-sm font-bold {{ $kehadiran >= 75 ? 'text-green-600' : 'text-amber-500' }}">
                                        {{ number_format($kehadiran, 1) }}%
                                    </div>
                                    <div class="w-16 h-1.5 bg-zinc-100 dark:bg-zinc-700 rounded-full mt-1 overflow-hidden">
                                        <div class="h-full rounded-full {{ $kehadiran >= 75 ? 'bg-teal-500' : 'bg-amber-400' }}"
                                             style="width: {{ min(100, $kehadiran) }}%"></div>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <flux:badge color="{{ $statusColor }}" size="sm">{{ $enroll->status }}</flux:badge>
                                </td>
                                <td class="px-5 py-3">
                                    @if($hasSert)
                                        <flux:button href="{{ route('sertifikat.saya') }}" size="sm" variant="ghost" icon="document-text">
                                            Sertifikat
                                        </flux:button>
                                    @else
                                        <flux:button href="{{ route('siswa.progres.index') }}" size="sm" variant="ghost" icon="eye">
                                            Detail
                                        </flux:button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-layouts::app>
