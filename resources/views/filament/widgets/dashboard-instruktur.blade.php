<x-filament-widgets::widget>
<div style="font-family: 'Inter', sans-serif;">
    <x-filament::section>
        <x-slot name="heading">Evaluasi Kelas Saya</x-slot>

        @if($evaluasi->isEmpty())
            <p style="text-align:center; color:#9ca3af; padding:40px 0; font-size:14px;">Belum ada evaluasi dari siswa.</p>
        @else
        @php
            $avgSkor  = $evaluasi->whereNotNull('skor_rata_rata')->avg('skor_rata_rata');
            $totalEv  = $evaluasi->count();
            $kelasSet = $evaluasi->pluck('kelas.nama_kelas')->filter()->unique()->count();
            $bintangAvg = $avgSkor ? (int) round($avgSkor) : 0;
        @endphp

        {{-- Summary Cards --}}
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:20px;">
            <div style="border:1px solid #fde68a; border-radius:16px; background:#fffbeb; padding:16px; text-align:center;">
                <div style="display:flex; justify-content:center; gap:3px; margin-bottom:8px;">
                    @for($i=1;$i<=5;$i++)
                    <svg style="width:16px; height:16px; flex-shrink:0;" fill="{{ $i <= $bintangAvg ? '#facc15' : '#e5e7eb' }}" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <p style="font-size:26px; font-weight:900; color:#b45309; margin:0;">{{ $avgSkor ? number_format($avgSkor, 1) : '–' }}</p>
                <p style="font-size:11px; font-weight:700; color:#d97706; text-transform:uppercase; letter-spacing:0.08em; margin:4px 0 0;">Rata-rata</p>
            </div>
            <div style="border:1px solid #99f6e4; border-radius:16px; background:#f0fdfa; padding:16px; text-align:center;">
                <p style="font-size:28px; font-weight:900; color:#0f766e; margin:0;">{{ $totalEv }}</p>
                <p style="font-size:11px; font-weight:700; color:#14b8a6; text-transform:uppercase; letter-spacing:0.08em; margin:4px 0 0;">Total Evaluasi</p>
            </div>
            <div style="border:1px solid #bfdbfe; border-radius:16px; background:#eff6ff; padding:16px; text-align:center;">
                <p style="font-size:28px; font-weight:900; color:#1d4ed8; margin:0;">{{ $kelasSet }}</p>
                <p style="font-size:11px; font-weight:700; color:#3b82f6; text-transform:uppercase; letter-spacing:0.08em; margin:4px 0 0;">Kelas</p>
            </div>
        </div>

        {{-- Table --}}
        <div style="border:1px solid #e5e7eb; border-radius:16px; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse; font-size:14px; font-family:'Inter',sans-serif;">
                <thead>
                    <tr style="background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                        <th style="text-align:left; padding:14px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em;">Kelas</th>
                        <th style="text-align:left; padding:14px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em;">Siswa</th>
                        <th style="text-align:center; padding:14px 16px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em;">Skor</th>
                        <th style="text-align:center; padding:14px 16px; font-size:11px; font-weight:700; color:#b45309; text-transform:uppercase; letter-spacing:0.08em;">Bintang</th>
                        <th style="text-align:left; padding:14px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em;">Ulasan</th>
                        <th style="text-align:left; padding:14px 16px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluasi as $ev)
                    @php
                        $skor = $ev->skor_rata_rata ?? 0;
                        $bt   = (int) round($skor);
                        if ($skor >= 4)     { $badgeBg='#ccfbf1'; $badgeColor='#0f766e'; }
                        elseif ($skor >= 3) { $badgeBg='#fef9c3'; $badgeColor='#a16207'; }
                        else                { $badgeBg='#fee2e2'; $badgeColor='#dc2626'; }
                        $inisial = strtoupper(substr($ev->siswa?->user?->nama_lengkap ?? '?', 0, 1));
                    @endphp
                    <tr style="border-bottom:1px solid #f3f4f6;">
                        <td style="padding:14px 20px; font-weight:700; color:#111827;">{{ $ev->kelas?->nama_kelas ?? '–' }}</td>
                        <td style="padding:14px 20px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; border-radius:50%; background:#ccfbf1; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:900; color:#0f766e; flex-shrink:0;">
                                    {{ $inisial }}
                                </div>
                                <span style="font-weight:500; color:#374151;">{{ $ev->siswa?->user?->nama_lengkap ?? '–' }}</span>
                            </div>
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            @if($ev->skor_rata_rata)
                            <span style="background:{{ $badgeBg }}; color:{{ $badgeColor }}; padding:4px 12px; border-radius:9999px; font-size:12px; font-weight:800;">
                                {{ number_format($skor, 1) }}/5
                            </span>
                            @else
                            <span style="color:#d1d5db; font-size:12px;">–</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            <div style="display:flex; justify-content:center; gap:3px;">
                                @for($i=1;$i<=5;$i++)
                                <svg style="width:18px; height:18px; flex-shrink:0;" fill="{{ $i <= $bt ? '#facc15' : '#e5e7eb' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                        </td>
                        <td style="padding:14px 20px; color:#6b7280; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ $ev->saran_ulasan ? \Illuminate\Support\Str::limit($ev->saran_ulasan, 55) : '–' }}
                        </td>
                        <td style="padding:14px 16px; color:#9ca3af; font-size:12px; white-space:nowrap;">
                            {{ $ev->created_at?->translatedFormat('d M Y') ?? '–' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </x-filament::section>
</div>
</x-filament-widgets::widget>
