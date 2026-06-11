<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - {{ $sertifikat->nomor_sertifikat }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-2xl w-full text-center border-t-8 border-indigo-600">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Halaman Verifikasi Sertifikat</h1>
        <p class="text-gray-500 mb-6">Robotics School Management System</p>

        <div class="border-2 border-dashed border-gray-200 p-6 rounded-md mb-6 bg-gray-50 text-left">
            <div class="mb-3">
                <span class="text-gray-400 text-sm block">Nomor Sertifikat:</span>
                <strong class="text-indigo-600 text-lg">{{ $sertifikat->nomor_sertifikat }}</strong>
            </div>
            <div class="mb-3">
                <span class="text-gray-400 text-sm block">Nama Siswa:</span>
                <strong class="text-gray-700 text-lg">{{ $sertifikat->siswa->user->name }}</strong>
            </div>
            <div class="mb-3">
                <span class="text-gray-400 text-sm block">Program & Kelas:</span>
                <strong class="text-gray-700">{{ $sertifikat->kelas->batch->nama_batch ?? 'Data Batch Kosong' }} - {{ $sertifikat->kelas->nama_kelas }}</strong>
            </div>
            <div>
                <span class="text-gray-400 text-sm block">Tanggal Terbit:</span>
                <strong class="text-gray-700">{{ \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->translatedFormat('d F Y') }}</strong>
            </div>
        </div>

        <p class="text-green-600 font-medium flex items-center justify-center gap-2">
            ✅ Sertifikat ini Valid dan Terdaftar Resmi
        </p>
    </div>

</body>

</html>