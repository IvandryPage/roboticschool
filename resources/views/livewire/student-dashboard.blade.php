<div>
    {{-- Quick Links --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
        <a href="{{ route('pendaftaran.status.saya') }}"
           class="flex flex-col items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm hover:border-cyan-400 hover:shadow-md transition">
            <svg class="h-6 w-6 text-cyan-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/></svg>
            <span class="text-sm font-semibold text-slate-700">Status Pendaftaran</span>
        </a>
        <a href="{{ route('sertifikat.saya') }}"
           class="flex flex-col items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm hover:border-cyan-400 hover:shadow-md transition">
            <svg class="h-6 w-6 text-cyan-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-1.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
            <span class="text-sm font-semibold text-slate-700">Sertifikat Saya</span>
        </a>
        <a href="{{ route('siswa.profil.show') }}"
           class="flex flex-col items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm hover:border-cyan-400 hover:shadow-md transition">
            <svg class="h-6 w-6 text-cyan-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
            <span class="text-sm font-semibold text-slate-700">Profil Saya</span>
        </a>
    </div>

    <h2 class="text-2xl font-bold mb-4 mt-8">Jadwal Sesi Live Mendatang</h2>
    
    @if($sesiMendatang->isEmpty())
        <p class="text-gray-500 mb-8">Belum ada sesi live mendatang.</p>
    @else
        <div class="overflow-x-auto mb-8">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border-b text-left">Judul Sesi</th>
                        <th class="py-2 px-4 border-b text-left">Kelas</th>
                        <th class="py-2 px-4 border-b text-left">Tanggal</th>
                        <th class="py-2 px-4 border-b text-left">Waktu</th>
                        <th class="py-2 px-4 border-b text-left">Platform</th>
                        <th class="py-2 px-4 border-b text-left">Link</th>
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
                                <a href="{{ $sesi->link_akses }}" target="_blank" class="text-blue-500 hover:underline">Mulai Sesi</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-4">Riwayat Sesi Live</h2>
    
    @if($riwayatSesi->isEmpty())
        <p class="text-gray-500">Belum ada riwayat sesi live.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 opacity-75">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border-b text-left">Judul Sesi</th>
                        <th class="py-2 px-4 border-b text-left">Kelas</th>
                        <th class="py-2 px-4 border-b text-left">Tanggal</th>
                        <th class="py-2 px-4 border-b text-left">Waktu</th>
                        <th class="py-2 px-4 border-b text-left">Platform</th>
                        <th class="py-2 px-4 border-b text-left">Link</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatSesi as $sesi)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $sesi->judul_sesi }}</td>
                            <td class="py-2 px-4 border-b">{{ $sesi->kelas->nama_kelas }}</td>
                            <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($sesi->tanggal)->format('d M Y') }}</td>
                            <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }}</td>
                            <td class="py-2 px-4 border-b">{{ $sesi->platform }}</td>
                            <td class="py-2 px-4 border-b">
                                <a href="{{ $sesi->link_akses }}" target="_blank" class="text-blue-500 hover:underline">Rekaman / Link</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
