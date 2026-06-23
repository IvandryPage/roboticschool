<x-layouts::app :title="'Edit Profil Siswa'">

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-sm text-zinc-400">
        <a href="{{ route('admin.siswa.index') }}" class="hover:text-zinc-600 transition">Siswa Aktif</a>
        <span>/</span>
        <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="hover:text-zinc-600 transition">
            {{ $siswa->nama_lengkap }}
        </a>
        <span>/</span>
        <span class="text-zinc-600 dark:text-zinc-300">Edit</span>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                <flux:icon name="pencil-square" class="size-4 text-zinc-400" />
                <flux:heading size="sm" class="font-semibold">Edit Profil Siswa</flux:heading>
            </div>

            <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" class="px-5 py-5 space-y-5">
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

                {{-- Email --}}
                <div>
                    <flux:label for="email">Email <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $siswa->email) }}"
                        class="mt-1 {{ $errors->has('email') ? 'border-red-400' : '' }}"
                    />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-zinc-400 mt-1">Perubahan email juga akan memperbarui email login siswa.</p>
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

                {{-- Status Akun --}}
                <div>
                    <flux:label for="status_akun">Status Akun <span class="text-red-500">*</span></flux:label>
                    <flux:select
                        id="status_akun"
                        name="status_akun"
                        class="mt-1 {{ $errors->has('status_akun') ? 'border-red-400' : '' }}"
                    >
                        <option value="aktif"    {{ old('status_akun', $siswa->status_akun) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status_akun', $siswa->status_akun) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </flux:select>
                    @error('status_akun')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-zinc-100 dark:border-zinc-700" />

                {{-- Ganti Password (opsional) --}}
                <div>
                    <flux:heading size="sm" class="font-semibold mb-3">Ganti Password</flux:heading>
                    <p class="text-xs text-zinc-400 mb-4">Kosongkan jika tidak ingin mengganti password siswa.</p>

                    <div class="space-y-4">
                        <div>
                            <flux:label for="password">Password Baru</flux:label>
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

                        <div>
                            <flux:label for="password_confirmation">Konfirmasi Password Baru</flux:label>
                            <flux:input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                placeholder="Ulangi password baru"
                                class="mt-1"
                            />
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-3 pt-2">
                    <flux:button href="{{ route('admin.siswa.show', $siswa->id) }}" variant="ghost">
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
