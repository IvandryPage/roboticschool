<x-layouts::app :title="__('Kelola Aset')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Kelola Master Aset Robotik</h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Daftar seluruh kit robotik beserta status ketersediaan dan jumlah stok barang.
                </p>
            </div>
            <div>
                <flux:button href="{{ route('admin.aset.create') }}" variant="primary" icon="plus">
                    Tambah Aset Baru
                </flux:button>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-950/30 dark:text-green-400 border border-green-200 dark:border-green-800/30">
                {{ session('success') }}
            </div>
        @endif

        <!-- Assets List Card -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
                    <thead>
                        <tr class="text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            <th class="py-3 px-4">Kode Aset</th>
                            <th class="py-3 px-4">Nama Kit</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4 text-center">Stok Minimal</th>
                            <th class="py-3 px-4 text-center">Total Item (Stok)</th>
                            <th class="py-3 px-4 text-center">Tersedia</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800 text-sm text-neutral-700 dark:text-neutral-300">
                        @forelse ($assets as $asset)
                            @php 
                                $avail = $asset->available_stock;
                                $min = $asset->stok_minimal;
                            @endphp
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-950/50 transition-colors">
                                <td class="py-3 px-4 font-mono text-xs">{{ $asset->kode_aset }}</td>
                                <td class="py-3 px-4 font-medium">{{ $asset->nama_kit }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center rounded-full bg-neutral-100 px-2 py-0.5 text-xs font-medium text-neutral-800 dark:bg-neutral-800 dark:text-neutral-300">
                                        {{ $asset->kategori ?? 'Lainnya' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center font-mono">{{ $min }}</td>
                                <td class="py-3 px-4 text-center font-mono">{{ $asset->total_stock }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $avail > 0 ? ($avail < $min ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-950/50 dark:text-yellow-400' : 'bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-400') : 'bg-red-100 text-red-800 dark:bg-red-950/50 dark:text-red-400' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $avail > 0 ? ($avail < $min ? 'bg-yellow-500' : 'bg-green-600 dark:bg-green-400') : 'bg-red-600 dark:bg-red-400' }}"></span>
                                        {{ $avail }} Unit
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <flux:button href="{{ route('admin.aset.edit', $asset) }}" variant="ghost" size="sm" icon="pencil">
                                            Edit
                                        </flux:button>
                                        <form action="{{ route('admin.aset.destroy', $asset) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini beserta seluruh item kit di dalamnya?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button type="submit" variant="danger" size="sm" icon="trash">
                                                Hapus
                                            </flux:button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    Belum ada data master aset yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
