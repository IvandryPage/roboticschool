<x-layouts::app :title="'Ganti Password'">

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-sm text-zinc-400">
        <a href="{{ route('siswa.profil.show') }}" class="hover:text-zinc-600 transition">Profil Saya</a>
        <span>/</span>
        <span class="text-zinc-600 dark:text-zinc-300">Ganti Password</span>
    </div>

    <div class="max-w-lg">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                <flux:icon name="lock-closed" class="size-4 text-zinc-400" />
                <flux:heading size="sm" class="font-semibold">Ganti Password</flux:heading>
            </div>

            <form action="{{ route('siswa.profil.update-password') }}" method="POST" class="px-5 py-5 space-y-5">
                @csrf
                @method('PUT')

                {{-- Password Lama --}}
                <div>
                    <flux:label for="password_lama">Password Saat Ini <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        id="password_lama"
                        name="password_lama"
                        type="password"
                        placeholder="Masukkan password saat ini"
                        class="mt-1 {{ $errors->has('password_lama') ? 'border-red-400' : '' }}"
                        autofocus
                    />
                    @error('password_lama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-zinc-100 dark:border-zinc-700" />

                {{-- Password Baru --}}
                <div>
                    <flux:label for="password">Password Baru <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Minimal 8 karakter"
                        class="mt-1 {{ $errors->has('password') ? 'border-red-400' : '' }}"
                    />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <flux:label for="password_confirmation">Konfirmasi Password Baru <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        placeholder="Ulangi password baru"
                        class="mt-1"
                    />
                </div>

                {{-- Tips keamanan --}}
                <div class="bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800 rounded-lg px-4 py-3 text-xs text-blue-700 dark:text-blue-400">
                    <flux:icon name="information-circle" class="size-3.5 inline-block mr-1 -mt-0.5" />
                    Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk keamanan yang lebih baik.
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-3 pt-2">
                    <flux:button href="{{ route('siswa.profil.show') }}" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="lock-closed">
                        Simpan Password
                    </flux:button>
                </div>
            </form>
        </div>
    </div>

</x-layouts::app>
