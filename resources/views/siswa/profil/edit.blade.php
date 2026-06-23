<x-layouts::app :title="'Edit Profil'">

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-sm text-zinc-400">
        <a href="{{ route('siswa.profil.show') }}" class="hover:text-zinc-600 transition">Profil Saya</a>
        <span>/</span>
        <span class="text-zinc-600 dark:text-zinc-300">Edit</span>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                <flux:icon name="pencil-square" class="size-4 text-zinc-400" />
                <flux:heading size="sm" class="font-semibold">Edit Data Diri</flux:heading>
            </div>

            <form action="{{ route('siswa.profil.update') }}" method="POST" class="px-5 py-5 space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama Lengkap --}}
                <div>
                    <flux:label for="nama_lengkap">Nama Lengkap <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        id="nama_lengkap"
                        name="nama_lengkap"
                        type="text"
                        value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}"
                        class="mt-1 {{ $errors->has('nama_lengkap') ? 'border-red-400' : '' }}"
                        autofocus
                    />
                    @error('nama_lengkap')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No. HP --}}
                <div>
                    <flux:label for="no_hp">No. HP</flux:label>
                    <flux:input
                        id="no_hp"
                        name="no_hp"
                        type="text"
                        value="{{ old('no_hp', $siswa->no_hp) }}"
                        class="mt-1 {{ $errors->has('no_hp') ? 'border-red-400' : '' }}"
                        placeholder="Contoh: 08123456789"
                    />
                    @error('no_hp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Asal Sekolah --}}
                <div>
                    <flux:label for="asal_sekolah">Asal Sekolah / Instansi</flux:label>
                    <flux:input
                        id="asal_sekolah"
                        name="asal_sekolah"
                        type="text"
                        value="{{ old('asal_sekolah', $siswa->asal_sekolah) }}"
                        class="mt-1 {{ $errors->has('asal_sekolah') ? 'border-red-400' : '' }}"
                    />
                    @error('asal_sekolah')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info: email & username tidak bisa diubah --}}
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg px-4 py-3 text-xs text-zinc-500 space-y-1">
                    <div class="flex items-center gap-1">
                        <flux:icon name="lock-closed" class="size-3.5 text-zinc-400" />
                        <span>Email (<strong class="text-zinc-600 dark:text-zinc-300">{{ $siswa->email }}</strong>) dan username tidak dapat diubah sendiri.</span>
                    </div>
                    <div>Hubungi admin jika perlu perubahan data tersebut.</div>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-3 pt-2">
                    <flux:button href="{{ route('siswa.profil.show') }}" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="check">
                        Simpan Perubahan
                    </flux:button>
                </div>
            </form>
        </div>
    </div>

</x-layouts::app>
