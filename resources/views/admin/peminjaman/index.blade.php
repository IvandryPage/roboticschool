<x-layouts::app :title="__('Persetujuan Peminjaman')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Kelola Peminjaman Aset Robotik</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Persetujuan pengajuan pinjam baru, penolakan permohonan, dan konfirmasi pengembalian aset oleh siswa dan instruktur.
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

        <!-- Peminjaman List Table -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
                    <thead>
                        <tr class="text-left text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                            <th class="py-3 px-4">Peminjam</th>
                            <th class="py-3 px-4">Kode Aset</th>
                            <th class="py-3 px-4">Aset Robotik</th>
                            <th class="py-3 px-4">Serial Number</th>
                            <th class="py-3 px-4">Batas Kembali</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Kondisi Awal / Akhir</th>
                            <th class="py-3 px-4">Verifikator</th>
                            <th class="py-3 px-4 text-center">Aksi / Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800 text-sm text-neutral-700 dark:text-neutral-300">
                        @forelse ($peminjamans as $borrow)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-950/50 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-neutral-900 dark:text-white">{{ $borrow->borrower->nama_lengkap ?? 'N/A' }}</div>
                                    <div class="text-xs text-neutral-400 font-mono">{{ $borrow->borrower->email ?? '' }}</div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs font-semibold">{{ $borrow->itemKit->aset->kode_aset ?? 'N/A' }}</td>
                                <td class="py-3.5 px-4 font-medium">{{ $borrow->itemKit->aset->nama_kit ?? 'N/A' }}</td>
                                <td class="py-3.5 px-4 font-mono text-xs text-neutral-500">{{ $borrow->itemKit->serial_number ?? 'N/A' }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="text-xs text-neutral-400">Jatuh Tempo:</div>
                                    <div class="font-semibold">{{ $borrow->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($borrow->tanggal_jatuh_tempo)->format('d M Y') : '-' }}</div>
                                    @if($borrow->tanggal_kembali)
                                        <div class="text-xs text-neutral-400 mt-1">Kembali: {{ \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $borrow->status === 'Dipinjam' ? 'bg-blue-100 text-blue-800 dark:bg-blue-950/30 dark:text-blue-400' : ($borrow->status === 'Diajukan' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-950/30 dark:text-yellow-400' : ($borrow->status === 'Dikembalikan' ? 'bg-green-100 text-green-800 dark:bg-green-950/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-950/30 dark:text-red-400')) }}">
                                        {{ $borrow->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    <div>Awal: <span class="font-semibold text-neutral-800 dark:text-neutral-200">{{ $borrow->kondisi_awal }}</span></div>
                                    <div class="mt-1">Akhir: <span class="font-semibold text-neutral-800 dark:text-neutral-200">{{ $borrow->kondisi_akhir ?? '-' }}</span></div>
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    {{ $borrow->verifikator->nama_lengkap ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($borrow->status === 'Diajukan')
                                            <!-- Approve Button -->
                                            <form action="{{ route('admin.peminjaman.approve', $borrow) }}" method="POST" class="inline">
                                                @csrf
                                                <flux:button type="submit" variant="primary" size="sm" icon="check">
                                                    Setujui
                                                </flux:button>
                                            </form>

                                            <!-- Reject Button -->
                                            <form action="{{ route('admin.peminjaman.reject', $borrow) }}" method="POST" class="inline">
                                                @csrf
                                                <flux:button type="submit" variant="danger" size="sm" icon="x-mark">
                                                    Tolak
                                                </flux:button>
                                            </form>
                                        @elseif ($borrow->status === 'Dipinjam')
                                            <!-- Confirm Return Form -->
                                            <form action="{{ route('admin.peminjaman.return', $borrow) }}" method="POST" class="flex items-center gap-1.5">
                                                @csrf
                                                <select name="kondisi_akhir" required class="block rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-950 text-neutral-900 dark:text-white text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1 border">
                                                    <option value="Baik" selected>Baik</option>
                                                    <option value="Rusak">Rusak</option>
                                                    <option value="Hilang">Hilang</option>
                                                </select>
                                                <flux:button type="submit" variant="filled" size="sm" class="bg-blue-600 hover:bg-blue-750 text-white border-0">
                                                    Kembalikan
                                                </flux:button>
                                            </form>
                                        @else
                                            <span class="text-xs text-neutral-400 italic">Selesai</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    Belum ada pengajuan peminjaman aset saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
