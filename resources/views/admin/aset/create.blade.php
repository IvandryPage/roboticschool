<x-layouts::app :title="__('Tambah Aset')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Tambah Aset Baru</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Masukkan data detail untuk mendaftarkan master aset robotik baru.
            </p>
        </div>

        <!-- Errors Alert -->
        @if ($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-950/30 dark:text-red-400 border border-red-200 dark:border-red-800/30">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <form action="{{ route('admin.aset.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label for="nama_kit" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Nama Kit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kit" id="nama_kit" required value="{{ old('nama_kit') }}" placeholder="Contoh: Arduino Starter Kit" class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="jumlah_stok" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                            Jumlah Stok <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah_stok" id="jumlah_stok" required value="{{ old('jumlah_stok', 1) }}" min="0" max="100" class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    </div>

                    <div>
                        <label for="kondisi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                            Kondisi Awal <span class="text-red-500">*</span>
                        </label>
                        <select name="kondisi" id="kondisi" required class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                            <option value="Bagus" {{ old('kondisi') === 'Bagus' ? 'selected' : '' }}>Bagus</option>
                            <option value="Rusak" {{ old('kondisi') === 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            <option value="Perbaikan" {{ old('kondisi') === 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Masukkan deskripsi singkat tentang kit robotik ini..." class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mt-4 flex items-center justify-end gap-3 border-t border-neutral-100 dark:border-neutral-850 pt-4">
                    <flux:button href="{{ route('admin.aset.index') }}" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Simpan
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
