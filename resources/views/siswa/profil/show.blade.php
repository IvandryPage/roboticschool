<x-layouts.app :title="'Profil Saya'">

    {{-- ===== HEADER ===== --}}
    <div class="mb-6">
        <flux:heading size="xl" class="font-bold">Profil Saya</flux:heading>
        <flux:text class="text-zinc-500 mt-1">Informasi akun dan data diri kamu.</flux:text>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-5">
            {{ session('success') }}
        </flux:callout>
    @endif

    {{-- PBI-072: Peringatan jika akun nonaktif (edge case) --}}
    @if($siswa->status_akun === 'nonaktif')
        <flux:callout variant="warning" icon="exclamation-triangle" class="mb-5">
            Akun kamu sedang <strong>nonaktif</strong>. Hubungi admin untuk informasi lebih lanjut.
        </flux:callout>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Kolom Kiri: Kartu profil ringkas --}}
        <div class="space-y-4">

            {{-- Avatar & Nama --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-6 flex flex-col items-center text-center">
                    <div class="size-20 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mb-3">
                        <flux:icon name="user" class="size-10 text-indigo-500" />
                    </div>
                    <div class="font-semibold text-lg text-zinc-800 dark:text-zinc-100">
                        {{ $siswa->nama_lengkap }}
                    </div>
                    <div class="text-sm text-zinc-400 mt-0.5">{{ $user->name }}</div>
                    <div class="mt-2">
                        <flux:badge
                            color="{{ $siswa->status_akun === 'aktif' ? 'green' : 'red' }}"
                            size="sm"
                        >
                            {{ ucfirst($siswa->status_akun) }}
                        </flux:badge>
                    </div>
                </div>
                <div class="px-5 pb-5 space-y-3 text-sm border-t border-zinc-100 dark:border-zinc-700 pt-4">
                    <div class="flex items-center gap-2 text-zinc-500">
                        <flux:icon name="envelope" class="size-4 shrink-0" />
                        <span class="truncate">{{ $siswa->email }}</span>
                    </div>
                    @if($siswa->no_hp)
                        <div class="flex items-center gap-2 text-zinc-500">
                            <flux:icon name="phone" class="size-4 shrink-0" />
                            <span>{{ $siswa->no_hp }}</span>
                        </div>
                    @endif
                    @if($siswa->asal_sekolah)
                        <div class="flex items-center gap-2 text-zinc-500">
                            <flux:icon name="building-library" class="size-4 shrink-0" />
                            <span>{{ $siswa->asal_sekolah }}</span>
                        </div>
                    @endif
                    <div class="flex items-center gap-2 text-zinc-500">
                        <flux:icon name="calendar" class="size-4 shrink-0" />
                        <span>Bergabung {{ $siswa->tanggal_bergabung
                            ? \Carbon\Carbon::parse($siswa->tanggal_bergabung)->translatedFormat('d M Y')
                            : '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Tombol aksi --}}
            <flux:button
                href="{{ route('siswa.profil.edit') }}"
                variant="primary"
                icon="pencil"
                class="w-full"
            >
                Edit Profil
            </flux:button>

            <flux:button
                href="{{ route('siswa.profil.ganti-password') }}"
                variant="ghost"
                icon="lock-closed"
                class="w-full"
            >
                Ganti Password
            </flux:button>
        </div>

        {{-- Kolom Kanan: Detail profil & info program --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Data Diri --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <flux:icon name="identification" class="size-4 text-zinc-400" />
                        <flux:heading size="sm" class="font-semibold">Data Diri</flux:heading>
                    </div>
                    <flux:button href="{{ route('siswa.profil.edit') }}" size="sm" variant="ghost" icon="pencil">
                        Edit
                    </flux:button>
                </div>
                <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    @php
                        $rows = [
                            'Nama Lengkap'         => $siswa->nama_lengkap,
                            'Email'                => $siswa->email,
                            'No. HP'               => $siswa->no_hp ?? '-',
                            'Asal Sekolah/Instansi'=> $siswa->asal_sekolah ?? '-',
                        ];
                    @endphp
                    @foreach($rows as $label => $value)
                        <div>
                            <div class="text-zinc-400 text-xs mb-0.5">{{ $label }}</div>
                            <div class="font-medium text-zinc-700 dark:text-zinc-200">{{ $value }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Info Akun Login --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <flux:icon name="key" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Akun Login</flux:heading>
                </div>
                <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Username</div>
                        <div class="font-mono font-medium text-zinc-700 dark:text-zinc-200">{{ $user->name }}</div>
                        <div class="text-xs text-zinc-400 mt-1">Username tidak dapat diubah sendiri. Hubungi admin jika perlu perubahan.</div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Email Login</div>
                        <div class="font-medium text-zinc-700 dark:text-zinc-200">{{ $user->email }}</div>
                        <div class="text-xs text-zinc-400 mt-1">Email tidak dapat diubah sendiri.</div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Password</div>
                        <div class="flex items-center gap-2">
                            <span class="text-zinc-400">••••••••</span>
                            <flux:button
                                href="{{ route('siswa.profil.ganti-password') }}"
                                size="sm"
                                variant="ghost"
                                icon="lock-closed"
                            >
                                Ganti
                            </flux:button>
                        </div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Akun Dibuat</div>
                        <div class="font-medium text-zinc-700 dark:text-zinc-200">
                            {{ $siswa->created_at->translatedFormat('d F Y') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Program --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <flux:icon name="academic-cap" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Program yang Diikuti</flux:heading>
                </div>
                <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Nama Program</div>
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
                        <div class="text-zinc-400 text-xs mb-0.5">No. Referensi</div>
                        <div class="font-mono font-medium text-zinc-700 dark:text-zinc-200">
                            {{ $siswa->pendaftaran->no_referensi ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
