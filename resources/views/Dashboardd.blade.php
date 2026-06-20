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
                <a href="#"
                    class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Masuk</a>
                <a href="#"
                    class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 hover:shadow-md flex items-center gap-1.5">
                    Daftar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
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

    <section id="program" class="py-24 px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <span class="text-xs font-bold tracking-widest text-cyan-600 uppercase mb-3 block">Katalog Jalur</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-4">Pilih Jalur Belajarmu
            </h2>
            <p class="text-slate-400 max-w-xl mx-auto text-sm sm:text-base mb-10">
                Mulai dari tingkat pemula tanpa basis pemrograman hingga mahir di ekosistem IoT & ROS.
            </p>

            <div class="inline-flex bg-slate-100 p-1.5 rounded-2xl gap-1 mb-12">
                <button
                    class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-white text-slate-900 shadow-sm transition">Semua</button>
                <button
                    class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-900 transition">Online</button>
                <button
                    class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-900 transition">Semi-Offline</button>
                <button
                    class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-900 transition">Bootcamp</button>
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
                <a href="#"
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

    <footer class="bg-[#0b1329] text-slate-500 text-xs py-8 px-6 sm:px-8 border-t border-slate-900">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>&copy; {{ date('Y') }} RoboNesia Academy. Hak Cipta Dilindungi.</div>
            <div class="flex gap-6 font-medium">
                <a href="#" class="hover:text-slate-300 transition-colors">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-slate-300 transition-colors">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

</body>

</html>