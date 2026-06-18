<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Saya — RoboNesia Academy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .cert-card { box-shadow: none !important; border: 1.5px solid #e5e7eb !important; page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

{{-- Top Navbar --}}
<nav class="no-print bg-white border-b border-gray-200 px-6 py-4 shadow-sm">
    <div class="max-w-5xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-500 flex items-center justify-center shadow-md">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="font-black text-gray-800 text-lg tracking-tight">RoboNesia</span>
        </div>
        <div class="flex items-center gap-4">
            @auth
            <span class="text-sm text-gray-500 font-medium">
                {{ auth()->user()->nama_lengkap ?? auth()->user()->name }}
            </span>
            <a href="{{ url('/admin') }}" class="text-sm text-teal-600 font-semibold hover:underline">Dashboard</a>
            @endauth
        </div>
    </div>
</nav>

<div class="max-w-5xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="no-print mb-8">
        <h1 class="text-3xl font-black text-gray-900">Sertifikat Saya</h1>
        <p class="text-gray-500 mt-1 text-sm">Bukti penyelesaian program robotika</p>
    </div>

    {{-- Peringatan jika bukan siswa --}}
    @if(isset($bukanSiswa) && $bukanSiswa)
    <div class="no-print bg-amber-50 border border-amber-200 rounded-2xl p-6 flex items-start gap-4 mb-6">
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="font-bold text-amber-800">Anda login sebagai Admin/Direktur/Instruktur</p>
            <p class="text-sm text-amber-700 mt-1">Halaman ini hanya untuk siswa. Silakan login menggunakan akun siswa terlebih dahulu.</p>
            <p class="text-xs text-amber-600 mt-2 font-mono">Contoh: budi@siswa.test / password</p>
        </div>
    </div>
    @endif

    @if($sertifikats->isEmpty() && !isset($bukanSiswa))
    {{-- Kosong --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-700">Belum ada sertifikat</h3>
        <p class="text-sm text-gray-400 mt-2">Selesaikan program untuk mendapatkan sertifikat dari akademi.</p>
    </div>
    @endif

    @foreach($sertifikats as $s)
    @php
        $progress = $s->siswa?->progressAkademik()
            ->where('kelas_id', $s->kelas_id)
            ->first();
        $nilaiAkhir  = $progress?->rata_nilai_tugas;
        $kehadiran   = $progress?->persentase_kehadiran;
        $bintang     = $nilaiAkhir ? min(5, round($nilaiAkhir / 20)) : 0;
        $totalSesi   = $s->kelas?->sesiLive?->count() ?? 0;
        $namaProgram = $s->kelas?->batch?->program?->nama_program ?? $s->kelas?->nama_kelas ?? 'Program Robotika';
        $namaSiswa   = strtoupper($s->siswa?->user?->nama_lengkap ?? '');
        $namaPenerbit = $s->penerbit?->nama_lengkap ?? 'Ahmad Fauzi';
    @endphp

    {{-- Nomor & tombol aksi --}}
    <div class="no-print flex items-center justify-between mb-3 px-1">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Sertifikat</span>
            <span class="font-mono text-sm font-bold text-teal-600 bg-teal-50 border border-teal-200 px-3 py-1 rounded-full">
                {{ $s->nomor_sertifikat }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()"
                class="flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </button>
            @if($s->verified_url)
            <button onclick="copyLink('{{ $s->verified_url }}')"
                class="flex items-center gap-1.5 px-4 py-2 bg-teal-500 text-white rounded-lg text-xs font-semibold hover:bg-teal-600 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Salin Link Verifikasi
            </button>
            @endif
        </div>
    </div>

    {{-- Kartu Sertifikat (mengikuti desain Figma) --}}
    <div class="cert-card bg-white rounded-2xl shadow-md overflow-hidden mb-10 border border-gray-100">
        {{-- Gradient bar atas --}}
        <div class="h-2 bg-gradient-to-r from-teal-400 via-cyan-400 to-teal-500"></div>

        <div class="px-14 py-12">
            {{-- Logo & Nama Sekolah --}}
            <div class="flex flex-col items-center mb-8">
                <div class="w-14 h-14 rounded-2xl bg-teal-500 flex items-center justify-center shadow-lg mb-3">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-black text-gray-800 tracking-tight">RoboNesia Academy</h2>
            </div>

            {{-- Judul sertifikat --}}
            <div class="text-center mb-8">
                <p class="text-xs font-black tracking-[0.3em] text-teal-500 uppercase mb-3">
                    Sertifikat Penyelesaian Program
                </p>
                <div class="w-20 h-0.5 bg-gradient-to-r from-transparent via-teal-400 to-transparent mx-auto"></div>
            </div>

            {{-- Nama Siswa --}}
            <div class="text-center mb-8">
                <p class="text-xs text-gray-400 font-medium mb-2 tracking-wide">Diberikan kepada:</p>
                <h3 class="text-4xl font-black text-gray-900 tracking-widest uppercase leading-tight">
                    {{ $namaSiswa ?: 'NAMA SISWA' }}
                </h3>
            </div>

            {{-- Program --}}
            <div class="text-center mb-10">
                <p class="text-xs text-gray-400 font-medium mb-2 tracking-wide">Telah menyelesaikan program:</p>
                <p class="text-xl font-black text-teal-500">{{ $namaProgram }}</p>
            </div>

            {{-- Stats row (Durasi | Nilai Akhir | Kehadiran) --}}
            <div class="flex items-center justify-center gap-10 mb-10">
                @if($totalSesi > 0)
                <div class="text-center">
                    <p class="text-xs text-gray-400 font-medium mb-1">Durasi</p>
                    <p class="text-sm font-bold text-gray-700">{{ $totalSesi }} Sesi</p>
                </div>
                <div class="w-px h-8 bg-gray-200"></div>
                @endif

                @if($nilaiAkhir)
                <div class="text-center">
                    <p class="text-xs text-gray-400 font-medium mb-1">Nilai Akhir</p>
                    <p class="text-sm font-bold text-gray-700">{{ number_format($nilaiAkhir, 0) }}/100</p>
                    <div class="flex items-center justify-center gap-0.5 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-3.5 h-3.5 {{ $i <= $bintang ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>
                </div>
                <div class="w-px h-8 bg-gray-200"></div>
                @endif

                @if($kehadiran)
                <div class="text-center">
                    <p class="text-xs text-gray-400 font-medium mb-1">Kehadiran</p>
                    <p class="text-sm font-bold text-gray-700">{{ number_format($kehadiran, 1) }}%</p>
                </div>
                @endif
            </div>

            {{-- Footer: Tanggal kiri, TTD kanan --}}
            <div class="border-t border-gray-100 pt-8 flex items-end justify-between">
                {{-- Tanggal & Nomor --}}
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-1">Diterbitkan</p>
                    <p class="text-sm font-bold text-gray-700">
                        {{ \Carbon\Carbon::parse($s->tanggal_terbit)->translatedFormat('d F Y') }}
                    </p>
                    <p class="font-mono text-xs text-gray-400 mt-1">{{ $s->nomor_sertifikat }}</p>
                </div>

                {{-- Tanda Tangan --}}
                <div class="text-center">
                    <p class="text-base font-black italic text-gray-700 mb-1">{{ $namaPenerbit }}</p>
                    <div class="border-t border-gray-300 pt-1">
                        <p class="text-xs text-gray-400">{{ $namaPenerbit }} · Instruktur</p>
                    </div>
                </div>

                {{-- Seal / QR --}}
                <div class="w-16 h-16 rounded-full bg-teal-50 border-2 border-teal-100 flex items-center justify-center">
                    @if($s->qr_code)
                        <img src="{{ $s->qr_code }}" alt="QR" class="w-12 h-12">
                    @else
                        <svg class="w-8 h-8 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url)
        .then(() => alert('✅ Link verifikasi berhasil disalin!'))
        .catch(() => prompt('Salin link ini:', url));
}
</script>
</body>
</html>
