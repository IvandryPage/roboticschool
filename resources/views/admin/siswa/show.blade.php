<x-layouts.app :title="'Detail Siswa'">

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-sm text-zinc-400">
        <a href="{{ route('admin.siswa.index') }}" class="hover:text-zinc-600 transition">Siswa Aktif</a>
        <span>/</span>
        <span class="text-zinc-600 dark:text-zinc-300">{{ $siswa->nama_lengkap }}</span>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-5">
            {{ session('success') }}
        </flux:callout>
    @endif
    @if(session('info'))
        <flux:callout variant="info" icon="information-circle" class="mb-5">
            {{ session('info') }}
        </flux:callout>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Profil (kiri) --}}
        <div class="space-y-4">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <flux:icon name="user-circle" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Profil Siswa</flux:heading>
                </div>
                <div class="px-5 py-4 space-y-4 text-sm">
                    @php
                        $rows = [
                            'Nama Lengkap'  => $siswa->nama_lengkap,
                            'Email'         => $siswa->email,
                            'No. HP'        => $siswa->no_hp ?? '-',
                            'Asal Sekolah'  => $siswa->asal_sekolah ?? '-',
                            'Username'      => $siswa->user->name ?? '-',
                        ];
                    @endphp
                    @foreach($rows as $label => $value)
                        <div>
                            <div class="text-zinc-400 text-xs mb-0.5">{{ $label }}</div>
                            <div class="font-medium text-zinc-700 dark:text-zinc-200">{{ $value }}</div>
                        </div>
                    @endforeach

                    <div>
                        <div class="text-zinc-400 text-xs mb-1">Status Akun</div>
                        <flux:badge
                            color="{{ $siswa->status_akun === 'aktif' ? 'green' : 'red' }}"
                            size="sm"
                        >
                            {{ ucfirst($siswa->status_akun) }}
                        </flux:badge>
                    </div>
                </div>
            </div>

            {{-- Tombol aksi --}}
            <flux:button
                href="{{ route('admin.siswa.edit', $siswa->id) }}"
                variant="primary"
                icon="pencil"
                class="w-full"
            >
                Edit Profil
            </flux:button>
            <flux:button
                href="{{ route('admin.siswa.index') }}"
                variant="ghost"
                icon="arrow-left"
                class="w-full"
            >
                Kembali ke Daftar
            </flux:button>
        </div>

        {{-- Info Pendaftaran (kanan) --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <flux:icon name="academic-cap" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Informasi Program</flux:heading>
                </div>
                <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Program Diikuti</div>
                        <div class="font-medium text-zinc-700 dark:text-zinc-200">
                            {{ $siswa->pendaftaran->program->nama_program ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Tanggal Bergabung</div>
                        <div class="font-medium text-zinc-700 dark:text-zinc-200">
                            {{ $siswa->tanggal_bergabung
                                ? \Carbon\Carbon::parse($siswa->tanggal_bergabung)->translatedFormat('d F Y')
                                : '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">No. Referensi Pendaftaran</div>
                        <div class="font-mono font-medium text-zinc-700 dark:text-zinc-200">
                            {{ $siswa->pendaftaran->no_referensi ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Akun Dibuat</div>
                        <div class="font-medium text-zinc-700 dark:text-zinc-200">
                            {{ $siswa->created_at->translatedFormat('d F Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
