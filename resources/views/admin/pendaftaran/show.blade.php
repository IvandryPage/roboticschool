<x-layouts.app :title="'Review Pendaftaran'">

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-sm text-zinc-400">
        <a href="{{ route('admin.pendaftaran.index') }}" class="hover:text-zinc-600 transition">Pendaftaran</a>
        <span>/</span>
        <span class="text-zinc-600 dark:text-zinc-300">Review #{{ substr($pendaftaran->id, 0, 8) }}</span>
    </div>

    {{-- Flash: Success --}}
    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-5">
            {{ session('success') }}
        </flux:callout>
    @endif

    {{-- Flash: Error --}}
    @if(session('error'))
        <flux:callout variant="danger" icon="x-circle" class="mb-5">
            {{ session('error') }}
        </flux:callout>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- ═══════════════════════════════════════════
             KOLOM KIRI: Data Peserta + Dokumen
        ═══════════════════════════════════════════ --}}
        <div class="lg:col-span-3 space-y-4">

            {{-- PBI-062: Data Calon Peserta (lengkap) --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <flux:icon name="user" class="size-4 text-zinc-400" />
                    <flux:heading size="sm" class="font-semibold">Data Calon Peserta</flux:heading>
                </div>
                <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                    @php
                        $cp = $pendaftaran->calonPeserta;
                        $rows = [
                            'Nama Lengkap'          => $cp->nama_lengkap ?? '-',
                            'Email'                 => $cp->email ?? '-',
                            'No. HP'                => $cp->no_hp ?? '-',
                            'Asal Sekolah/Instansi' => $cp->asal_sekolah_atau_instansi ?? '-',
                            'Jenjang Pendidikan'    => $cp->jenjang_pendidikan ?? '-',
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

            {{-- PBI-062: Data Pendaftaran --}}
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
                            $labelStatus = match($pendaftaran->status) {
                                'pending'   => 'Pending',
                                'disetujui' => 'Disetujui',
                                'revisi'    => 'Perlu Revisi',
                                'ditolak'   => 'Ditolak',
                                default     => $pendaftaran->status,
                            };
                        @endphp
                        <flux:badge color="{{ $badge }}" size="sm">{{ $labelStatus }}</flux:badge>
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

            {{-- PBI-062 + PBI-063: Dokumen Pendaftaran + Pratinjau + Verifikasi --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <flux:icon name="paper-clip" class="size-4 text-zinc-400" />
                        <flux:heading size="sm" class="font-semibold">Dokumen Pendaftaran</flux:heading>
                    </div>
                    {{-- Ringkasan verifikasi --}}
                    <span class="text-xs text-zinc-400">
                        {{ $validDokumen }}/{{ $totalDokumen }} valid
                    </span>
                </div>

                <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse($pendaftaran->dokumenPendaftaran as $dok)
                        @php
                            $docBadge = match($dok->status_verifikasi) {
                                'valid'   => 'green',
                                'invalid' => 'red',
                                default   => 'yellow',
                            };
                            $docLabel = match($dok->status_verifikasi) {
                                'valid'   => 'Valid',
                                'invalid' => 'Tidak Valid',
                                default   => 'Belum Diverifikasi',
                            };
                            $ext = strtolower(pathinfo($dok->nama_file, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $isPdf   = $ext === 'pdf';
                        @endphp

                        <div x-data="{ openPreview: false, openVerif: false }" class="px-5 py-4">

                            {{-- Baris utama dokumen --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($isImage)
                                        <flux:icon name="photo" class="size-5 text-blue-400 shrink-0" />
                                    @else
                                        <flux:icon name="document" class="size-5 text-red-400 shrink-0" />
                                    @endif
                                    <div class="min-w-0">
                                        <div class="text-sm font-medium truncate">{{ $dok->jenis_dokumen }}</div>
                                        <div class="text-xs text-zinc-400 truncate">
                                            {{ $dok->nama_file }} · v{{ $dok->versi }}
                                        </div>
                                        @if($dok->catatan)
                                            <div class="text-xs text-amber-600 dark:text-amber-400 mt-0.5 italic">
                                                Catatan: {{ $dok->catatan }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <flux:badge color="{{ $docBadge }}" size="sm">{{ $docLabel }}</flux:badge>

                                    {{-- PBI-062: Pratinjau dokumen --}}
                                    <flux:button
                                        @click="openPreview = !openPreview"
                                        size="sm"
                                        variant="ghost"
                                        icon="eye"
                                        title="Pratinjau"
                                    />

                                    {{-- Buka di tab baru --}}
                                    <flux:button
                                        href="{{ asset('storage/' . $dok->file_path) }}"
                                        target="_blank"
                                        size="sm"
                                        variant="ghost"
                                        icon="arrow-top-right-on-square"
                                        title="Buka di tab baru"
                                    />

                                    {{-- PBI-063: Toggle form verifikasi --}}
                                    @if(in_array($pendaftaran->status, ['pending', 'revisi']))
                                        <flux:button
                                            @click="openVerif = !openVerif"
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil-square"
                                            title="Verifikasi dokumen"
                                        />
                                    @endif
                                </div>
                            </div>

                            {{-- PBI-062: Panel pratinjau inline --}}
                            <div x-show="openPreview" x-transition class="mt-3 rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800">
                                @if($isImage)
                                    <img
                                        src="{{ asset('storage/' . $dok->file_path) }}"
                                        alt="{{ $dok->jenis_dokumen }}"
                                        class="w-full max-h-96 object-contain"
                                        data-fallback-url="{{ asset('storage/' . $dok->file_path) }}"
                                        onerror="this.parentElement.innerHTML='<p class=\'p-4 text-sm text-zinc-400 text-center\'>Gagal memuat gambar. <a href=\'' + this.getAttribute('data-fallback-url') + '\' target=\'_blank\' class=\'underline\'>Buka di tab baru</a></p>'"
                                    />
                                @elseif($isPdf)
                                    <iframe
                                        src="{{ asset('storage/' . $dok->file_path) }}"
                                        class="w-full h-96"
                                        title="{{ $dok->jenis_dokumen }}"
                                    ></iframe>
                                @else
                                    <div class="p-4 text-sm text-zinc-400 text-center">
                                        Pratinjau tidak tersedia untuk tipe file ini.
                                        <a href="{{ asset('storage/' . $dok->file_path) }}" target="_blank" class="underline text-zinc-500">
                                            Unduh file
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- PBI-063: Form verifikasi dokumen --}}
                            @if(in_array($pendaftaran->status, ['pending', 'revisi']))
                                <div x-show="openVerif" x-transition class="mt-3">
                                    <form
                                        action="{{ route('admin.pendaftaran.verifikasi-dokumen', [$pendaftaran->id, $dok->id]) }}"
                                        method="POST"
                                        class="bg-zinc-50 dark:bg-zinc-800 rounded-lg px-4 py-3 space-y-3"
                                    >
                                        @csrf
                                        <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wide mb-2">
                                            Verifikasi: {{ $dok->jenis_dokumen }}
                                        </div>

                                        {{-- Pilih status --}}
                                        <div class="flex gap-3">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="status_verifikasi"
                                                    value="valid"
                                                    {{ $dok->status_verifikasi === 'valid' ? 'checked' : '' }}
                                                    class="text-green-600 focus:ring-green-500"
                                                >
                                                <span class="text-sm font-medium text-green-700 dark:text-green-400">✓ Valid</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="status_verifikasi"
                                                    value="invalid"
                                                    {{ $dok->status_verifikasi === 'invalid' ? 'checked' : '' }}
                                                    class="text-red-600 focus:ring-red-500"
                                                >
                                                <span class="text-sm font-medium text-red-700 dark:text-red-400">✗ Tidak Valid</span>
                                            </label>
                                        </div>

                                        {{-- Catatan opsional --}}
                                        <flux:textarea
                                            name="catatan"
                                            placeholder="Catatan (opsional) — contoh: foto tidak terbaca, dokumen kadaluarsa..."
                                            rows="2"
                                        >{{ $dok->catatan }}</flux:textarea>

                                        <div class="flex gap-2">
                                            <flux:button type="submit" size="sm" variant="primary">
                                                Simpan Verifikasi
                                            </flux:button>
                                            <flux:button type="button" @click="openVerif = false" size="sm" variant="ghost">
                                                Batal
                                            </flux:button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                        </div>
                    @empty
                        <div class="px-5 py-6 text-center text-sm text-zinc-400">
                            <flux:icon name="inbox" class="size-8 mx-auto mb-2" />
                            Belum ada dokumen diunggah.
                        </div>
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
                                @php
                                    $dotColor = match($riwayat->status) {
                                        'disetujui' => 'bg-green-400',
                                        'revisi'    => 'bg-blue-400',
                                        'ditolak'   => 'bg-red-400',
                                        default     => 'bg-zinc-300 dark:bg-zinc-600',
                                    };
                                @endphp
                                <div class="mt-1.5 shrink-0 size-2 rounded-full {{ $dotColor }}"></div>
                                <div>
                                    <span class="font-medium capitalize">{{ $riwayat->status }}</span>
                                    @if($riwayat->catatan)
                                        <span class="text-zinc-400"> — {{ $riwayat->catatan }}</span>
                                    @endif
                                    <div class="text-xs text-zinc-400 mt-0.5">
                                        {{ $riwayat->created_at->translatedFormat('d M Y, H:i') }}
                                        @if($riwayat->diubahOleh)
                                            · oleh {{ $riwayat->diubahOleh->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- ═══════════════════════════════════════════
             KOLOM KANAN: Panel Aksi Admin
        ═══════════════════════════════════════════ --}}
        <div class="lg:col-span-2 space-y-4">

            @if(in_array($pendaftaran->status, ['pending', 'revisi']))

                {{-- ───── PBI-064: SETUJUI ───── --}}
                <div class="bg-white dark:bg-zinc-900 border {{ $semuaValid ? 'border-green-200 dark:border-green-800' : 'border-zinc-200 dark:border-zinc-700' }} rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-3 {{ $semuaValid ? 'bg-green-50 dark:bg-green-950 border-b border-green-200 dark:border-green-800' : 'bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-100 dark:border-zinc-700' }} flex items-center gap-2">
                        <flux:icon name="check-circle" class="size-4 {{ $semuaValid ? 'text-green-600' : 'text-zinc-400' }}" />
                        <span class="font-semibold text-sm {{ $semuaValid ? 'text-green-700 dark:text-green-400' : 'text-zinc-600 dark:text-zinc-300' }}">
                            Setujui Pendaftaran
                        </span>
                    </div>
                    <div class="px-5 py-4">
                        @if($semuaValid)
                            <flux:text class="text-sm text-zinc-500 mb-4">
                                Semua {{ $totalDokumen }} dokumen telah diverifikasi dan valid. Pendaftaran siap disetujui.
                            </flux:text>
                            <form
                                action="{{ route('admin.pendaftaran.setujui', $pendaftaran->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menyetujui pendaftaran ini?')"
                            >
                                @csrf
                                <flux:button type="submit" variant="primary" class="w-full bg-green-600 hover:bg-green-700" icon="check">
                                    Setujui Pendaftaran
                                </flux:button>
                            </form>
                        @else
                            <div class="text-sm text-zinc-400 text-center py-2">
                                <flux:icon name="lock-closed" class="size-5 mx-auto mb-1 text-zinc-300" />
                                Verifikasi semua dokumen terlebih dahulu.
                                <br>
                                <span class="text-xs">({{ $validDokumen }}/{{ $totalDokumen }} dokumen valid)</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ───── PBI-066: KIRIM CATATAN REVISI ───── --}}
                <div x-data="{ openRevisi: false }" class="bg-white dark:bg-zinc-900 border border-blue-200 dark:border-blue-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-blue-50 dark:bg-blue-950 border-b border-blue-200 dark:border-blue-800 flex items-center gap-2">
                        <flux:icon name="arrow-path" class="size-4 text-blue-600" />
                        <span class="font-semibold text-sm text-blue-700 dark:text-blue-400">Kirim Catatan Revisi</span>
                    </div>
                    <div class="px-5 py-4">
                        <button
                            @click="openRevisi = !openRevisi"
                            class="w-full text-left text-sm text-zinc-500 mb-2 flex items-center justify-between"
                        >
                            <span>Ada dokumen yang perlu diperbaiki calon peserta?</span>
                            <flux:icon name="chevron-down" class="size-4 transition-transform" :class="{ 'rotate-180': openRevisi }" />
                        </button>

                        <div x-show="openRevisi" x-transition>
                            <form action="{{ route('admin.pendaftaran.revisi', $pendaftaran->id) }}" method="POST">
                                @csrf

                                {{-- Pilih dokumen bermasalah (PBI-066) --}}
                                @if($pendaftaran->dokumenPendaftaran->count() > 0)
                                    <div class="mb-3">
                                        <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wide mb-2">
                                            Dokumen yang perlu diperbaiki
                                        </div>
                                        <div class="space-y-2">
                                            @foreach($pendaftaran->dokumenPendaftaran as $dok)
                                                <div
                                                    x-data="{ checked: false }"
                                                    class="border border-zinc-100 dark:border-zinc-700 rounded-lg overflow-hidden"
                                                >
                                                    <label class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                                        <input
                                                            type="checkbox"
                                                            name="dokumen_bermasalah[]"
                                                            value="{{ $dok->id }}"
                                                            @change="checked = $event.target.checked"
                                                            {{ $dok->status_verifikasi === 'invalid' ? 'checked' : '' }}
                                                            class="rounded text-blue-600 focus:ring-blue-500"
                                                            x-init="checked = {{ $dok->status_verifikasi === 'invalid' ? 'true' : 'false' }}"
                                                        >
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-sm font-medium">{{ $dok->jenis_dokumen }}</div>
                                                            <div class="text-xs text-zinc-400">{{ $dok->nama_file }}</div>
                                                        </div>
                                                        @php
                                                            $cb = match($dok->status_verifikasi) {
                                                                'valid'   => 'green',
                                                                'invalid' => 'red',
                                                                default   => 'yellow',
                                                            };
                                                        @endphp
                                                        <flux:badge color="{{ $cb }}" size="sm">
                                                            {{ match($dok->status_verifikasi) { 'valid' => 'Valid', 'invalid' => 'Tdk Valid', default => 'Pending' } }}
                                                        </flux:badge>
                                                    </label>

                                                    {{-- Catatan per dokumen --}}
                                                    <div x-show="checked" x-transition class="px-3 pb-2">
                                                        <input
                                                            type="text"
                                                            name="catatan_dokumen[{{ $dok->id }}]"
                                                            value="{{ $dok->catatan }}"
                                                            placeholder="Catatan untuk dokumen ini (opsional)..."
                                                            class="w-full text-xs border border-zinc-200 dark:border-zinc-600 rounded px-2 py-1.5 bg-white dark:bg-zinc-900 focus:outline-none focus:ring-1 focus:ring-blue-400"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Catatan umum revisi --}}
                                <div class="mb-3">
                                    <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wide mb-1">
                                        Catatan Revisi <span class="text-red-400">*</span>
                                    </div>
                                    <flux:textarea
                                        name="catatan_admin"
                                        placeholder="Jelaskan apa yang perlu diperbaiki oleh pendaftar..."
                                        rows="3"
                                        class="@error('catatan_admin') border-red-400 @enderror"
                                    >{{ old('catatan_admin') }}</flux:textarea>
                                    @error('catatan_admin')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <flux:button type="submit" class="w-full" icon="paper-airplane">
                                    Kirim Permintaan Revisi
                                </flux:button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ───── PBI-065: TOLAK PENDAFTARAN ───── --}}
                <div x-data="{ openTolak: false }" class="bg-white dark:bg-zinc-900 border border-red-200 dark:border-red-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-red-50 dark:bg-red-950 border-b border-red-200 dark:border-red-800 flex items-center gap-2">
                        <flux:icon name="x-circle" class="size-4 text-red-600" />
                        <span class="font-semibold text-sm text-red-700 dark:text-red-400">Tolak Pendaftaran</span>
                    </div>
                    <div class="px-5 py-4">
                        <button
                            @click="openTolak = !openTolak"
                            class="w-full text-left text-sm text-zinc-500 mb-2 flex items-center justify-between"
                        >
                            <span>Pendaftaran tidak memenuhi syarat?</span>
                            <flux:icon name="chevron-down" class="size-4 transition-transform" :class="{ 'rotate-180': openTolak }" />
                        </button>

                        <div x-show="openTolak" x-transition>
                            <form
                                action="{{ route('admin.pendaftaran.tolak', $pendaftaran->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menolak pendaftaran ini? Tindakan ini tidak dapat dibatalkan.')"
                            >
                                @csrf
                                <div class="mb-3">
                                    <flux:textarea
                                        name="catatan_admin"
                                        placeholder="Tuliskan alasan penolakan yang jelas untuk calon peserta..."
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
                </div>

            @else
                {{-- Status sudah final --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm text-center py-10 px-5">
                    @if($pendaftaran->status === 'disetujui')
                        <flux:icon name="check-circle" class="size-10 text-green-500 mx-auto mb-3" />
                        <flux:heading size="sm" class="text-green-600 font-semibold">Pendaftaran Telah Disetujui</flux:heading>
                        <flux:text class="text-zinc-400 text-sm mt-2">
                            Semua {{ $totalDokumen }} dokumen valid. Tidak ada aksi lebih lanjut.
                        </flux:text>
                    @elseif($pendaftaran->status === 'ditolak')
                        <flux:icon name="x-circle" class="size-10 text-red-500 mx-auto mb-3" />
                        <flux:heading size="sm" class="text-red-600 font-semibold">Pendaftaran Ditolak</flux:heading>
                        <flux:text class="text-zinc-400 text-sm mt-2">Tidak ada aksi lebih lanjut.</flux:text>
                    @endif
                </div>
            @endif

            {{-- Tombol kembali --}}
            <flux:button href="{{ route('admin.pendaftaran.index') }}" variant="ghost" class="w-full" icon="arrow-left">
                Kembali ke Daftar
            </flux:button>

        </div>
    </div>

</x-layouts.app>
