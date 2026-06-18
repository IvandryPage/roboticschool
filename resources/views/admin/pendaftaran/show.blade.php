<x-layouts.app :title="'Review Pendaftaran'">

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-sm text-zinc-400">
        <a href="{{ route('admin.pendaftaran.index') }}" class="hover:text-zinc-600 transition">Pendaftaran</a>
        <span>/</span>
        <span class="text-zinc-600 dark:text-zinc-300">Review #{{ substr($pendaftaran->id, 0, 8) }}</span>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-5">
            {{ session('success') }}
        </flux:callout>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- ===== KOLOM KIRI: Data & Dokumen ===== --}}
        <div class="lg:col-span-3 space-y-4">

            {{-- Data Calon Peserta --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <flux:icon name="user" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Data Calon Peserta</flux:heading>
                </div>
                <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                    @php
                        $cp = $pendaftaran->calonPeserta;
                        $rows = [
                            'Nama Lengkap'           => $cp->nama_lengkap ?? '-',
                            'Email'                  => $cp->email ?? '-',
                            'No. HP'                 => $cp->no_hp ?? '-',
                            'Asal Sekolah/Instansi'  => $cp->asal_sekolah_atau_instansi ?? '-',
                            'Jenjang Pendidikan'     => $cp->jenjang_pendidikan ?? '-',
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

            {{-- Data Pendaftaran --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <flux:icon name="document-text" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Data Pendaftaran</flux:heading>
                </div>
                <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">No. Referensi</div>
                        <div class="font-mono font-medium text-zinc-700 dark:text-zinc-200">
                            {{ $pendaftaran->no_referensi ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Program Dipilih</div>
                        <div class="font-medium text-zinc-700 dark:text-zinc-200">
                            {{ $pendaftaran->program->nama_program ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Tanggal Daftar</div>
                        <div class="font-medium text-zinc-700 dark:text-zinc-200">
                            {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d F Y') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-xs mb-0.5">Status</div>
                        @php
                            $badge = match($pendaftaran->status) {
                                'disetujui' => 'green',
                                'revisi'    => 'blue',
                                'ditolak'   => 'red',
                                default     => 'yellow',
                            };
                            $label = match($pendaftaran->status) {
                                'pending'   => 'Pending',
                                'disetujui' => 'Disetujui',
                                'revisi'    => 'Perlu Revisi',
                                'ditolak'   => 'Ditolak',
                                default     => $pendaftaran->status,
                            };
                        @endphp
                        <flux:badge color="{{ $badge }}" size="sm">{{ $label }}</flux:badge>
                    </div>

                    @if($pendaftaran->catatan_admin)
                        <div class="sm:col-span-2">
                            <div class="text-zinc-400 text-xs mb-0.5">Catatan Admin</div>
                            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg px-3 py-2 text-zinc-600 dark:text-zinc-300 italic text-sm">
                                {{ $pendaftaran->catatan_admin }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Daftar Dokumen --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <flux:icon name="paper-clip" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Dokumen Pendaftaran</flux:heading>
                </div>
                <div class="px-5 py-4">
                    @forelse($pendaftaran->dokumenPendaftaran as $dok)
                        <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                            <div class="flex items-center gap-3">
                                <flux:icon name="document" class="size-5 text-red-400 shrink-0" />
                                <div>
                                    <div class="text-sm font-medium">{{ $dok->jenis_dokumen }}</div>
                                    <div class="text-xs text-zinc-400">{{ $dok->nama_file }} · v{{ $dok->versi }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @php
                                    $docBadge = match($dok->status_verifikasi) {
                                        'valid'   => 'green',
                                        'invalid' => 'red',
                                        default   => 'yellow',
                                    };
                                @endphp
                                <flux:badge color="{{ $docBadge }}" size="sm">{{ ucfirst($dok->status_verifikasi ?? 'pending') }}</flux:badge>
                                <flux:button
                                    href="{{ asset('storage/' . $dok->file_path) }}"
                                    target="_blank"
                                    size="sm"
                                    variant="ghost"
                                    icon="eye"
                                />
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400 py-2">Belum ada dokumen diunggah.</p>
                    @endforelse
                </div>
            </div>

            {{-- Riwayat Status --}}
            @if($pendaftaran->riwayatStatus->count() > 0)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                        <flux:icon name="clock" class="size-4 text-zinc-400" />
                        <flux:heading size="sm" class="font-semibold">Riwayat Status</flux:heading>
                    </div>
                    <div class="px-5 py-4 space-y-3">
                        @foreach($pendaftaran->riwayatStatus->sortByDesc('created_at') as $riwayat)
                            <div class="flex gap-3 text-sm">
                                <div class="mt-0.5 shrink-0 size-2 rounded-full bg-zinc-300 dark:bg-zinc-600 mt-1.5"></div>
                                <div>
                                    <span class="font-medium">{{ ucfirst($riwayat->status) }}</span>
                                    @if($riwayat->catatan)
                                        <span class="text-zinc-400"> — {{ $riwayat->catatan }}</span>
                                    @endif
                                    <div class="text-xs text-zinc-400 mt-0.5">
                                        {{ $riwayat->created_at->translatedFormat('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- ===== KOLOM KANAN: Aksi Verifikasi (PBI-057) ===== --}}
        <div class="lg:col-span-2 space-y-4">

            @if(in_array($pendaftaran->status, ['pending', 'revisi']))

                {{-- SETUJUI --}}
                <div class="bg-white dark:bg-zinc-900 border border-green-200 dark:border-green-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-green-50 dark:bg-green-950 border-b border-green-200 dark:border-green-800 flex items-center gap-2">
                        <flux:icon name="check-circle" class="size-4 text-green-600" />
                        <span class="font-semibold text-sm text-green-700 dark:text-green-400">Setujui Pendaftaran</span>
                    </div>
                    <div class="px-5 py-4">
                        <flux:text class="text-sm text-zinc-500 mb-4">
                            Semua dokumen lengkap dan memenuhi syarat. Klik tombol di bawah untuk menyetujui.
                        </flux:text>
                        <form
                            action="{{ route('admin.pendaftaran.setujui', $pendaftaran->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menyetujui pendaftaran ini?')"
                        >
                            @csrf
                            <flux:button type="submit" variant="primary" class="w-full bg-green-600 hover:bg-green-700" icon="check">
                                Setujui
                            </flux:button>
                        </form>
                    </div>
                </div>

                {{-- MINTA REVISI --}}
                <div class="bg-white dark:bg-zinc-900 border border-blue-200 dark:border-blue-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-blue-50 dark:bg-blue-950 border-b border-blue-200 dark:border-blue-800 flex items-center gap-2">
                        <flux:icon name="arrow-path" class="size-4 text-blue-600" />
                        <span class="font-semibold text-sm text-blue-700 dark:text-blue-400">Minta Revisi</span>
                    </div>
                    <div class="px-5 py-4">
                        <form action="{{ route('admin.pendaftaran.revisi', $pendaftaran->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <flux:textarea
                                    name="catatan_admin"
                                    placeholder="Tuliskan apa yang perlu direvisi oleh pendaftar..."
                                    rows="3"
                                    class="@error('catatan_admin') border-red-400 @enderror"
                                >{{ old('catatan_admin') }}</flux:textarea>
                                @error('catatan_admin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <flux:button type="submit" class="w-full" icon="pencil">
                                Kirim Permintaan Revisi
                            </flux:button>
                        </form>
                    </div>
                </div>

                {{-- TOLAK --}}
                <div class="bg-white dark:bg-zinc-900 border border-red-200 dark:border-red-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-red-50 dark:bg-red-950 border-b border-red-200 dark:border-red-800 flex items-center gap-2">
                        <flux:icon name="x-circle" class="size-4 text-red-600" />
                        <span class="font-semibold text-sm text-red-700 dark:text-red-400">Tolak Pendaftaran</span>
                    </div>
                    <div class="px-5 py-4">
                        <form
                            action="{{ route('admin.pendaftaran.tolak', $pendaftaran->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menolak pendaftaran ini? Tindakan ini tidak dapat dibatalkan.')"
                        >
                            @csrf
                            <div class="mb-3">
                                <flux:textarea
                                    name="catatan_admin"
                                    placeholder="Tuliskan alasan penolakan..."
                                    rows="3"
                                    class="@error('catatan_admin') border-red-400 @enderror"
                                >{{ old('catatan_admin') }}</flux:textarea>
                                @error('catatan_admin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <flux:button type="submit" variant="danger" class="w-full" icon="x-mark">
                                Tolak Pendaftaran
                            </flux:button>
                        </form>
                    </div>
                </div>

            @else
                {{-- Status sudah final --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm text-center py-10 px-5">
                    @if($pendaftaran->status === 'disetujui')
                        <flux:icon name="check-circle" class="size-10 text-green-500 mx-auto mb-3" />
                        <flux:heading size="sm" class="text-green-600 font-semibold">Pendaftaran Telah Disetujui</flux:heading>
                    @else
                        <flux:icon name="x-circle" class="size-10 text-red-500 mx-auto mb-3" />
                        <flux:heading size="sm" class="text-red-600 font-semibold">Pendaftaran Ditolak</flux:heading>
                    @endif
                    <flux:text class="text-zinc-400 text-sm mt-2">Tidak ada aksi lebih lanjut.</flux:text>
                </div>
            @endif

            {{-- Tombol kembali --}}
            <flux:button href="{{ route('admin.pendaftaran.index') }}" variant="ghost" class="w-full" icon="arrow-left">
                Kembali ke Daftar
            </flux:button>

        </div>
    </div>

</x-layouts.app>
