<x-layouts::app :title="'Daftar Siswa Aktif'">

    {{-- ===== HEADER ===== --}}
    <div class="mb-6">
        <flux:heading size="xl" class="font-bold">Siswa Aktif</flux:heading>
        <flux:text class="text-zinc-500 mt-1">Daftar seluruh siswa yang telah memiliki akun aktif.</flux:text>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-5">
            {{ session('success') }}
        </flux:callout>
    @endif

    {{-- ===== STATISTIK (PBI-068) ===== --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        @php
            $statItems = [
                ['label' => 'Total Siswa', 'value' => $stats['total'],    'color' => 'text-zinc-700 dark:text-zinc-200'],
                ['label' => 'Aktif',       'value' => $stats['aktif'],    'color' => 'text-green-600'],
                ['label' => 'Nonaktif',    'value' => $stats['nonaktif'], 'color' => 'text-red-500'],
            ];
        @endphp
        @foreach($statItems as $s)
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl px-4 py-4 text-center shadow-sm">
                <div class="text-2xl font-bold {{ $s['color'] }}">{{ $s['value'] }}</div>
                <div class="text-xs text-zinc-500 mt-1">{{ $s['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- ===== FILTER & SEARCH (PBI-069) ===== --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl px-5 py-4 mb-5 shadow-sm">
        <form method="GET" action="{{ route('admin.siswa.index') }}">
            <div class="flex flex-col sm:flex-row gap-3">

                {{-- Search nama / email --}}
                <div class="flex-1">
                    <flux:input
                        name="search"
                        placeholder="Cari nama atau email siswa..."
                        value="{{ $search }}"
                        icon="magnifying-glass"
                    />
                </div>

                {{-- Filter program --}}
                <div class="w-full sm:w-60">
                    <flux:select name="program" placeholder="Semua Program">
                        <option value="">Semua Program</option>
                        @foreach($programList as $prog)
                            <option value="{{ $prog->id }}" {{ $program === (string)$prog->id ? 'selected' : '' }}>
                                {{ $prog->nama_program }}
                            </option>
                        @endforeach
                    </flux:select>
                </div>

                <flux:button type="submit" variant="primary" icon="funnel">Filter</flux:button>

                @if($search || $program)
                    <flux:button href="{{ route('admin.siswa.index') }}" icon="x-mark">Reset</flux:button>
                @endif
            </div>
        </form>
    </div>

    {{-- ===== TABEL DAFTAR SISWA (PBI-068) ===== --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">

        @if($siswaList->isEmpty())
            <div class="text-center py-16 text-zinc-400">
                <flux:icon name="users" class="mx-auto mb-3 size-10" />
                <p>Belum ada siswa aktif ditemukan.</p>
            </div>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="w-8">#</flux:table.column>
                    <flux:table.column>Nama Siswa</flux:table.column>
                    <flux:table.column>Program</flux:table.column>
                    <flux:table.column>Tgl. Bergabung</flux:table.column>
                    <flux:table.column>Status Akun</flux:table.column>
                    <flux:table.column class="text-center">Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($siswaList as $i => $siswa)
                        <flux:table.row>
                            <flux:table.cell class="text-zinc-400 text-sm">
                                {{ $siswaList->firstItem() + $i }}
                            </flux:table.cell>

                            {{-- Nama + Email --}}
                            <flux:table.cell>
                                <div class="font-medium text-sm">{{ $siswa->nama_lengkap }}</div>
                                <div class="text-xs text-zinc-400">{{ $siswa->email }}</div>
                            </flux:table.cell>

                            {{-- Program --}}
                            <flux:table.cell class="text-sm text-zinc-600 dark:text-zinc-300">
                                {{ $siswa->pendaftaran->program->nama_program ?? '-' }}
                            </flux:table.cell>

                            {{-- Tanggal Bergabung --}}
                            <flux:table.cell class="text-sm text-zinc-500">
                                {{ $siswa->tanggal_bergabung
                                    ? \Carbon\Carbon::parse($siswa->tanggal_bergabung)->translatedFormat('d M Y')
                                    : '-' }}
                            </flux:table.cell>

                            {{-- Status Akun --}}
                            <flux:table.cell>
                                <flux:badge
                                    color="{{ $siswa->status_akun === 'aktif' ? 'green' : 'red' }}"
                                    size="sm"
                                >
                                    {{ ucfirst($siswa->status_akun) }}
                                </flux:badge>
                            </flux:table.cell>

                            {{-- Aksi --}}
                            <flux:table.cell class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <flux:button
                                        href="{{ route('admin.siswa.show', $siswa->id) }}"
                                        size="sm"
                                        variant="ghost"
                                        icon="eye"
                                    >
                                        Detail
                                    </flux:button>
                                    <flux:button
                                        href="{{ route('admin.siswa.edit', $siswa->id) }}"
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil"
                                    >
                                        Edit
                                    </flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            {{-- Pagination --}}
            @if($siswaList->hasPages())
                <div class="px-5 py-4 border-t border-zinc-100 dark:border-zinc-700">
                    {{ $siswaList->links() }}
                </div>
            @endif
        @endif
    </div>

</x-layouts::app>
