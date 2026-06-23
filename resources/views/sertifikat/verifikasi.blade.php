<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - {{ $sertifikat->nomor_sertifikat }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-2xl shadow-lg max-w-2xl w-full text-center border-t-8 border-teal-500">

        <div class="flex items-center justify-center gap-3 mb-4">
            <div class="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xl font-bold text-gray-800">RoboNesia Academy</span>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-1">Verifikasi Sertifikat</h1>
        <p class="text-gray-400 text-sm mb-6">Sistem Informasi Manajemen Sekolah Robotik</p>

        <div class="border-2 border-dashed border-gray-200 p-6 rounded-xl mb-6 bg-gray-50 text-left space-y-4">
            <div>
                <span class="text-gray-400 text-xs uppercase tracking-wide block mb-1">Nomor Sertifikat</span>
                <strong class="text-teal-600 text-lg font-mono">{{ $sertifikat->nomor_sertifikat }}</strong>
            </div>
            <div>
                <span class="text-gray-400 text-xs uppercase tracking-wide block mb-1">Nama Siswa</span>
                <strong class="text-gray-800 text-lg">
                    {{ $sertifikat->siswa?->user?->nama_lengkap ?? $sertifikat->siswa?->user?->name ?? '-' }}
                </strong>
            </div>
            <div>
                <span class="text-gray-400 text-xs uppercase tracking-wide block mb-1">Program & Kelas</span>
                <strong class="text-gray-700">
                    {{ $sertifikat->kelas?->batch?->program?->nama_program ?? 'Program tidak tersedia' }}
                    — {{ $sertifikat->kelas?->nama_kelas ?? '-' }}
                </strong>
            </div>
            <div>
                <span class="text-gray-400 text-xs uppercase tracking-wide block mb-1">Tanggal Terbit</span>
                <strong class="text-gray-700">
                    {{ \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->translatedFormat('d F Y') }}
                </strong>
            </div>
        </div>

        <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 font-semibold px-6 py-3 rounded-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Sertifikat ini Valid dan Terdaftar Resmi
        </div>

        <p class="text-xs text-gray-400 mt-6">
            Halaman ini digunakan untuk memverifikasi keaslian sertifikat yang diterbitkan oleh RoboNesia Academy.
        </p>
    </div>

</body>
</html>