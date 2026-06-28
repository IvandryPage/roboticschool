<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoboNesia Academy - Kuasai Robotika</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Efek Grid Blueprint khas Figma Design */
        .grid-bg {
            background-size: 32px 32px;
            background-image: linear-gradient(to right, rgba(6, 182, 212, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(6, 182, 212, 0.05) 1px, transparent 1px);
        }

        .dark-grid-bg {
            background-size: 24px 24px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>

<body class="bg-white text-slate-900 antialiased selection:bg-cyan-500 selection:text-white">

    @php
        $program = request('program');
    @endphp

    @if (!$program)

        <header class="sticky top-0 z-50 bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 h-20 flex items-center justify-between">
                <a href="#"
                    class="flex items-center gap-2.5 font-bold text-xl text-slate-900 tracking-tight hover:opacity-90 transition">
                    <div
                        class="bg-gradient-to-tr from-cyan-500 to-blue-500 text-white p-2 rounded-xl shadow-md shadow-cyan-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 5h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2z">
                            </path>
                        </svg>
                    </div>
                    <span>RoboNesia <span class="font-normal text-slate-400 text-sm tracking-normal">Academy</span></span>
                </a>

                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="#tentang" class="hover:text-cyan-500 transition-colors">Tentang Kami</a>
                    <a href="#program" class="hover:text-cyan-500 transition-colors">Program</a>
                    <a href="#bootcamp" class="hover:text-cyan-500 transition-colors">Bootcamp</a>
                    <a href="#" class="hover:text-cyan-500 transition-colors flex items-center gap-1">News <span
                            class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span></a>
                </nav>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-900 transition-colors cursor-pointer">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Masuk</a>
                        <a href="{{ route('pendaftaran.create') }}"
                            class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 hover:shadow-md flex items-center gap-1.5">
                            Daftar
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <section id="home" class="relative overflow-hidden bg-white">

            <!-- GRID BACKGROUND -->
            <div class="absolute inset-0 opacity-100" style="
            background-image:
            linear-gradient(#e5e7eb 1px, transparent 1px),
            linear-gradient(90deg,#e5e7eb 1px,transparent 1px);
            background-size:80px 80px;">
            </div>

            <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-24">

                <div class="grid lg:grid-cols-2 gap-16 items-center">

                    <!-- LEFT -->
                    <div>

                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-100 text-cyan-700 text-sm font-semibold mb-8">

                            🤖 PLATFORM ROBOTIKA #1

                        </div>

                        <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight text-slate-900">

                            Kuasai Robotika,
                            <br>

                            <span class="text-cyan-500">
                                Mulai
                            </span>

                            dari Sini.

                        </h1>

                        <p class="mt-8 text-xl text-slate-600 leading-relaxed max-w-xl">

                            Program terstruktur mulai dari elektronika dasar hingga
                            robot industrial. Belajar online, semi-offline,
                            atau bootcamp intensif.

                        </p>

                        <div class="flex flex-wrap gap-4 mt-10">

                            <a href="#program"
                                class="px-8 py-4 bg-cyan-500 text-white rounded-xl font-semibold hover:bg-cyan-600 transition">

                                Mulai Belajar →

                            </a>

                            <a href="#program"
                                class="px-8 py-4 border-2 border-cyan-500 text-cyan-600 rounded-xl font-semibold hover:bg-cyan-50 transition">

                                Lihat Program

                            </a>

                        </div>

                        <div class="mt-14 pt-10 border-t border-slate-200">

                            <div class="flex gap-12">

                                <div>
                                    <div class="text-5xl font-extrabold text-cyan-500">
                                        500+
                                    </div>

                                    <div class="text-slate-500">
                                        Siswa
                                    </div>
                                </div>

                                <div>
                                    <div class="text-5xl font-extrabold text-cyan-500">
                                        12
                                    </div>

                                    <div class="text-slate-500">
                                        Program
                                    </div>
                                </div>

                                <div>
                                    <div class="text-5xl font-extrabold text-cyan-500">
                                        95%
                                    </div>

                                    <div class="text-slate-500">
                                        Lulus
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="relative">

                        <div class="relative bg-cyan-50 rounded-[32px] p-16 shadow-2xl">

                            <!-- CHIP TOP -->
                            <div class="absolute left-0 top-8 -translate-x-5 bg-white rounded-2xl px-5 py-4 shadow-lg">

                                <span class="font-mono text-sm">
                                    ⚙ analogRead(A0)
                                </span>

                            </div>

                            <!-- CHIP RIGHT -->
                            <div
                                class="absolute right-0 top-1/2 translate-x-5 bg-white rounded-2xl w-14 h-14 flex items-center justify-center shadow-lg">

                                💻

                            </div>

                            <!-- CHIP LEFT BOTTOM -->
                            <div
                                class="absolute left-10 bottom-4 bg-white rounded-2xl w-12 h-12 flex items-center justify-center shadow-lg">

                                ⚡

                            </div>

                            <!-- CHIP RIGHT BOTTOM -->
                            <div class="absolute right-0 bottom-12 translate-x-5 bg-white rounded-2xl px-5 py-4 shadow-lg">

                                📶 IoT Connected

                            </div>

                            <!-- ROBOT CARD -->
                            <div
                                class="bg-gradient-to-br from-slate-950 to-cyan-800 rounded-[28px] aspect-square flex items-center justify-center">

                                <svg xmlns="http://www.w3.org/2000/svg" class="w-40 h-40 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 3h6m-3 0v4m-5 4h10a2 2 0 012 2v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4a2 2 0 012-2zm2 3v2m6-2v2m-8 5h8" />

                                </svg>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>
        <section id="tentang" class="py-24 px-6 sm:px-8 lg:px-16 bg-white">
            <div class="max-w-7xl mx-auto">

                <div class="grid lg:grid-cols-2 gap-16 items-center">

                    <!-- KIRI -->
                    <div>

                        <span
                            class="inline-flex items-center bg-cyan-50 text-cyan-600 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider mb-6">
                            Tentang Kami
                        </span>

                        <h2 class="text-4xl lg:text-5xl font-extrabold leading-tight mb-6">
                            RoboNesia Academy — Lahir dari Passion,
                            <br>
                            <span class="text-cyan-500">
                                Dibangun untuk Masa Depan.
                            </span>
                        </h2>

                        <p class="text-slate-500 leading-relaxed mb-6">
                            Didirikan pada 2020, RoboNesia Academy adalah lembaga pendidikan
                            teknologi robotika yang fokus mencetak generasi insinyur muda
                            Indonesia. Kami menyediakan kurikulum berbasis proyek nyata yang
                            disusun bersama praktisi industri.
                        </p>

                        <p class="text-slate-500 leading-relaxed mb-10">
                            Dari elektronika dasar hingga robot otonom berbasis ROS, setiap
                            program dirancang agar siswa tidak hanya paham teori — tapi mampu
                            membangun, memecahkan masalah, dan berinovasi.
                        </p>

                        <div class="grid grid-cols-4 gap-8">

                            <div>
                                <div class="text-4xl font-extrabold text-cyan-500">2020</div>
                                <div class="text-sm text-slate-500">Tahun Berdiri</div>
                            </div>

                            <div>
                                <div class="text-4xl font-extrabold text-cyan-500">500+</div>
                                <div class="text-sm text-slate-500">Lulusan</div>
                            </div>

                            <div>
                                <div class="text-4xl font-extrabold text-cyan-500">12+</div>
                                <div class="text-sm text-slate-500">Program Aktif</div>
                            </div>

                            <div>
                                <div class="text-4xl font-extrabold text-cyan-500">95%</div>
                                <div class="text-sm text-slate-500">Tingkat Kelulusan</div>
                            </div>

                        </div>

                    </div>

                    <!-- KANAN -->
                    <div class="relative">

                        <div class="bg-[#0E3A54] rounded-3xl p-10 text-white overflow-hidden relative">

                            <div class="absolute inset-0 opacity-20" style="
                            background-image:
                            linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px);
                            background-size:24px 24px;">
                            </div>

                            <div class="relative z-10">

                                <div class="flex items-center gap-3 mb-8">

                                    <div class="w-12 h-12 rounded-xl bg-cyan-500 flex items-center justify-center">
                                        🤖
                                    </div>

                                    <div>
                                        <h3 class="font-bold">
                                            RoboNesia Academy
                                        </h3>
                                        <p class="text-xs text-cyan-200">
                                            Est. 2020 • Jakarta, Indonesia
                                        </p>
                                    </div>

                                </div>

                                <p class="text-2xl font-bold leading-relaxed">
                                    "Kami percaya setiap anak muda Indonesia berhak
                                    mendapatkan pendidikan teknologi berkualitas tinggi
                                    yang relevan dengan industri global."
                                </p>

                            </div>

                        </div>

                        <div class="absolute -bottom-6 left-6 bg-white rounded-2xl shadow-xl p-4 border">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    🏆
                                </div>

                                <div>
                                    <div class="font-semibold text-sm">
                                        Lembaga Terverifikasi
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        Kemendikbud RI 2023
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- KEUNGGULAN -->
                <div class="mt-32">

                    <div class="text-center mb-14">

                        <span class="text-cyan-500 text-xs font-bold uppercase tracking-widest">
                            Keunggulan
                        </span>

                        <h2 class="text-4xl font-extrabold mt-3">
                            Kenapa Belajar di RoboNesia?
                        </h2>

                    </div>

                    <div class="grid md:grid-cols-3 gap-6">

                        <div class="border rounded-2xl p-6 hover:shadow-lg transition">
                            <h3 class="font-bold mb-3">
                                Kurikulum Berbasis Proyek
                            </h3>
                            <p class="text-sm text-slate-500">
                                Setiap sesi dirancang dengan project nyata sehingga
                                siswa belajar dengan membangun, bukan hanya membaca teori.
                            </p>
                        </div>

                        <div class="border rounded-2xl p-6 hover:shadow-lg transition">
                            <h3 class="font-bold mb-3">
                                Jalur Belajar Tersusun
                            </h3>
                            <p class="text-sm text-slate-500">
                                Fondasi → Spesialisasi → Project Akhir dengan kurikulum
                                yang jelas dan terarah.
                            </p>
                        </div>

                        <div class="border rounded-2xl p-6 hover:shadow-lg transition">
                            <h3 class="font-bold mb-3">
                                Instruktur Praktisi Industri
                            </h3>
                            <p class="text-sm text-slate-500">
                                Dibimbing langsung oleh profesional aktif di bidang
                                robotika dan IoT.
                            </p>
                        </div>

                        <div class="border rounded-2xl p-6 hover:shadow-lg transition">
                            <h3 class="font-bold mb-3">
                                Fleksibel Online & Offline
                            </h3>
                            <p class="text-sm text-slate-500">
                                Tersedia kelas online, semi-offline dan bootcamp intensif.
                            </p>
                        </div>

                        <div class="border rounded-2xl p-6 hover:shadow-lg transition">
                            <h3 class="font-bold mb-3">
                                Sertifikat Terverifikasi
                            </h3>
                            <p class="text-sm text-slate-500">
                                Sertifikat resmi yang dapat diverifikasi secara online.
                            </p>
                        </div>

                        <div class="border rounded-2xl p-6 hover:shadow-lg transition">
                            <h3 class="font-bold mb-3">
                                Komunitas Alumni Aktif
                            </h3>
                            <p class="text-sm text-slate-500">
                                Terhubung dengan ratusan alumni dan peluang karier.
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        </section>

        <section id="program" class="py-24 px-6 sm:px-8 lg:px-16 bg-slate-50">

            <div class="max-w-7xl mx-auto">

                <!-- Heading -->
                <div class="text-center mb-14">
                    <span class="text-cyan-500 text-xs font-bold uppercase tracking-[0.2em]">
                        Katalog Jalur
                    </span>

                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mt-3">
                        Pilih Jalur Belajarmu
                    </h2>

                    <p class="text-slate-500 max-w-2xl mx-auto mt-4">
                        Tersedia program online, semi-offline, dan bootcamp intensif
                        sesuai kebutuhanmu.
                    </p>
                </div>

                <!-- Filter -->
                <div class="flex justify-center mb-12">
                    <div class="inline-flex bg-slate-100 p-1.5 rounded-2xl gap-1">
                        <button
                            class="filter-btn px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-cyan-500 text-white transition"
                            data-filter="all">
                            Semua
                        </button>

                        <button
                            class="filter-btn px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-900 transition"
                            data-filter="online">
                            Online
                        </button>

                        <button
                            class="filter-btn px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-900 transition"
                            data-filter="semi">
                            Semi-Offline
                        </button>

                        <button
                            class="filter-btn px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-900 transition"
                            data-filter="bootcamp">
                            Bootcamp
                        </button>
                    </div>
                </div>

                <!-- Card Program -->
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

                @if(isset($programs) && $programs->count())
                    @foreach($programs as $prog)
                    @php
                        $activeBatch = $prog->batches->first();
                        $levelColor = match(strtolower($prog->level ?? '')) {
                            'pemula' => 'bg-green-100 text-green-700',
                            'menengah' => 'bg-yellow-100 text-yellow-700',
                            'lanjutan' => 'bg-red-100 text-red-700',
                            default => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <div class="program-card bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-2 transition-all duration-300"
                        data-category="online">

                        <div class="bg-[#0b3553] p-8 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-20" style="background-image:linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px);background-size:24px 24px;"></div>

                            <div class="flex justify-between mb-10">
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                                    ● Online
                                </span>
                                <span class="{{ $levelColor }} text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $prog->level ?? 'Umum' }}
                                </span>
                            </div>

                            @if($prog->gambar)
                                <div class="flex justify-center">
                                    <img src="{{ Storage::url($prog->gambar) }}" alt="{{ $prog->nama_program }}" class="w-24 h-24 object-cover rounded-xl">
                                </div>
                            @else
                                <div class="flex justify-center">
                                    <svg class="w-24 h-24 text-cyan-400" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M45 22H68V42" stroke="currentColor" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                                        <rect x="25" y="42" width="70" height="50" rx="12" stroke="currentColor" stroke-width="6"/>
                                        <line x1="48" y1="60" x2="48" y2="72" stroke="currentColor" stroke-width="6" stroke-linecap="round"/>
                                        <line x1="72" y1="60" x2="72" y2="72" stroke="currentColor" stroke-width="6" stroke-linecap="round"/>
                                        <line x1="15" y1="67" x2="25" y2="67" stroke="currentColor" stroke-width="6" stroke-linecap="round"/>
                                        <line x1="95" y1="67" x2="105" y2="67" stroke="currentColor" stroke-width="6" stroke-linecap="round"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $prog->nama_program }}</h3>

                            <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ $prog->deskripsi ?? '-' }}</p>

                            <div class="space-y-2 text-sm text-slate-500 mb-4">
                                <div class="flex justify-between">
                                    <span>Durasi</span>
                                    <span class="font-semibold text-slate-700">{{ $prog->durasi_minggu ? $prog->durasi_minggu . ' Minggu' : '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Biaya</span>
                                    <span class="font-semibold text-cyan-500">Rp{{ number_format($prog->biaya ?? 0, 0, ',', '.') }}</span>
                                </div>
                                @if($activeBatch)
                                <div class="flex justify-between">
                                    <span>Batch Aktif</span>
                                    <span class="font-semibold text-slate-700">{{ $activeBatch->nama_batch }}</span>
                                </div>
                                @endif
                            </div>

                            @if($prog->materiProgram->count())
                            <div class="mb-4">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Materi</p>
                                <ul class="space-y-1">
                                    @foreach($prog->materiProgram->take(3) as $materi)
                                    <li class="text-xs text-slate-500 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shrink-0"></span>
                                        {{ $materi->judul_materi }}
                                    </li>
                                    @endforeach
                                    @if($prog->materiProgram->count() > 3)
                                    <li class="text-xs text-slate-400">+{{ $prog->materiProgram->count() - 3 }} materi lainnya</li>
                                    @endif
                                </ul>
                            </div>
                            @endif

                            <a href="{{ route('pendaftaran.create') }}"
                                class="w-full flex justify-center items-center bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 rounded-xl transition">
                                Daftar Sekarang →
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-3 text-center py-16 text-slate-400">
                        <p class="text-lg">Belum ada program yang ditampilkan.</p>
                    </div>
                @endif

                    <!-- CARD 1 (legacy — hidden) -->
                    <div class="hidden" style="display:none!important">
                    <div class="program-card bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-2 transition-all duration-300"
                        data-category="online">

                        <div class="bg-[#0b3553] p-8 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-20" style="
                                background-image:
                                linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),
                                linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px);
                                background-size:24px 24px;">
                            </div>

                            <div class="flex justify-between mb-10">
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                                    ● Online
                                </span>

                                <span class="bg-slate-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    Pemula
                                </span>
                            </div>

                            <div class="flex justify-center">
                                <svg class="w-24 h-24 text-cyan-400" viewBox="0 0 120 120" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <!-- Antena -->
                                    <path d="M45 22H68V42" stroke="currentColor" stroke-width="6" stroke-linecap="round"
                                        stroke-linejoin="round" />

                                    <!-- Kepala Robot -->
                                    <rect x="25" y="42" width="70" height="50" rx="12" stroke="currentColor"
                                        stroke-width="6" />

                                    <!-- Mata -->
                                    <line x1="48" y1="60" x2="48" y2="72" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <line x1="72" y1="60" x2="72" y2="72" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <!-- Tangan kiri -->
                                    <line x1="15" y1="67" x2="25" y2="67" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <!-- Tangan kanan -->
                                    <line x1="95" y1="67" x2="105" y2="67" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                </svg>
                            </div>

                        </div>

                        <div class="p-6">

                            <p class="text-cyan-500 text-xs font-bold uppercase tracking-widest mb-3">
                                Mikrokontroler
                            </p>

                            <h3 class="text-2xl font-bold text-slate-900 mb-4">
                                Arduino Basic
                            </h3>

                            <div class="space-y-3 text-sm text-slate-500 mb-6">

                                <div class="flex justify-between">
                                    <span>Level</span>
                                    <span class="font-semibold text-slate-700">
                                        Pemula
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Durasi</span>
                                    <span class="font-semibold text-slate-700">
                                        3 Bulan
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Biaya</span>
                                    <span class="font-semibold text-cyan-500">
                                        Rp499.000
                                    </span>
                                </div>

                            </div>

                            <a href="/?program=arduino"
                                class="w-full flex justify-center items-center bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 rounded-xl transition">
                                Lihat Detail →
                            </a>

                        </div>

                    </div>

                    <!-- CARD 2 (legacy hidden) -->
                    <div class="program-card bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-2 transition-all duration-300"
                        data-category="semi">

                        <div class="bg-[#0b3553] p-8 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-20" style="
                                background-image:
                                linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),
                                linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px);
                                background-size:24px 24px;">
                            </div>

                            <div class="flex justify-between mb-10">
                                <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full">
                                    ● Semi-Offline
                                </span>

                                <span class="bg-slate-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    Pemula
                                </span>
                            </div>

                            <div class="flex justify-center">
                                <svg class="w-24 h-24 text-cyan-400" viewBox="0 0 120 120" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <!-- Antena -->
                                    <path d="M45 22H68V42" stroke="currentColor" stroke-width="6" stroke-linecap="round"
                                        stroke-linejoin="round" />

                                    <!-- Kepala Robot -->
                                    <rect x="25" y="42" width="70" height="50" rx="12" stroke="currentColor"
                                        stroke-width="6" />

                                    <!-- Mata -->
                                    <line x1="48" y1="60" x2="48" y2="72" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <line x1="72" y1="60" x2="72" y2="72" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <!-- Tangan kiri -->
                                    <line x1="15" y1="67" x2="25" y2="67" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <!-- Tangan kanan -->
                                    <line x1="95" y1="67" x2="105" y2="67" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                </svg>
                            </div>

                        </div>

                        <div class="p-6">

                            <p class="text-cyan-500 text-xs font-bold uppercase tracking-widest mb-3">
                                Internet of Things
                            </p>

                            <h3 class="text-2xl font-bold text-slate-900 mb-4">
                                IoT Development
                            </h3>

                            <div class="space-y-3 text-sm text-slate-500 mb-6">

                                <div class="flex justify-between">
                                    <span>Level</span>
                                    <span class="font-semibold text-slate-700">
                                        Pemula
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Durasi</span>
                                    <span class="font-semibold text-slate-700">
                                        4 Bulan
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Biaya</span>
                                    <span class="font-semibold text-cyan-500">
                                        Rp799.000
                                    </span>
                                </div>

                            </div>

                            <a href="/?program=iot"
                                class="w-full flex justify-center items-center bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 rounded-xl transition">
                                Lihat Detail →
                            </a>

                        </div>

                    </div>

                    <!-- CARD 3 -->
                    <div class="program-card bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-2 transition-all duration-300"
                        data-category="online">

                        <div class="bg-[#0b3553] p-8 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-20" style="
                                background-image:
                                linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),
                                linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px);
                                background-size:24px 24px;">
                            </div>

                            <div class="flex justify-between mb-10">
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                                    ● Online
                                </span>

                                <span class="bg-slate-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    Menengah
                                </span>
                            </div>

                            <div class="flex justify-center">
                                <svg class="w-24 h-24 text-cyan-400" viewBox="0 0 120 120" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <!-- Antena -->
                                    <path d="M45 22H68V42" stroke="currentColor" stroke-width="6" stroke-linecap="round"
                                        stroke-linejoin="round" />

                                    <!-- Kepala Robot -->
                                    <rect x="25" y="42" width="70" height="50" rx="12" stroke="currentColor"
                                        stroke-width="6" />

                                    <!-- Mata -->
                                    <line x1="48" y1="60" x2="48" y2="72" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <line x1="72" y1="60" x2="72" y2="72" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <!-- Tangan kiri -->
                                    <line x1="15" y1="67" x2="25" y2="67" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <!-- Tangan kanan -->
                                    <line x1="95" y1="67" x2="105" y2="67" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                </svg>
                            </div>

                        </div>

                        <div class="p-6">

                            <p class="text-cyan-500 text-xs font-bold uppercase tracking-widest mb-3">
                                Robotik Lanjut
                            </p>

                            <h3 class="text-2xl font-bold text-slate-900 mb-4">
                                ROS Fundamentals
                            </h3>

                            <div class="space-y-3 text-sm text-slate-500 mb-6">

                                <div class="flex justify-between">
                                    <span>Level</span>
                                    <span class="font-semibold text-slate-700">
                                        Menengah
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Durasi</span>
                                    <span class="font-semibold text-slate-700">
                                        3 Bulan
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Biaya</span>
                                    <span class="font-semibold text-cyan-500">
                                        Rp999.000
                                    </span>
                                </div>

                            </div>

                            <a href="/?program=ros"
                                class="w-full flex justify-center items-center bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 rounded-xl transition">
                                Lihat Detail →
                            </a>

                        </div>

                    </div>

                    <!-- CARD 4 -->
                    <div class="program-card bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-2 transition-all duration-300"
                        data-category="online">

                        <div class="bg-[#0b3553] p-8 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-20" style="
                                background-image:
                                linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),
                                linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px);
                                background-size:24px 24px;">
                            </div>

                            <div class="flex justify-between mb-10">
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                                    ● Online
                                </span>

                                <span class="bg-slate-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    Pemula
                                </span>
                            </div>

                            <div class="flex justify-center">
                                <svg class="w-24 h-24 text-cyan-400" viewBox="0 0 120 120" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <!-- Antena -->
                                    <path d="M45 22H68V42" stroke="currentColor" stroke-width="6" stroke-linecap="round"
                                        stroke-linejoin="round" />

                                    <!-- Kepala Robot -->
                                    <rect x="25" y="42" width="70" height="50" rx="12" stroke="currentColor"
                                        stroke-width="6" />

                                    <!-- Mata -->
                                    <line x1="48" y1="60" x2="48" y2="72" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <line x1="72" y1="60" x2="72" y2="72" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <!-- Tangan kiri -->
                                    <line x1="15" y1="67" x2="25" y2="67" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                    <!-- Tangan kanan -->
                                    <line x1="95" y1="67" x2="105" y2="67" stroke="currentColor" stroke-width="6"
                                        stroke-linecap="round" />

                                </svg>
                            </div>

                        </div>

                        <div class="p-6">

                            <p class="text-cyan-500 text-xs font-bold uppercase tracking-widest mb-3">
                                Fondasi
                            </p>

                            <h3 class="text-2xl font-bold text-slate-900 mb-4">
                                Elektronika Dasar
                            </h3>

                            <div class="space-y-3 text-sm text-slate-500 mb-6">

                                <div class="flex justify-between">
                                    <span>Level</span>
                                    <span class="font-semibold text-slate-700">
                                        Pemula
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Durasi</span>
                                    <span class="font-semibold text-slate-700">
                                        2 Bulan
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Biaya</span>
                                    <span class="font-semibold text-cyan-500">
                                        Rp399.000
                                    </span>
                                </div>

                            </div>

                            <a href="/?program=elektronika"
                                class="w-full flex justify-center items-center bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 rounded-xl transition">
                                Lihat Detail →
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <section id="fasilitas" class="py-24 px-6 bg-slate-50">

            <div class="max-w-7xl mx-auto">

                <div class="text-center mb-14">

                    <span class="text-cyan-500 text-xs font-bold uppercase tracking-widest">
                        Fasilitas Pembelajaran
                    </span>

                    <h2 class="text-4xl font-extrabold mt-3">
                        Yang Akan Kamu Dapatkan
                    </h2>

                    <p class="text-slate-500 mt-4 max-w-2xl mx-auto">
                        Seluruh peserta mendapatkan akses pembelajaran lengkap mulai dari sesi belajar hingga sertifikasi
                        resmi.
                    </p>

                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- Live Session -->
                    <div class="bg-white border rounded-2xl p-8 hover:shadow-xl transition">

                        <div class="w-14 h-14 rounded-xl bg-cyan-100 flex items-center justify-center mb-5 text-2xl">
                            🎥
                        </div>

                        <h3 class="font-bold text-lg mb-2">
                            Live Session
                        </h3>

                        <p class="text-slate-500 text-sm">
                            Belajar langsung bersama instruktur melalui kelas online maupun tatap muka.
                        </p>

                    </div>

                    <!-- Materi Digital -->
                    <div class="bg-white border rounded-2xl p-8 hover:shadow-xl transition">

                        <div class="w-14 h-14 rounded-xl bg-cyan-100 flex items-center justify-center mb-5 text-2xl">
                            📚
                        </div>

                        <h3 class="font-bold text-lg mb-2">
                            Materi Digital
                        </h3>

                        <p class="text-slate-500 text-sm">
                            Modul, video pembelajaran, dan dokumentasi yang dapat diakses kapan saja.
                        </p>

                    </div>

                    <!-- Tugas -->
                    <div class="bg-white border rounded-2xl p-8 hover:shadow-xl transition">

                        <div class="w-14 h-14 rounded-xl bg-cyan-100 flex items-center justify-center mb-5 text-2xl">
                            📝
                        </div>

                        <h3 class="font-bold text-lg mb-2">
                            Tugas & Project
                        </h3>

                        <p class="text-slate-500 text-sm">
                            Latihan dan project nyata untuk meningkatkan kemampuan praktik peserta.
                        </p>

                    </div>

                    <!-- Sertifikat -->
                    <div class="bg-white border rounded-2xl p-8 hover:shadow-xl transition">

                        <div class="w-14 h-14 rounded-xl bg-cyan-100 flex items-center justify-center mb-5 text-2xl">
                            🏆
                        </div>

                        <h3 class="font-bold text-lg mb-2">
                            Sertifikat Resmi
                        </h3>

                        <p class="text-slate-500 text-sm">
                            Sertifikat terverifikasi yang dapat digunakan untuk portofolio dan karier.
                        </p>

                    </div>

                </div>

            </div>

        </section>

        <section class="py-28 px-6 bg-white border-b border-slate-100">

            <div class="max-w-7xl mx-auto">

                <div class="text-center mb-20">

                    <span class="text-cyan-500 text-xs font-bold uppercase tracking-[0.2em]">
                        Cara Kerja
                    </span>

                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mt-4">
                        Bagaimana Proses Belajarnya?
                    </h2>

                </div>

                <div class="grid md:grid-cols-5 gap-10 text-center">

                    <!-- STEP 1 -->
                    <div class="flex flex-col items-center">

                        <div class="relative mb-6">

                            <div
                                class="w-16 h-16 bg-cyan-500 rounded-full flex items-center justify-center shadow-xl shadow-cyan-500/20">

                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.253v13m0-13C10.832 5.483 9.246 5 7.5 5S4.168 5.483 3 6.253v13C4.168 18.483 5.754 18 7.5 18s3.332.483 4.5 1.253m0-13C13.168 5.483 14.754 5 16.5 5s3.332.483 4.5 1.253v13C19.832 18.483 18.246 18 16.5 18s-3.332.483-4.5 1.253">
                                    </path>
                                </svg>

                            </div>

                            <div
                                class="absolute -top-2 -right-2 w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                1
                            </div>

                        </div>

                        <h3 class="font-bold text-xl text-slate-900">
                            Pilih Program
                        </h3>

                        <p class="text-slate-500 text-sm mt-2">
                            Pilih jalur belajar sesuai minatmu
                        </p>

                    </div>

                    <!-- STEP 2 -->
                    <div class="flex flex-col items-center">

                        <div class="relative mb-6">

                            <div
                                class="w-16 h-16 bg-cyan-500 rounded-full flex items-center justify-center shadow-xl shadow-cyan-500/20">

                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5V4H2v16h5m10 0v-5a3 3 0 00-6 0v5m6 0H11">
                                    </path>
                                </svg>

                            </div>

                            <div
                                class="absolute -top-2 -right-2 w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                2
                            </div>

                        </div>

                        <h3 class="font-bold text-xl text-slate-900">
                            Daftar & Verifikasi
                        </h3>

                        <p class="text-slate-500 text-sm mt-2">
                            Lengkapi data dan verifikasi email
                        </p>

                    </div>

                    <!-- STEP 3 -->
                    <div class="flex flex-col items-center">

                        <div class="relative mb-6">

                            <div
                                class="w-16 h-16 bg-cyan-500 rounded-full flex items-center justify-center shadow-xl shadow-cyan-500/20">

                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.868v4.264a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>

                            </div>

                            <div
                                class="absolute -top-2 -right-2 w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                3
                            </div>

                        </div>

                        <h3 class="font-bold text-xl text-slate-900">
                            Ikuti Sesi
                        </h3>

                        <p class="text-slate-500 text-sm mt-2">
                            Live online atau tatap muka
                        </p>

                    </div>

                    <!-- STEP 4 -->
                    <div class="flex flex-col items-center">

                        <div class="relative mb-6">

                            <div
                                class="w-16 h-16 bg-cyan-500 rounded-full flex items-center justify-center shadow-xl shadow-cyan-500/20">

                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3h6v4H9z">
                                    </path>
                                </svg>

                            </div>

                            <div
                                class="absolute -top-2 -right-2 w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                4
                            </div>

                        </div>

                        <h3 class="font-bold text-xl text-slate-900">
                            Kerjakan Tugas
                        </h3>

                        <p class="text-slate-500 text-sm mt-2">
                            Praktik dan proyek nyata
                        </p>

                    </div>

                    <!-- STEP 5 -->
                    <div class="flex flex-col items-center">

                        <div class="relative mb-6">

                            <div
                                class="w-16 h-16 bg-cyan-500 rounded-full flex items-center justify-center shadow-xl shadow-cyan-500/20">

                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 15l-3.5 2 1-4L6 9.5l4-.5L12 5l2 4 4 .5-3.5 3.5 1 4z">
                                    </path>
                                </svg>

                            </div>

                            <div
                                class="absolute -top-2 -right-2 w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                5
                            </div>

                        </div>

                        <h3 class="font-bold text-xl text-slate-900">
                            Raih Sertifikat
                        </h3>

                        <p class="text-slate-500 text-sm mt-2">
                            Sertifikat resmi terverifikasi
                        </p>

                    </div>

                </div>

            </div>

        </section>

        <section id="bootcamp"
            class="bg-[#0b1329] dark-grid-bg text-white py-24 px-6 sm:px-12 lg:px-24 relative overflow-hidden">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <div class="lg:col-span-7">
                    <span
                        class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 text-xs font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider mb-5 inline-block">
                        Akselerasi Karir 2026
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold mb-5 leading-tight tracking-tight">
                        Bootcamp Robotika Intensif <br>Tatap Muka Selama 2 Minggu
                    </h2>
                    <p class="text-slate-400 mb-8 max-w-lg text-sm sm:text-base leading-relaxed">
                        Diselenggarakan terbatas hanya 2 kali dalam setahun (Januari & Juli). Program intensif untuk
                        lompatan karir optimal di bidang otomasi industri dan Internet of Things.
                    </p>
                    <a href="{{ route('pendaftaran.create') }}"
                        class="bg-cyan-500 hover:bg-cyan-600 text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all shadow-lg shadow-cyan-500/10 inline-flex items-center gap-2">
                        Daftar Kuota Batch #1 &rarr;
                    </a>
                </div>

                <div class="lg:col-span-5 flex lg:justify-end">
                    <div class="w-full max-w-md bg-[#131c35] border border-slate-800/80 rounded-2xl p-6 shadow-2xl">
                        <div class="text-xs text-slate-400 mb-4 font-bold uppercase tracking-widest">Pendaftaran Ditutup
                            Dalam:</div>

                        <div class="grid grid-cols-4 gap-3 text-center mb-6">
                            <div class="bg-[#0b1329] p-3 rounded-xl border border-slate-800/80">
                                <div class="text-2xl font-extrabold text-cyan-400">45</div>
                                <div class="text-[10px] text-slate-500 font-medium uppercase mt-0.5">Hari</div>
                            </div>
                            <div class="bg-[#0b1329] p-3 rounded-xl border border-slate-800/80">
                                <div class="text-2xl font-extrabold text-cyan-400">12</div>
                                <div class="text-[10px] text-slate-500 font-medium uppercase mt-0.5">Jam</div>
                            </div>
                            <div class="bg-[#0b1329] p-3 rounded-xl border border-slate-800/80">
                                <div class="text-2xl font-extrabold text-cyan-400">26</div>
                                <div class="text-[10px] text-slate-500 font-medium uppercase mt-0.5">Menit</div>
                            </div>
                            <div class="bg-[#0b1329] p-3 rounded-xl border border-slate-800/80">
                                <div class="text-2xl font-extrabold text-cyan-400">41</div>
                                <div class="text-[10px] text-slate-500 font-medium uppercase mt-0.5">Detik</div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between text-xs text-slate-400 pt-4 border-t border-slate-800">
                            <div>Jadwal: <span class="text-white font-medium">Juli 2026</span></div>
                            <div class="text-amber-400 font-semibold bg-amber-500/10 px-2 py-0.5 rounded">🔥 Sisa 8 Kursi
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <footer class="bg-[#0b1329] text-slate-400 py-16 px-6 sm:px-8 border-t border-slate-800">
            <div class="max-w-7xl mx-auto">

                <!-- Top: 4 kolom -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

                    <!-- Kolom 1: Logo + Tagline -->
                    <div>
                        <a href="#" class="flex items-center gap-2.5 font-bold text-lg text-white mb-3">
                            <div
                                class="bg-gradient-to-tr from-cyan-500 to-blue-500 text-white p-2 rounded-xl shadow-md shadow-cyan-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 5h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2z">
                                    </path>
                                </svg>
                            </div>
                            <span>RoboNesia <span class="font-normal text-slate-400 text-sm">Academy</span></span>
                        </a>
                        <p class="text-sm text-slate-500 italic">"Kuasai Robotika, Mulai dari Sini."</p>
                    </div>

                    <!-- Kolom 2: Program -->
                    <div>
                        <h4 class="text-white font-semibold mb-4 text-sm tracking-wide">Program</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="#" class="hover:text-cyan-400 transition-colors">Arduino Basic</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors">IoT Development</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors">ROS Fundamentals</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors">Elektronika Dasar</a></li>
                        </ul>
                    </div>

                    <!-- Kolom 3: Explore -->
                    <div>
                        <h4 class="text-white font-semibold mb-4 text-sm tracking-wide">Explore</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="#" class="hover:text-cyan-400 transition-colors">News</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors">Bootcamp</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors">About</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors">FAQ</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors">Design System</a></li>
                            <li><a href="#" class="hover:text-cyan-400 transition-colors">Portal Instruktur</a></li>
                        </ul>
                    </div>

                    <!-- Kolom 4: Kontak -->
                    <div>
                        <h4 class="text-white font-semibold mb-4 text-sm tracking-wide">Kontak</h4>
                        <ul class="space-y-3 text-sm mb-5">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                hello@robonesia.id
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                +62 812-3456-7890
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Bandung, Indonesia
                            </li>
                        </ul>

                        <!-- Tombol Kirim Keluhan -->
                        <a href="{{ route('keluhan.create') }}"
                            class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-600 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition mb-5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Kirim Keluhan
                        </a>

                        <!-- Sosial Media -->
                        <div class="flex gap-3">
                            <!-- Instagram -->
                            <a href="#"
                                class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-cyan-500 flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                </svg>
                            </a>
                            <!-- YouTube -->
                            <a href="#"
                                class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-cyan-500 flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                </svg>
                            </a>
                            <!-- LinkedIn -->
                            <a href="#"
                                class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-cyan-500 flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Bottom: copyright -->
                <div class="border-t border-slate-800 pt-6 text-center text-xs text-slate-600">
                    &copy; {{ date('Y') }} RoboNesia Academy · All rights reserved
                </div>

            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterButtons = document.querySelectorAll('.filter-btn');
                const cards = document.querySelectorAll('.program-card');

                filterButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const filter = this.dataset.filter;

                        filterButtons.forEach(btn => {
                            btn.classList.remove('bg-cyan-500', 'text-white');
                            btn.classList.add('text-slate-500');
                        });

                        this.classList.add('bg-cyan-500', 'text-white');
                        this.classList.remove('text-slate-500');

                        cards.forEach(card => {
                            if (
                                filter === 'all' ||
                                card.dataset.category === filter
                            ) {
                                card.classList.remove('hidden');
                            } else {
                                card.classList.add('hidden');
                            }
                        });
                    });
                });
            });
        </script>

    @endif
    @if ($program)

        <section class="py-20 px-6 bg-slate-50 min-h-screen">

            <div class="max-w-6xl mx-auto">

                <!-- Tombol Kembali -->
                <a href="/"
                    class="inline-flex items-center gap-2 text-cyan-500 font-semibold mb-8 hover:text-cyan-600 transition">
                    ← Kembali ke Program
                </a>

                <!-- Header Program -->
                <div class="bg-white rounded-3xl p-10 shadow-sm border border-slate-200 mb-10">

                    <span class="bg-cyan-100 text-cyan-700 text-xs font-bold px-3 py-1 rounded-full">
                        Program Kursus
                    </span>

                    <h1 class="text-4xl font-extrabold text-slate-900 mt-5 mb-4">

                        @if ($program == 'arduino')
                            Arduino Basic
                        @elseif ($program == 'iot')
                            IoT Development
                        @elseif ($program == 'ros')
                            ROS Fundamentals
                        @elseif ($program == 'elektronika')
                            Elektronika Dasar
                        @endif

                    </h1>

                    <p class="text-slate-500 leading-relaxed">

                        @if ($program == 'arduino')
                            Pelajari dasar mikrokontroler Arduino mulai dari pemrograman, penggunaan sensor, hingga pembuatan
                            mini project robotika.

                        @elseif ($program == 'iot')
                            Pelajari pengembangan Internet of Things (IoT) menggunakan ESP32, komunikasi data, dan pembuatan
                            sistem monitoring berbasis internet.

                        @elseif ($program == 'ros')
                            Pelajari Robot Operating System (ROS) untuk membangun robot cerdas, simulasi robot, dan sistem
                            navigasi otomatis.

                        @elseif ($program == 'elektronika')
                            Pelajari konsep elektronika dasar, komponen elektronik, dan perancangan rangkaian sederhana sebagai
                            fondasi robotika.

                        @endif

                    </p>

                </div>

                <!-- Grid Informasi -->
                <div class="grid lg:grid-cols-3 gap-8">

                    <!-- Kiri -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Deskripsi -->
                        <div class="bg-white rounded-3xl p-8 border border-slate-200">

                            <h2 class="text-2xl font-bold text-slate-900 mb-4">
                                Deskripsi Program
                            </h2>

                            <p class="text-slate-500 leading-relaxed">
                                Program ini membantu peserta memahami konsep
                                robotika, mikrokontroler, dan implementasi sistem
                                otomasi melalui pembelajaran berbasis project.
                            </p>

                        </div>

                        <!-- Materi -->
                        <div class="bg-white rounded-3xl p-8 border border-slate-200">

                            <h2 class="text-2xl font-bold text-slate-900 mb-6">
                                Materi yang Dipelajari
                            </h2>

                            <div class="space-y-4">

                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-500 flex items-center justify-center font-bold">
                                        ✓
                                    </div>
                                    <span>Pengenalan Robotika dan Elektronika</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-500 flex items-center justify-center font-bold">
                                        ✓
                                    </div>
                                    <span>Dasar Pemrograman Arduino</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-500 flex items-center justify-center font-bold">
                                        ✓
                                    </div>
                                    <span>Penggunaan Sensor dan Aktuator</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-500 flex items-center justify-center font-bold">
                                        ✓
                                    </div>
                                    <span>Pembuatan Mini Project Robotika</span>
                                </div>

                            </div>

                        </div>
                        <!-- Syarat Peserta -->
                        <div class="bg-white rounded-3xl p-8 border border-slate-200">

                            <h2 class="text-2xl font-bold text-slate-900 mb-6">
                                Syarat Peserta
                            </h2>

                            <div class="space-y-4">

                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-500 flex items-center justify-center font-bold flex-shrink-0">
                                        ✓
                                    </div>
                                    <span class="text-slate-600">
                                        Minimal usia 15 tahun.
                                    </span>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-500 flex items-center justify-center font-bold flex-shrink-0">
                                        ✓
                                    </div>
                                    <span class="text-slate-600">
                                        Memiliki laptop untuk praktik pemrograman dan simulasi.
                                    </span>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-500 flex items-center justify-center font-bold flex-shrink-0">
                                        ✓
                                    </div>
                                    <span class="text-slate-600">
                                        Tidak diwajibkan memiliki pengalaman di bidang robotika.
                                    </span>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-500 flex items-center justify-center font-bold flex-shrink-0">
                                        ✓
                                    </div>
                                    <span class="text-slate-600">
                                        Memiliki motivasi belajar dan mampu mengikuti pembelajaran secara mandiri maupun
                                        berkelompok.
                                    </span>
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- Kanan -->
                    <div>

                        <div class="bg-white rounded-3xl p-8 border border-slate-200 sticky top-24">

                            <h2 class="text-2xl font-bold text-slate-900 mb-6">
                                Informasi Program
                            </h2>

                            <div class="space-y-5 text-sm">

                                <div class="flex justify-between">
                                    <span class="text-slate-500">
                                        Level
                                    </span>
                                    <span class="font-semibold">

                                        @if ($program == 'ros')
                                            Menengah
                                        @else
                                            Pemula
                                        @endif
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-slate-500">
                                        Durasi
                                    </span>
                                    <span class="font-semibold">

                                        @if ($program == 'arduino')
                                            3 Bulan
                                        @elseif ($program == 'iot')
                                            4 Bulan
                                        @elseif ($program == 'ros')
                                            3 Bulan
                                        @elseif ($program == 'elektronika')
                                            2 Bulan
                                        @endif

                                    </span>
                                </div>
                                <!-- Jadwal Pendaftaran -->
                                <div class="border-t border-slate-200 pt-6 mt-6">

                                    <h3 class="font-bold text-slate-900 mb-5">
                                        Jadwal Pendaftaran
                                    </h3>

                                    <!-- Status -->
                                    <div
                                        class="flex justify-between items-center bg-green-50 border border-green-200 rounded-2xl px-4 py-3 mb-4">

                                        <span class="text-slate-600">
                                            Status
                                        </span>

                                        <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                                            ● Dibuka
                                        </span>

                                    </div>

                                    <!-- Tanggal -->
                                    <div class="space-y-3 text-sm">

                                        <div class="flex justify-between">
                                            <span class="text-slate-500">
                                                Dibuka
                                            </span>

                                            <span class="font-semibold text-slate-700">
                                                1 Juli 2026
                                            </span>
                                        </div>

                                        <div class="flex justify-between">
                                            <span class="text-slate-500">
                                                Ditutup
                                            </span>

                                            <span class="font-semibold text-slate-700">
                                                31 Juli 2026
                                            </span>
                                        </div>

                                    </div>

                                    <!-- Kuota -->
                                    <div class="mt-5 bg-cyan-50 border border-cyan-200 rounded-2xl p-4 text-center">

                                        <p class="text-sm text-slate-500">
                                            Sisa Kuota
                                        </p>
                                        <div>
                                            <div class="flex justify-between text-xs mb-2">
                                                <span class="text-slate-500">
                                                    Kuota Terisi
                                                </span>

                                                <span class="font-semibold text-red-500">
                                                    12/20 Peserta
                                                </span>
                                            </div>

                                            <div class="w-full bg-slate-200 rounded-full h-2">
                                                <div class="bg-red-500 h-2 rounded-full" style="width:60%">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-3xl font-extrabold text-cyan-500">
                                            8
                                        </p>

                                        <p class="text-xs text-slate-400">
                                            kursi tersedia
                                        </p>

                                    </div>

                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">
                                        Syarat
                                    </span>
                                    <span class="font-semibold text-right">
                                        Tidak perlu pengalaman
                                    </span>
                                </div>

                                <div class="border-t pt-5">

                                    <div class="text-sm text-slate-500 mb-1">
                                        Biaya Program
                                    </div>

                                    <div class="text-3xl font-extrabold text-cyan-500">

                                        @if ($program == 'arduino')
                                            Rp499.000
                                        @elseif ($program == 'iot')
                                            Rp799.000
                                        @elseif ($program == 'ros')
                                            Rp999.000
                                        @elseif ($program == 'elektronika')
                                            Rp399.000
                                        @endif

                                    </div>

                                </div>

                            </div>

                            <a href="{{ route('pendaftaran.create', ['program' => $program]) }}"
                                class="w-full block text-center mt-8 bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-4 rounded-2xl transition">
                                Daftar Sekarang
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    @endif
</body>

</html>