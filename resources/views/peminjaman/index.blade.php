<x-layouts::app :title="__('Peminjaman Aset')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Peminjaman Aset Robotik</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Ajukan peminjaman kit robotik untuk keperluan kelas, penugasan, atau proyek pembelajaran.
            </p>
        </div>

        <!-- Alerts -->
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

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            
            <!-- Left 2 Columns: Assets and History -->
            <div class="lg:col-span-2 flex flex-col gap-6">
                <!-- Available Kits Catalog -->
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Katalog Kit Robotik</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse ($assets as $asset)
                            @php $avail = $asset->available_stock; @endphp
                            <div class="relative flex flex-col justify-between p-4 rounded-lg border border-neutral-100 dark:border-neutral-800 bg-neutral-50/50 dark:bg-neutral-950/50">
                                <div>
                                    <div class="flex justify-between items-start gap-2">
                                        <span class="inline-flex items-center rounded-full bg-neutral-100 px-2 py-0.5 text-xs font-medium text-neutral-800 dark:bg-neutral-800 dark:text-neutral-300">
                                            {{ $asset->kategori ?? 'Lainnya' }}
                                        </span>
                                        <span class="text-xs font-mono text-neutral-400">{{ $asset->kode_aset }}</span>
                                    </div>
                                    <h3 class="mt-2 text-base font-semibold text-neutral-900 dark:text-white">{{ $asset->nama_kit }}</h3>
                                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400 line-clamp-2">
                                        {{ $asset->deskripsi ?? 'Tidak ada deskripsi.' }}
                                    </p>
                                </div>
                                <div class="mt-4 flex items-center justify-between border-t border-neutral-100 dark:border-neutral-800 pt-3">
                                    <span class="text-xs text-neutral-400">Min Stok: {{ $asset->stok_minimal }}</span>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $avail > 0 ? 'bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-950/50 dark:text-red-400' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $avail > 0 ? 'bg-green-600 dark:bg-green-400' : 'bg-red-600 dark:bg-red-400' }}"></span>
                                        {{ $avail }} Tersedia
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 col-span-2 py-6 text-center">
                                Tidak ada data kit robotik yang terdaftar.
                            </p>
                        @endforelse
                    </div>
                </div>

                <!-- Active Peminjaman List -->
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Peminjaman Aktif</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
                            <thead>
                                <tr class="text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                    <th class="py-3 px-4">Kode Aset</th>
                                    <th class="py-3 px-4">Nama Kit</th>
                                    <th class="py-3 px-4">Serial Number</th>
                                    <th class="py-3 px-4">Tgl Pinjam</th>
                                    <th class="py-3 px-4">Batas Kembali</th>
                                    <th class="py-3 px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800 text-sm text-neutral-700 dark:text-neutral-300">
                                @forelse ($activeBorrowings as $borrow)
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-950/50">
                                        <td class="py-3 px-4 font-mono text-xs font-semibold">{{ $borrow->itemKit->aset->kode_aset ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 font-medium">{{ $borrow->itemKit->aset->nama_kit ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 font-mono text-xs text-neutral-500">{{ $borrow->itemKit->serial_number }}</td>
                                        <td class="py-3 px-4">{{ $borrow->tanggal_pinjam ? \Carbon\Carbon::parse($borrow->tanggal_pinjam)->format('d M Y') : '-' }}</td>
                                        <td class="py-3 px-4 font-semibold">{{ $borrow->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($borrow->tanggal_jatuh_tempo)->format('d M Y') : '-' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $borrow->status === 'Dipinjam' ? 'bg-blue-100 text-blue-800 dark:bg-blue-950/30 dark:text-blue-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-950/30 dark:text-yellow-400' }}">
                                                {{ $borrow->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            Tidak ada peminjaman aktif saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- History Peminjaman List -->
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Riwayat Peminjaman</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
                            <thead>
                                <tr class="text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                    <th class="py-3 px-4">Kode Aset</th>
                                    <th class="py-3 px-4">Nama Kit</th>
                                    <th class="py-3 px-4">Serial Number</th>
                                    <th class="py-3 px-4">Tgl Kembali</th>
                                    <th class="py-3 px-4">Kondisi Akhir</th>
                                    <th class="py-3 px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800 text-sm text-neutral-700 dark:text-neutral-300">
                                @forelse ($historyBorrowings as $borrow)
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-950/50">
                                        <td class="py-3 px-4 font-mono text-xs font-semibold">{{ $borrow->itemKit->aset->kode_aset ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 font-medium">{{ $borrow->itemKit->aset->nama_kit ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 font-mono text-xs text-neutral-500">{{ $borrow->itemKit->serial_number }}</td>
                                        <td class="py-3 px-4">{{ $borrow->tanggal_kembali ? \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d M Y') : '-' }}</td>
                                        <td class="py-3 px-4">{{ $borrow->kondisi_akhir ?? '-' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $borrow->status === 'Dikembalikan' ? 'bg-green-100 text-green-800 dark:bg-green-950/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-950/30 dark:text-red-400' }}">
                                                {{ $borrow->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            Belum ada riwayat peminjaman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right 1 Column: Request Form -->
            <div class="flex flex-col gap-6">
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm sticky top-6">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Ajukan Peminjaman</h2>
                    <form action="{{ route('peminjaman.store') }}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <div>
                            <label for="aset_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                Pilih Kit Robotik
                            </label>
                            <select name="aset_id" id="aset_id" required class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                                <option value="" disabled selected>Pilih Aset...</option>
                                @foreach ($assets as $asset)
                                    @php $avail = $asset->available_stock; @endphp
                                    <option value="{{ $asset->id }}" {{ $avail == 0 ? 'disabled' : '' }}>
                                        [{{ $asset->kode_aset }}] {{ $asset->nama_kit }} ({{ $avail }} Tersedia)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_jatuh_tempo" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                Batas Tanggal Pengembalian
                            </label>
                            <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" required class="block w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-neutral-800 dark:bg-neutral-100 dark:text-black dark:hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer transition-colors">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
