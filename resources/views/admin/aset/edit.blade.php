<x-layouts::app :title="__('Edit Aset & Stok')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6 max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Edit Aset & Stok</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Perbarui informasi detail aset dan kelola stok unit kit robotik.
            </p>
        </div>

        <!-- Success/Error Alerts -->
        @if (session('success'))
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-950/30 dark:text-green-400 border border-green-200 dark:border-green-800/30">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-950/30 dark:text-red-400 border border-red-200 dark:border-red-800/30">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card 1: Detail Aset -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Informasi Detail Aset</h2>
            <form action="{{ route('admin.aset.update', $aset) }}" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400">
                        Kode Aset (Sistem)
                    </label>
                    <div class="mt-1 font-mono text-sm text-neutral-900 dark:text-white bg-neutral-50 dark:bg-neutral-950 p-2.5 rounded border border-neutral-200 dark:border-neutral-800">
                        {{ $aset->kode_aset }}
                    </div>
                </div>

                <div>
                    <label for="nama_kit" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Nama Kit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kit" id="nama_kit" required value="{{ old('nama_kit', $aset->nama_kit) }}" class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">{{ old('deskripsi', $aset->deskripsi) }}</textarea>
                </div>

                <div class="mt-4 flex items-center justify-end gap-3 border-t border-neutral-100 dark:border-neutral-850 pt-4">
                    <flux:button href="{{ route('admin.aset.index') }}" variant="ghost">
                        Kembali
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Perbarui Informasi
                    </flux:button>
                </div>
            </form>
        </div>

        <!-- Card 2: Tambah Stok -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Tambah Stok Baru</h2>
            <form action="{{ route('admin.aset.item-kit.store', $aset) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                @csrf
                <div>
                    <label for="jumlah_stok" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Jumlah Stok Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah_stok" id="jumlah_stok" required value="1" min="1" max="50" class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                </div>

                <div>
                    <label for="status_kondisi" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Kondisi <span class="text-red-500">*</span>
                    </label>
                    <select name="status_kondisi" id="status_kondisi" required class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                        <option value="Bagus" selected>Bagus</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Perbaikan">Perbaikan</option>
                    </select>
                </div>

                <div>
                    <label for="lokasi_rak" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Lokasi Rak
                    </label>
                    <input type="text" name="lokasi_rak" id="lokasi_rak" placeholder="Contoh: RAK-A1" class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                </div>

                <div class="md:col-span-3 flex justify-end mt-4">
                    <flux:button type="submit" variant="primary" icon="plus">
                        Tambah Item Stok
                    </flux:button>
                </div>
            </form>
        </div>

        <!-- Card 3: Daftar Item Kit -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Daftar Item Kit (Stok)</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
                    <thead>
                        <tr class="text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider font-mono">
                            <th class="py-3 px-3">Serial Number</th>
                            <th class="py-3 px-3">Kondisi (Ubah Inline)</th>
                            <th class="py-3 px-3">Lokasi Rak</th>
                            <th class="py-3 px-3">Status Pinjam</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800 text-sm text-neutral-700 dark:text-neutral-300">
                        @forelse ($aset->itemKits as $item)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-950/50">
                                <td class="py-3 px-3 font-mono text-xs font-semibold">{{ $item->serial_number }}</td>
                                <td class="py-3 px-3">
                                    <form action="{{ route('admin.item-kit.update-condition', $item) }}" method="POST" class="inline-block">
                                        @csrf
                                        <select name="status_kondisi" onchange="this.form.submit()" class="block rounded border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1 font-medium">
                                            <option value="Bagus" {{ $item->status_kondisi === 'Bagus' ? 'selected' : '' }}>Bagus</option>
                                            <option value="Rusak" {{ $item->status_kondisi === 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                            <option value="Perbaikan" {{ $item->status_kondisi === 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3 px-3 font-mono text-xs">{{ $item->lokasi_rak ?? '-' }}</td>
                                <td class="py-3 px-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $item->is_available ? 'bg-green-100 text-green-800 dark:bg-green-950/30' : 'bg-neutral-100 text-neutral-500 dark:bg-neutral-800' }}">
                                        {{ $item->is_available ? 'Tersedia' : 'Dipinjam/Rusak' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <form action="{{ route('admin.item-kit.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item kit ini dari stok?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="danger" size="sm" icon="trash">
                                            Hapus
                                        </flux:button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    Aset ini belum memiliki item kit (Stok kosong).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
