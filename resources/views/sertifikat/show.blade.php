<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Saya - RoboNesia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .cert-wrapper { box-shadow: none; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="no-print bg-white border-b px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-teal-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="font-semibold text-gray-800">RoboNesia</span>
        </div>
        <span class="text-sm text-gray-500">Sertifikat</span>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="no-print mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Sertifikat Saya</h1>
            <p class="text-gray-500 text-sm mt-1">Bukti penyelesaian program robotika</p>
        </div>

        @if($sertifikat)
        <div class="no-print flex items-center justify-between mb-4">
            <span class="text-xs font-semibold text-gray-400 tracking-widest uppercase">
                Sertifikat #{{ $sertifikat->nomor_sertifikat }}
            </span>
            <div class="flex gap-2">
                <button onclick="window.print()"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak
                </button>
                <button onclick="copyVerifyLink('{{ $sertifikat->verified_url }}')"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Salin Link Verifikasi
                </button>
            </div>
        </div>

        <div class="cert-wrapper bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-teal-400 to-cyan-500"></div>
            <div class="px-12 py-12 text-center">
                <div class="flex items-center justify-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800">RoboNesia Academy</span>
                </div>

                <p class="text-xs font-bold tracking-[0.25em] text-teal-500 uppercase mb-3">
                    Sertifikat Penyelesaian Program
                </p>
                <div class="w-16 h-0.5 bg-teal-400 mx-auto mb-8"></div>

                <p class="text-sm text-gray-500 mb-2">Diberikan kepada:</p>
                <h2 class="text-4xl font-black text-gray-900 tracking-wide uppercase mb-8">
                    {{ $sertifikat->siswa->user->name }}
                </h2>

                <p class="text-sm text-gray-500 mb-2">Telah menyelesaikan program:</p>
                <p class="text-xl font-bold text-teal-500 mb-8">
                    {{ $sertifikat->kelas->programKursus->nama_program ?? $sertifikat->kelas->nama_kelas }}
                </p>

                @php
                    $enrollment = $sertifikat->siswa->enrollments()
                        ->where('kelas_id', $sertifikat->kelas_id)->first();
                @endphp

                <div class="flex justify-center gap-16 mb-12">
                    @if($enrollment?->nilai_akhir)
                    <div class="text-center">
                        <p class="text-xs text-gray-400 mb-1">Nilai Akhir</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $enrollment->nilai_akhir }}/100</p>
                    </div>
                    @endif
                    @if($enrollment?->persentase_kehadiran)
                    <div class="text-center">
                        <p class="text-xs text-gray-400 mb-1">Kehadiran</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $enrollment->persentase_kehadiran }}%</p>
                    </div>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-8">
                    <div class="flex items-end justify-between">
                        <div class="text-left">
                            <p class="text-xs text-gray-400 mb-1">Diterbitkan</p>
                            <p class="text-sm font-semibold text-gray-700">
                                {{ \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-2">{{ $sertifikat->nomor_sertifikat }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-semibold italic text-gray-600 mb-1">
                                {{ $sertifikat->penerbit->name }}
                            </p>
                            <div class="border-t border-gray-300 pt-1">
                                <p class="text-xs text-gray-400">{{ $sertifikat->penerbit->name }} Â· Admin</p>
                            </div>
                        </div>
                        <div class="w-16 h-16 bg-teal-50 rounded-full flex items-center justify-center border-2 border-teal-100">
                            @if($sertifikat->qr_code)
                                <img src="{{ $sertifikat->qr_code }}" alt="QR" class="w-12 h-12">
                            @else
                                <svg class="w-8 h-8 text-teal-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else
        <div class="bg-white rounded-2xl shadow-md p-16 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada sertifikat</h3>
            <p class="text-sm text-gray-400">Selesaikan program untuk mendapatkan sertifikat.</p>
        </div>
        @endif
    </div>

    <script>
        function copyVerifyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link verifikasi berhasil disalin!');
            });
        }
    </script>
</body>
</html>
