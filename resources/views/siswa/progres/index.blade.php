<x-layouts::app :title="'Progres Belajar'">

    <div class="mb-6">
        <flux:heading size="xl" class="font-bold">Progres Belajar</flux:heading>
        <flux:text class="text-zinc-500 mt-1">Rekapitulasi kehadiran, nilai tugas, dan penyelesaian programmu.</flux:text>
    </div>

    @if(empty($progresPerKelas))
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-12 text-center shadow-sm">
            <flux:icon name="chart-bar" class="size-10 text-zinc-300 mx-auto mb-3" />
            <flux:text class="text-zinc-400">Belum ada data progres. Daftarkan dirimu ke kelas terlebih dahulu.</flux:text>
        </div>
    @else
        <div class="space-y-5">
            @foreach($progresPerKelas as $p)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">

                    {{-- Header kelas --}}
                    <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <div>
                            <div class="font-semibold text-zinc-800 dark:text-zinc-100">
                                {{ $p['kelas']->nama_kelas }}
                            </div>
                            <div class="text-xs text-zinc-400 mt-0.5">
                                {{ $p['kelas']->batch?->program?->nama_program ?? '-' }} •
                                Status:
                                <flux:badge color="{{ $p['enrollment']->status === 'Aktif' ? 'green' : 'gray' }}" size="sm">
                                    {{ $p['enrollment']->status }}
                                </flux:badge>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-zinc-400">Penyelesaian</div>
                            <div class="text-2xl font-bold text-cyan-600">{{ $p['persentase_selesai'] }}%</div>
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-800">
                        <div class="h-2.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="h-full bg-cyan-500 rounded-full transition-all"
                                 style="width: {{ min($p['persentase_selesai'], 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Stat grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-zinc-100 dark:divide-zinc-800">
                        <div class="px-5 py-4 text-center">
                            <div class="text-xs text-zinc-400 uppercase tracking-wide">Kehadiran</div>
                            <div class="text-xl font-bold text-zinc-800 dark:text-zinc-100 mt-1">
                                {{ $p['persentase_hadir'] }}%
                            </div>
                            <div class="text-xs text-zinc-400">{{ $p['hadir'] }}/{{ $p['total_sesi'] }} sesi</div>
                        </div>
                        <div class="px-5 py-4 text-center">
                            <div class="text-xs text-zinc-400 uppercase tracking-wide">Rata Nilai</div>
                            <div class="text-xl font-bold text-zinc-800 dark:text-zinc-100 mt-1">
                                {{ $p['rata_nilai'] ?: '-' }}
                            </div>
                            <div class="text-xs text-zinc-400">dari tugas dinilai</div>
                        </div>
                        <div class="px-5 py-4 text-center">
                            <div class="text-xs text-zinc-400 uppercase tracking-wide">Tugas Dikumpul</div>
                            <div class="text-xl font-bold text-zinc-800 dark:text-zinc-100 mt-1">
                                {{ $p['sudah_dikumpul'] }}/{{ $p['total_tugas'] }}
                            </div>
                            <div class="text-xs text-zinc-400">tugas</div>
                        </div>
                        <div class="px-5 py-4 text-center">
                            <div class="text-xs text-zinc-400 uppercase tracking-wide">Sudah Dinilai</div>
                            <div class="text-xl font-bold text-zinc-800 dark:text-zinc-100 mt-1">
                                {{ $p['sudah_dinilai'] }}
                            </div>
                            <div class="text-xs text-zinc-400">tugas</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-layouts::app>
