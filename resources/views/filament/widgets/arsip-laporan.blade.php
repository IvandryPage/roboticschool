<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Arsip Laporan Terbaru</x-slot>
        <x-slot name="headerEnd">
            <a href="{{ \App\Filament\Resources\Laporans\LaporanResource::getUrl('index') }}"
               class="text-xs font-semibold text-blue-600 hover:underline">Lihat Semua →</a>
        </x-slot>

        @if($laporan->isEmpty())
            <p class="text-center text-sm text-gray-400 py-10">Belum ada arsip laporan.</p>
        @else
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm" style="font-family: 'Inter', sans-serif; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="text-align:left; padding: 12px 16px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em; white-space:nowrap;">Judul Laporan</th>
                        <th style="text-align:left; padding: 12px 16px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em; white-space:nowrap;">Tipe</th>
                        <th style="text-align:left; padding: 12px 16px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em; white-space:nowrap;">Periode</th>
                        <th style="text-align:left; padding: 12px 16px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em; white-space:nowrap;">Dibuat Oleh</th>
                        <th style="text-align:left; padding: 12px 16px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em; white-space:nowrap;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan as $i => $lap)
                    @php
                        $tipeMap = [
                            'laporan_kelulusan'  => ['label' => 'Kelulusan',  'bg' => '#dcfce7', 'color' => '#15803d'],
                            'laporan_keuangan'   => ['label' => 'Keuangan',   'bg' => '#fef9c3', 'color' => '#a16207'],
                            'laporan_akademik'   => ['label' => 'Akademik',   'bg' => '#dbeafe', 'color' => '#1d4ed8'],
                            'laporan_instruktur' => ['label' => 'Instruktur', 'bg' => '#f3e8ff', 'color' => '#7e22ce'],
                            'laporan_bulanan'    => ['label' => 'Bulanan',    'bg' => '#f1f5f9', 'color' => '#475569'],
                            'laporan_tahunan'    => ['label' => 'Tahunan',    'bg' => '#fee2e2', 'color' => '#b91c1c'],
                        ];
                        $t = $tipeMap[$lap->tipe_laporan] ?? ['label' => $lap->tipe_laporan, 'bg' => '#f1f5f9', 'color' => '#475569'];
                        $rowBg = $i % 2 === 0 ? '#ffffff' : '#f9fafb';
                        $inisial = strtoupper(substr($lap->pembuat?->nama_lengkap ?? '?', 0, 1));
                        $nama = $lap->pembuat?->nama_lengkap ?? '—';
                    @endphp
                    <tr style="background:{{ $rowBg }}; border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 16px; max-width: 220px;">
                            <p style="font-weight:600; color:#111827; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ \Illuminate\Support\Str::limit($lap->judul, 40) }}
                            </p>
                        </td>
                        <td style="padding: 12px 16px; white-space:nowrap;">
                            <span style="background:{{ $t['bg'] }}; color:{{ $t['color'] }}; padding: 3px 10px; border-radius: 9999px; font-size:11px; font-weight:700;">
                                {{ $t['label'] }}
                            </span>
                        </td>
                        <td style="padding: 12px 16px; color:#6b7280; white-space:nowrap;">
                            {{ $lap->periode ?? '—' }}
                        </td>
                        <td style="padding: 12px 16px; white-space:nowrap;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:28px; height:28px; border-radius:50%; background:#dbeafe; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; color:#1d4ed8; flex-shrink:0;">
                                    {{ $inisial }}
                                </div>
                                <span style="color:#374151; font-weight:500;">{{ $nama }}</span>
                            </div>
                        </td>
                        <td style="padding: 12px 16px; color:#9ca3af; font-size:12px; white-space:nowrap;">
                            {{ $lap->created_at?->translatedFormat('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
