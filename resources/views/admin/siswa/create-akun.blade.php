<x-layouts.app :title="'Buat Akun Siswa'">

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-sm text-zinc-400">
        <a href="{{ route('admin.pendaftaran.index') }}" class="hover:text-zinc-600 transition">Pendaftaran</a>
        <span>/</span>
        <a href="{{ route('admin.pendaftaran.show', $pendaftaran->id) }}" class="hover:text-zinc-600 transition">
            Review #{{ substr($pendaftaran->id, 0, 8) }}
        </a>
        <span>/</span>
        <span class="text-zinc-600 dark:text-zinc-300">Buat Akun Siswa</span>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-5">
            {{ session('success') }}
        </flux:callout>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- Info Pendaftaran (kiri) --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <flux:icon name="user" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Data Calon Peserta</flux:heading>
                </div>
                <div class="px-5 py-4 space-y-3 text-sm">
                    @php
                        $cp = $pendaftaran->calonPeserta;
                        $rows = [
                            'Nama Lengkap' => $cp->nama_lengkap ?? '-',
                            'Email'        => $cp->email ?? '-',
                            'No. HP'       => $cp->no_hp ?? '-',
                            'Program'      => $pendaftaran->program->nama_program ?? '-',
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

            <div class="bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800 rounded-xl px-5 py-4 text-sm text-green-700 dark:text-green-400">
                <flux:icon name="check-circle" class="size-4 inline-block mr-1 -mt-0.5" />
                Pendaftaran ini telah <strong>disetujui</strong>. Silakan buat akun login untuk siswa.
            </div>
        </div>

        {{-- Form Buat Akun (kanan) --}}
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <flux:icon name="key" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Buat Akun Login Siswa</flux:heading>
                </div>

                <form action="{{ route('admin.siswa.store-akun', $pendaftaran->id) }}" method="POST" class="px-5 py-5 space-y-5">
                    @csrf

                    {{-- Username --}}
                    <div>
                        <flux:label for="username">Username <span class="text-red-500">*</span></flux:label>
                        <flux:input
                            id="username"
                            name="username"
                            type="text"
                            value="{{ old('username') }}"
                            placeholder="Contoh: budi.santoso"
                            class="mt-1 @error('username') border-red-400 @enderror"
                            autofocus
                        />
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-zinc-400 mt-1">Minimal 4 karakter, tidak boleh sama dengan username lain.</p>
                    </div>

                    {{-- Password --}}
                    <div>
                        <flux:label for="password">Password Awal <span class="text-red-500">*</span></flux:label>
                        <flux:input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Minimal 8 karakter"
                            class="mt-1 @error('password') border-red-400 @enderror"
                        />
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <flux:label for="password_confirmation">Konfirmasi Password <span class="text-red-500">*</span></flux:label>
                        <flux:input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="Ulangi password"
                            class="mt-1"
                        />
                    </div>

                    <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg px-4 py-3 text-xs text-zinc-500">
                        <flux:icon name="information-circle" class="size-3.5 inline-block mr-1 -mt-0.5 text-zinc-400" />
                        Email akun akan menggunakan email calon peserta:
                        <strong class="text-zinc-700 dark:text-zinc-300">{{ $pendaftaran->calonPeserta->email }}</strong>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <flux:button href="{{ route('admin.pendaftaran.show', $pendaftaran->id) }}" variant="ghost">
                            Batal
                        </flux:button>
                        <flux:button
                            type="submit"
                            variant="primary"
                            icon="user-plus"
                            onclick="return confirm('Yakin ingin membuat akun siswa ini?')"
                        >
                            Buat Akun Siswa
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.app>
