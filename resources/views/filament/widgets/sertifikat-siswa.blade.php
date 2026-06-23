<x-filament-widgets::widget>
<div style="font-family: 'Inter', sans-serif;">
    <x-filament::section>
        <x-slot name="heading">Sertifikat Saya</x-slot>
        <x-slot name="description">
            <span style="color:#6b7280; font-size:13px;">Bukti penyelesaian program robotika</span>
        </x-slot>

        @if($sertifikats->isEmpty())
        <div style="display:flex; flex-direction:column; align-items:center; padding:48px 0; color:#9ca3af;">
            <svg style="width:48px; height:48px; opacity:0.3; margin-bottom:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p style="font-weight:600; font-size:14px; margin:0;">Belum ada sertifikat</p>
            <p style="font-size:12px; margin:4px 0 0; opacity:0.6;">Selesaikan program untuk mendapatkan sertifikat</p>
        </div>
        @else
        @foreach($sertifikats as $s)
        @php
            $progress     = $s->siswa?->progressAkademik()->where('kelas_id', $s->kelas_id)->first();
            $nilaiAkhir   = $progress?->rata_nilai_tugas;
            $kehadiran    = $progress?->persentase_kehadiran;
            $bintang      = $nilaiAkhir ? min(5, (int) round($nilaiAkhir / 20)) : 0;
            $totalSesi    = $s->kelas?->sesiLive?->count() ?? 0;
            $namaProgram  = $s->kelas?->batch?->program?->nama_program ?? ($s->kelas?->nama_kelas ?? 'Program Robotika');
            $namaSiswa    = strtoupper($s->siswa?->user?->nama_lengkap ?? '');
            $namaPenerbit = $s->penerbit?->nama_lengkap ?? 'Instruktur';
            $tglTerbit    = \Carbon\Carbon::parse($s->tanggal_terbit)->translatedFormat('d F Y');
        @endphp

        {{-- Nomor + Aksi --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <span style="font-family:monospace; font-size:13px; font-weight:700; color:#0f766e; background:#f0fdfa; border:1px solid #99f6e4; padding:4px 14px; border-radius:9999px;">
                {{ $s->nomor_sertifikat }}
            </span>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('sertifikat.saya') }}" target="_blank"
                   style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; font-size:12px; font-weight:600; color:#374151; border:1px solid #d1d5db; border-radius:8px; background:#fff; text-decoration:none;">
                    <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak
                </a>
                @if($s->verified_url)
                <a href="{{ $s->verified_url }}" target="_blank"
                   style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; font-size:12px; font-weight:600; color:#0f766e; border:1px solid #99f6e4; border-radius:8px; background:#f0fdfa; text-decoration:none;">
                    <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Verifikasi
                </a>
                @endif
            </div>
        </div>

        {{-- Kartu Sertifikat --}}
        <div style="border:1px solid #e5e7eb; border-radius:16px; overflow:hidden; box-shadow:0 1px 8px rgba(0,0,0,0.06); margin-bottom:24px;">
            {{-- Gradient bar --}}
            <div style="height:6px; background:linear-gradient(to right, #2dd4bf, #06b6d4, #14b8a6);"></div>

            <div style="background:#fff; padding:40px 48px;">
                {{-- Logo & Nama --}}
                <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:28px;">
                    <div style="width:56px; height:56px; border-radius:14px; background:#14b8a6; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(20,184,166,0.3); margin-bottom:10px;">
                        <svg style="width:32px; height:32px;" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p style="font-size:18px; font-weight:900; color:#111827; letter-spacing:-0.02em; margin:0;">RoboNesia Academy</p>
                </div>

                {{-- Judul --}}
                <div style="text-align:center; margin-bottom:28px;">
                    <p style="font-size:11px; font-weight:900; letter-spacing:0.25em; color:#14b8a6; text-transform:uppercase; margin:0 0 10px;">Sertifikat Penyelesaian Program</p>
                    <div style="width:64px; height:2px; background:linear-gradient(to right, transparent, #14b8a6, transparent); margin:0 auto;"></div>
                </div>

                {{-- Nama Siswa --}}
                <div style="text-align:center; margin-bottom:24px;">
                    <p style="font-size:12px; color:#9ca3af; font-weight:500; margin:0 0 8px; letter-spacing:0.05em;">Diberikan kepada:</p>
                    <p style="font-size:32px; font-weight:900; color:#111827; letter-spacing:0.12em; text-transform:uppercase; line-height:1.2; margin:0;">{{ $namaSiswa }}</p>
                </div>

                {{-- Program --}}
                <div style="text-align:center; margin-bottom:28px;">
                    <p style="font-size:12px; color:#9ca3af; font-weight:500; margin:0 0 6px; letter-spacing:0.05em;">Telah menyelesaikan program:</p>
                    <p style="font-size:20px; font-weight:900; color:#14b8a6; margin:0;">{{ $namaProgram }}</p>
                </div>

                {{-- Stats --}}
                @if($totalSesi || $nilaiAkhir || $kehadiran)
                <div style="display:flex; align-items:center; justify-content:center; gap:32px; margin-bottom:28px;">
                    @if($totalSesi)
                    <div style="text-align:center;">
                        <p style="font-size:11px; color:#9ca3af; margin:0 0 4px; font-weight:500;">Durasi</p>
                        <p style="font-size:14px; font-weight:700; color:#374151; margin:0;">{{ $totalSesi }} Sesi</p>
                    </div>
                    @if($nilaiAkhir || $kehadiran)
                    <div style="width:1px; height:32px; background:#e5e7eb;"></div>
                    @endif
                    @endif

                    @if($nilaiAkhir)
                    <div style="text-align:center;">
                        <p style="font-size:11px; color:#9ca3af; margin:0 0 4px; font-weight:500;">Nilai Akhir</p>
                        <p style="font-size:14px; font-weight:700; color:#374151; margin:0 0 4px;">{{ number_format($nilaiAkhir, 0) }}/100</p>
                        <div style="display:flex; justify-content:center; gap:2px;">
                            @for($i=1;$i<=5;$i++)
                            <svg style="width:14px; height:14px;" fill="{{ $i<=$bintang ? '#facc15' : '#e5e7eb' }}" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                    </div>
                    @if($kehadiran)
                    <div style="width:1px; height:32px; background:#e5e7eb;"></div>
                    @endif
                    @endif

                    @if($kehadiran)
                    <div style="text-align:center;">
                        <p style="font-size:11px; color:#9ca3af; margin:0 0 4px; font-weight:500;">Kehadiran</p>
                        <p style="font-size:14px; font-weight:700; color:#374151; margin:0;">{{ number_format($kehadiran, 1) }}%</p>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Footer --}}
                <div style="border-top:1px solid #f3f4f6; padding-top:24px; display:flex; align-items:flex-end; justify-content:space-between;">
                    <div>
                        <p style="font-size:11px; color:#9ca3af; margin:0 0 4px; font-weight:500;">Diterbitkan</p>
                        <p style="font-size:14px; font-weight:700; color:#374151; margin:0;">{{ $tglTerbit }}</p>
                        <p style="font-family:monospace; font-size:11px; color:#9ca3af; margin:4px 0 0;">{{ $s->nomor_sertifikat }}</p>
                    </div>
                    <div style="text-align:center;">
                        <p style="font-size:15px; font-weight:900; font-style:italic; color:#374151; margin:0 0 6px;">{{ $namaPenerbit }}</p>
                        <div style="border-top:1px solid #d1d5db; padding-top:6px;">
                            <p style="font-size:11px; color:#9ca3af; margin:0;">{{ $namaPenerbit }} · Instruktur</p>
                        </div>
                    </div>
                    <div style="width:56px; height:56px; border-radius:50%; background:#f0fdfa; border:2px solid #99f6e4; display:flex; align-items:center; justify-content:center;">
                        @if($s->qr_code)
                            <img src="{{ $s->qr_code }}" style="width:40px; height:40px;" alt="QR">
                        @else
                            <svg style="width:28px; height:28px;" fill="none" viewBox="0 0 24 24" stroke="#14b8a6" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </x-filament::section>
</div>
</x-filament-widgets::widget>
