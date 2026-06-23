<x-layouts.app :title="'Daftar Pendaftaran'">

    {{-- ===== HEADER ===== --}}
    <div class="mb-6">
        <flux:heading size="xl" class="font-bold">Pendaftaran Masuk</flux:heading>
        <flux:text class="text-zinc-500 mt-1">Kelola dan verifikasi pendaftaran calon peserta.</flux:text>
    </div>

    {{-- ===== STATISTIK (PBI-060) ===== --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        @php
            $statItems = [
                ['label' => 'Total',    'value' => $stats['total'],     'color' => 'text-zinc-700'],
                ['label' => 'Pending',  'value' => $stats['pending'],   'color' => 'text-yellow-600'],
                ['label' => 'Disetujui','value' => $stats['disetujui'], 'color' => 'text-green-600'],
                ['label' => 'Revisi',   'value' => $stats['revisi'],    'color' => 'text-blue-600'],
                ['label' => 'Ditolak',  'value' => $stats['ditolak'],   'color' => 'text-red-600'],
            ];
        @endphp

        @foreach($statItems as $s)
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl px-4 py-4 text-center shadow-sm">
                <div class="text-2xl font-bold {{ $s['color'] }}">{{ $s['value'] }}</div>
                <div class="text-xs text-zinc-500 mt-1">{{ $s['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- ===== FILTER & SEARCH (PBI-061) ===== --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl px-5 py-4 mb-5 shadow-sm">
        <form method="GET" action="{{ route('admin.pendaftaran.index') }}">
            <div class="flex flex-col sm:flex-row gap-3">

                {{-- Search nama --}}
                <div class="flex-1">
                    <flux:input
                        name="search"
                        placeholder="Cari nama atau email..."
                        value="{{ $search }}"
                        icon="magnifying-glass"
                    />
                </div>

                {{-- Filter program --}}
                <div class="w-full sm:w-52">
                    <flux:select name="program" placeholder="Semua Program">
                        <option value="">Semua Program</option>
                        @foreach($programList as $prog)
                            <option value="{{ $prog->id }}" {{ $program === $prog->id ? 'selected' : '' }}>
                                {{ $prog->nama_program }}
                            </option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Filter status --}}
                <div class="w-full sm:w-44">
                    <flux:select name="status" placeholder="Semua Status">
                        <option value="">Semua Status</option>
                        <option value="pending"   {{ $status === 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="revisi"    {{ $status === 'revisi'    ? 'selected' : '' }}>Perlu Revisi</option>
                        <option value="ditolak"   {{ $status === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                    </flux:select>
                </div>

                <flux:button type="submit" variant="primary" icon="funnel">Filter</flux:button>

                @if($search || $program || $status)
                    <flux:button href="{{ route('admin.pendaftaran.index') }}" icon="x-mark">Reset</flux:button>
                @endif
            </div>
        </form>
    </div>

    {{-- ===== TABEL DAFTAR PENDAFTARAN (PBI-060) ===== --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">

        @if($pendaftaranList->isEmpty())
            <div class="text-center py-16 text-zinc-400">
                <flux:icon name="inbox" class="mx-auto mb-3 size-10" />
                <p>Tidak ada data pendaftaran ditemukan.</p>
            </div>
        @else
            <flux:table>
                <flux:columns>
                    <flux:column class="w-8">#</flux:column>
                    <flux:column>Calon Peserta</flux:column>
                    <flux:column>Program</flux:column>
                    <flux:column>Tgl. Daftar</flux:column>
                    <flux:column>Dokumen</flux:column>
                    <flux:column>Status</flux:column>
                    <flux:column class="text-center">Aksi</flux:column>
                </flux:columns>

                <flux:rows>
                    @foreach($pendaftaranList as $i => $item)
                        <flux:row>
                            <flux:cell class="text-zinc-400 text-sm">
                                {{ $pendaftaranList->firstItem() + $i }}
                            </flux:cell>

                            <flux:cell>
                                <div class="font-medium text-sm">
                                    {{ $item->calonPeserta->nama_lengkap ?? '-' }}
                                </div>
                                <div class="text-xs text-zinc-400">
                                    {{ $item->calonPeserta->email ?? '-' }}
                                </div>
                            </flux:cell>

                            <flux:cell class="text-sm">
                                {{ $item->program->nama_program ?? '-' }}
                            </flux:cell>

                            <flux:cell class="text-sm text-zinc-500">
                                {{ \Carbon\Carbon::parse($item->tanggal_daftar)->translatedFormat('d M Y') }}
                            </flux:cell>

                            <flux:cell class="text-sm text-zinc-500">
                                {{ $item->dokumenPendaftaran->count() }} file
                            </flux:cell>

                            <flux:cell>
                                @php
                                    $badge = match($item->status) {
                                        'disetujui' => 'green',
                                        'revisi'    => 'blue',
                                        'ditolak'   => 'red',
                                        default     => 'yellow',
                                    };
                                    $label = match($item->status) {
                                        'pending'   => 'Pending',
                                        'disetujui' => 'Disetujui',
                                        'revisi'    => 'Perlu Revisi',
                                        'ditolak'   => 'Ditolak',
                                        default     => $item->status,
                                    };
                                @endphp
                                <flux:badge color="{{ $badge }}" size="sm">{{ $label }}</flux:badge>
                            </flux:cell>

                            <flux:cell class="text-center">
                                <flux:button
                                    href="{{ route('admin.pendaftaran.show', $item->id) }}"
                                    size="sm"
                                    variant="ghost"
                                    icon="eye"
                                >
                                    Review
                                </flux:button>
                            </flux:cell>
                        </flux:row>
                    @endforeach
                </flux:rows>
            </flux:table>

            {{-- Pagination --}}
            @if($pendaftaranList->hasPages())
                <div class="px-5 py-4 border-t border-zinc-100 dark:border-zinc-700">
                    {{ $pendaftaranList->links() }}
                </div>
            @endif
        @endif
    </div>

</x-layouts.app>
