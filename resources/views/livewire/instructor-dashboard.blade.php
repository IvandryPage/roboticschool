<div>
    <h2 class="text-2xl font-bold mb-4">Jadwal Sesi Live Mendatang</h2>
    
    @if($sesiMendatang->isEmpty())
        <p class="text-gray-500">Belum ada sesi live yang dijadwalkan.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border-b text-left">Judul Sesi</th>
                        <th class="py-2 px-4 border-b text-left">Kelas</th>
                        <th class="py-2 px-4 border-b text-left">Tanggal</th>
                        <th class="py-2 px-4 border-b text-left">Waktu</th>
                        <th class="py-2 px-4 border-b text-left">Platform</th>
                        <th class="py-2 px-4 border-b text-left">Link</th>
                        <th class="py-2 px-4 border-b text-left">Jumlah Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sesiMendatang as $sesi)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $sesi->judul_sesi }}</td>
                            <td class="py-2 px-4 border-b">{{ $sesi->kelas->nama_kelas }}</td>
                            <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($sesi->tanggal)->format('d M Y') }}</td>
                            <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }}</td>
                            <td class="py-2 px-4 border-b">{{ $sesi->platform }}</td>
                            <td class="py-2 px-4 border-b">
                                <a href="{{ $sesi->link_akses }}" target="_blank" class="text-blue-500 hover:underline">Akses Link</a>
                            </td>
                            <td class="py-2 px-4 border-b">{{ $sesi->kelas->enrollmentKelas()->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
