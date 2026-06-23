<x-filament-widgets::widget>
<div style="font-family: 'Inter', sans-serif;">
    <x-filament::section>
        <x-slot name="heading">Rekap Kelulusan Per Program</x-slot>

        {{-- Filter --}}
        <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; margin-bottom:20px; padding:16px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px;">
            <div style="display:flex; flex-direction:column; gap:6px; min-width:180px;">
                <label style="font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.08em;">Program</label>
                <select wire:model.live="filterProgram"
                    style="font-family:'Inter',sans-serif; font-size:14px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#1f2937; padding:8px 12px; outline:none;">
                    <option value="">— Semua Program —</option>
                    @foreach($programs as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; flex-direction:column; gap:6px;">
                <label style="font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.08em;">Tahun</label>
                <select wire:model.live="filterTahun"
                    style="font-family:'Inter',sans-serif; font-size:14px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#1f2937; padding:8px 12px; outline:none;">
                    <option value="">— Semua Tahun —</option>
                    @foreach($tahunOptions as $y => $label)
                        <option value="{{ $y }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if($filterProgram || $filterTahun)
            <button wire:click="$set('filterProgram', null); $set('filterTahun', null)"
                style="display:flex; align-items:center; gap:6px; padding:8px 14px; font-size:12px; font-weight:700; color:#dc2626; border:1px solid #fca5a5; border-radius:8px; background:#fff; cursor:pointer;">
                <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Reset
            </button>
            @endif
        </div>

        @if($rekap->isEmpty())
            <p style="text-align:center; color:#9ca3af; padding:40px 0; font-size:14px;">Belum ada data.</p>
        @else
        @php
            $totSiswa = $rekap->sum('total_siswa');
            $totLulus = $rekap->sum('total_lulus');
            $totAktif = $rekap->sum('total_aktif');
            $totDrop  = $rekap->sum('total_drop');
            $pctTotal = $totSiswa > 0 ? round(($totLulus / $totSiswa) * 100) : 0;
        @endphp

        {{-- Summary Cards --}}
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:16px;">
            <div style="border:1px solid #e5e7eb; border-radius:16px; background:#fff; padding:16px; text-align:center;">
                <p style="font-size:28px; font-weight:900; color:#111827; margin:0;">{{ $totSiswa }}</p>
                <p style="font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.08em; margin:4px 0 0;">Total Siswa</p>
            </div>
            <div style="border:1px solid #99f6e4; border-radius:16px; background:#f0fdfa; padding:16px; text-align:center;">
                <p style="font-size:28px; font-weight:900; color:#0f766e; margin:0;">{{ $totLulus }}</p>
                <p style="font-size:11px; font-weight:700; color:#14b8a6; text-transform:uppercase; letter-spacing:0.08em; margin:4px 0 0;">Lulus</p>
            </div>
            <div style="border:1px solid #bfdbfe; border-radius:16px; background:#eff6ff; padding:16px; text-align:center;">
                <p style="font-size:28px; font-weight:900; color:#1d4ed8; margin:0;">{{ $totAktif }}</p>
                <p style="font-size:11px; font-weight:700; color:#3b82f6; text-transform:uppercase; letter-spacing:0.08em; margin:4px 0 0;">Aktif</p>
            </div>
            <div style="border:1px solid #fecaca; border-radius:16px; background:#fef2f2; padding:16px; text-align:center;">
                <p style="font-size:28px; font-weight:900; color:#dc2626; margin:0;">{{ $totDrop }}</p>
                <p style="font-size:11px; font-weight:700; color:#ef4444; text-transform:uppercase; letter-spacing:0.08em; margin:4px 0 0;">Drop</p>
            </div>
        </div>

        {{-- Table --}}
        <div style="border:1px solid #e5e7eb; border-radius:16px; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse; font-family:'Inter',sans-serif; font-size:14px;">
                <thead>
                    <tr style="background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                        <th style="text-align:left; padding:14px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em;">Program</th>
                        <th style="text-align:left; padding:14px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em;">Batch</th>
                        <th style="text-align:center; padding:14px 16px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em;">Total</th>
                        <th style="text-align:center; padding:14px 16px; font-size:11px; font-weight:700; color:#0f766e; text-transform:uppercase; letter-spacing:0.08em;">Lulus</th>
                        <th style="text-align:center; padding:14px 16px; font-size:11px; font-weight:700; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.08em;">Aktif</th>
                        <th style="text-align:center; padding:14px 16px; font-size:11px; font-weight:700; color:#dc2626; text-transform:uppercase; letter-spacing:0.08em;">Drop</th>
                        <th style="text-align:center; padding:14px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em;">% Lulus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekap as $row)
                    @php
                        $pct = $row->total_siswa > 0 ? round(($row->total_lulus / $row->total_siswa) * 100) : 0;
                        if ($pct >= 75)      { $pctColor = '#0f766e'; $barColor = '#14b8a6'; }
                        elseif ($pct >= 50)  { $pctColor = '#a16207'; $barColor = '#eab308'; }
                        else                 { $pctColor = '#dc2626'; $barColor = '#f87171'; }
                    @endphp
                    <tr style="border-bottom:1px solid #f3f4f6;">
                        <td style="padding:14px 20px; font-weight:700; color:#111827;">{{ $row->nama_program }}</td>
                        <td style="padding:14px 20px; color:#6b7280;">{{ $row->nama_batch }}</td>
                        <td style="padding:14px 16px; text-align:center; font-weight:800; color:#374151;">{{ $row->total_siswa }}</td>
                        <td style="padding:14px 16px; text-align:center;">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:#ccfbf1; color:#0f766e; font-weight:900; font-size:14px;">{{ $row->total_lulus }}</span>
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:#dbeafe; color:#1d4ed8; font-weight:900; font-size:14px;">{{ $row->total_aktif }}</span>
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:#fee2e2; color:#dc2626; font-weight:900; font-size:14px;">{{ $row->total_drop }}</span>
                        </td>
                        <td style="padding:14px 20px; text-align:center;">
                            <div style="display:flex; flex-direction:column; align-items:center; gap:6px;">
                                <span style="font-size:14px; font-weight:900; color:{{ $pctColor }};">{{ $pct }}%</span>
                                <div style="width:72px; height:8px; background:#f3f4f6; border-radius:999px; overflow:hidden;">
                                    <div style="width:{{ $pct }}%; height:8px; background:{{ $barColor }}; border-radius:999px;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#f9fafb; border-top:2px solid #e5e7eb;">
                        <td colspan="2" style="padding:12px 20px; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em;">Total Keseluruhan</td>
                        <td style="padding:12px 16px; text-align:center; font-weight:900; color:#111827;">{{ $totSiswa }}</td>
                        <td style="padding:12px 16px; text-align:center; font-weight:900; color:#0f766e;">{{ $totLulus }}</td>
                        <td style="padding:12px 16px; text-align:center; font-weight:900; color:#1d4ed8;">{{ $totAktif }}</td>
                        <td style="padding:12px 16px; text-align:center; font-weight:900; color:#dc2626;">{{ $totDrop }}</td>
                        <td style="padding:12px 20px; text-align:center; font-weight:900; color:{{ $pctTotal >= 75 ? '#0f766e' : ($pctTotal >= 50 ? '#a16207' : '#dc2626') }};">{{ $pctTotal }}%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    </x-filament::section>
</div>
</x-filament-widgets::widget>
