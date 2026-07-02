<x-layouts::app :title="'Sertifikat Saya'">

{{-- ── Print CSS: A4 landscape, warna terjaga (PBI-131) ── --}}
<style>
    @media print {
        /* Teknik visibility: sembunyikan semua, lalu tampilkan hanya cert-card */
        body * {
            visibility: hidden !important;
        }

        #print-area,
        #print-area * {
            visibility: visible !important;
        }

        #print-area {
            position: fixed !important;
            inset: 0 !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            background: white !important;
            z-index: 99999 !important;
        }

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            print-color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
        }

        .cert-card {
            box-shadow: none !important;
            border: 1.5px solid #e2e8f0 !important;
            page-break-inside: avoid !important;
        }

        /* Sembunyikan tombol aksi saat print */
        .no-print {
            display: none !important;
            visibility: hidden !important;
        }
    }
</style>

{{-- ── Header halaman ── --}}
@if($sertifikats->isNotEmpty())
    @php $s = $sertifikats->first(); @endphp

    {{-- Nomor sertifikat + tombol aksi --}}
    <div class="mb-5 flex items-center justify-between no-print">
        <div>
            <span class="text-xs font-semibold tracking-widest text-zinc-400 uppercase">Sertifikat</span>
            <div class="font-mono text-sm font-bold text-teal-600 mt-0.5">
                #{{ $s->nomor_sertifikat }}
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{-- Salin Link Verifikasi --}}
            @if($s->verified_url)
                <button
                    onclick="navigator.clipboard.writeText('{{ $s->verified_url }}').then(()=>{ this.textContent='✓ Disalin!'; setTimeout(()=>this.textContent='Salin Link Verifikasi',2000) })"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-zinc-200 text-sm font-medium text-zinc-600 hover:bg-zinc-50 transition"
                >
                    <svg class="size-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Salin Link Verifikasi
                </button>
            @endif

            {{-- Cetak --}}
            <button
                onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-teal-500 bg-teal-500 text-sm font-medium text-white hover:bg-teal-600 transition"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </button>
        </div>
    </div>
@endif

{{-- ── Konten Utama ── --}}
@if($sertifikats->isEmpty())
    {{-- State kosong --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-16 text-center shadow-sm no-print">
        <div class="size-16 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mx-auto mb-4">
            <svg class="size-8 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div class="font-semibold text-zinc-500 dark:text-zinc-400 text-lg">Belum ada sertifikat</div>
        <p class="text-zinc-400 text-sm mt-1">Selesaikan program untuk mendapatkan sertifikat dari akademi.</p>
    </div>

@else
    {{-- Tampilkan satu sertifikat (yang pertama / utama) sebagai dokumen resmi --}}
    @foreach($sertifikats as $s)
        @php
            $progAkademik = $s->siswa?->progressAkademik
                ->firstWhere('kelas_id', $s->kelas_id);
            $kehadiran  = $progAkademik?->persentase_kehadiran;
            $nilaiAkhir = $progAkademik?->rata_nilai_tugas;
            $durasi     = $s->kelas?->batch?->program?->durasi_minggu;
            $jumlahSesi = $s->kelas?->sesiLive?->count() ?? 0;
            $instrNama  = $s->penerbit?->nama_lengkap ?? 'Administrator';
        @endphp

        {{-- ID untuk keperluan print --}}
        <div id="print-area" class="cert-card bg-white rounded-2xl shadow-md overflow-hidden mb-6
             border border-zinc-100
             @if(!$loop->first) mt-8 @endif">

            {{-- Garis aksen teal di atas --}}
            <div class="h-1.5 bg-gradient-to-r from-teal-400 to-cyan-500"></div>

            <div class="px-10 py-10">

                {{-- ── Header sertifikat: Logo + Nama Akademi ── --}}
                <div class="flex flex-col items-center text-center mb-8">
                    {{-- Logo icon --}}
                    <div class="size-14 rounded-2xl bg-teal-500 flex items-center justify-center mb-3 shadow-sm">
                        <svg class="size-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>

                    {{-- Nama Akademi --}}
                    <div class="text-zinc-700 font-semibold text-base">RoboNesia Academy</div>

                    {{-- Judul sertifikat --}}
                    <div class="mt-3 text-xs font-bold tracking-[0.3em] text-teal-500 uppercase">
                        Sertifikat Penyelesaian Program
                    </div>
                    {{-- Garis bawah judul --}}
                    <div class="mt-2 w-24 h-0.5 bg-gradient-to-r from-teal-400 to-cyan-500 rounded-full"></div>
                </div>

                {{-- ── Isi sertifikat ── --}}
                <div class="text-center">
                    <p class="text-sm text-zinc-400 mb-2">Diberikan kepada:</p>
                    <h1 class="text-4xl font-black text-zinc-800 tracking-wide uppercase mb-5">
                        {{ $s->siswa?->user?->nama_lengkap ?? '—' }}
                    </h1>

                    <p class="text-sm text-zinc-400 mb-1">Telah menyelesaikan program:</p>
                    <p class="text-2xl font-bold text-teal-500 mb-8">
                        {{ $s->kelas?->batch?->program?->nama_program ?? $s->kelas?->nama_kelas ?? '—' }}
                    </p>

                    {{-- ── 3 Statistik ── --}}
                    <div class="flex justify-center gap-0 mb-8">
                        {{-- Durasi --}}
                        <div class="flex-1 max-w-[160px] px-4 border-r border-zinc-100">
                            <div class="text-lg font-black text-zinc-800">
                                @if($durasi)
                                    {{ $durasi }} Minggu
                                    @if($jumlahSesi > 0)
                                        · {{ $jumlahSesi }} Sesi
                                    @endif
                                @else
                                    —
                                @endif
                            </div>
                            <div class="text-xs text-zinc-400 mt-1 font-medium">Durasi</div>
                        </div>

                        {{-- Nilai Akhir --}}
                        <div class="flex-1 max-w-[160px] px-4 border-r border-zinc-100">
                            <div class="text-lg font-black text-zinc-800">
                                @if($nilaiAkhir !== null)
                                    {{ number_format($nilaiAkhir, 0) }}/100
                                    <span class="text-amber-400 text-base">
                                        @for($i = 1; $i <= 5; $i++)
                                            {{ $nilaiAkhir / 20 >= $i ? '★' : '☆' }}
                                        @endfor
                                    </span>
                                @else
                                    —
                                @endif
                            </div>
                            <div class="text-xs text-zinc-400 mt-1 font-medium">Nilai Akhir</div>
                        </div>

                        {{-- Kehadiran --}}
                        <div class="flex-1 max-w-[160px] px-4">
                            <div class="text-lg font-black text-zinc-800">
                                @if($kehadiran !== null)
                                    {{ number_format($kehadiran, 1) }}%
                                @else
                                    —
                                @endif
                            </div>
                            <div class="text-xs text-zinc-400 mt-1 font-medium">Kehadiran</div>
                        </div>
                    </div>
                </div>

                {{-- ── Footer sertifikat ── --}}
                <div class="border-t border-zinc-100 pt-6 flex items-end justify-between">

                    {{-- Kiri: Tanggal & Nomor --}}
                    <div>
                        <div class="text-xs text-zinc-400 mb-1">Diterbitkan</div>
                        <div class="font-bold text-zinc-700">
                            {{ $s->tanggal_terbit
                                ? \Carbon\Carbon::parse($s->tanggal_terbit)->translatedFormat('d F Y')
                                : '—' }}
                        </div>
                        <div class="text-xs font-mono text-zinc-400 mt-1">{{ $s->nomor_sertifikat }}</div>
                    </div>

                    {{-- Kanan: Instruktur / Penerbit + Cap --}}
                    <div class="flex items-end gap-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold italic text-teal-600" style="font-family: Georgia, serif;">
                                {{ $instrNama }}
                            </div>
                            <div class="text-xs text-zinc-400 mt-0.5">{{ $instrNama }} · Instruktur</div>
                        </div>
                        {{-- Cap / Seal --}}
                        <div class="size-12 rounded-full border-2 border-teal-500 flex items-center justify-center text-teal-500">
                            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>{{-- end px-10 py-10 --}}
        </div>{{-- end cert-card --}}

        {{-- Jika punya lebih dari 1 sertifikat, tampilkan sisanya sebagai kartu ringkas --}}
    @endforeach

    {{-- Daftar sertifikat lain (jika lebih dari 1) --}}
    @if($sertifikats->count() > 1)
        <div class="mt-6 no-print">
            <div class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-3">Sertifikat Lainnya</div>
            <div class="space-y-3">
                @foreach($sertifikats->skip(1) as $s)
                    <div class="bg-white border border-zinc-100 rounded-xl px-5 py-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="size-10 rounded-xl bg-teal-50 flex items-center justify-center">
                                <svg class="size-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-zinc-800">
                                    {{ $s->kelas?->batch?->program?->nama_program ?? $s->kelas?->nama_kelas ?? '—' }}
                                </div>
                                <div class="text-xs text-zinc-400 font-mono">{{ $s->nomor_sertifikat }}</div>
                            </div>
                        </div>
                        <div class="text-sm text-zinc-400">
                            {{ $s->tanggal_terbit
                                ? \Carbon\Carbon::parse($s->tanggal_terbit)->translatedFormat('d M Y')
                                : '—' }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endif

</x-layouts::app>
