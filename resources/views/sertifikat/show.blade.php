<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Saya — RoboNesia Academy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; background: #f3f4f6; }

        /* ── Sidebar ─────────────────────────────────────── */
        .portal-sidebar {
            position: fixed; top: 0; left: 0; height: 100vh; width: 260px;
            background: #ffffff; border-right: 1px solid #e5e7eb;
            display: flex; flex-direction: column; z-index: 50;
        }
        .sidebar-logo {
            padding: 24px 20px 16px;
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid #f3f4f6;
        }
        .sidebar-logo-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: #14b8a6; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-logo-text { font-weight: 800; font-size: 15px; color: #111827; line-height: 1.2; }
        .sidebar-logo-sub  { font-weight: 500; font-size: 10px; color: #6b7280; }

        /* Profile card */
        .sidebar-profile {
            margin: 16px 12px;
            padding: 12px; border-radius: 12px; background: #f9fafb;
            border: 1px solid #e5e7eb;
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, #14b8a6, #06b6d4);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 15px; color: white; flex-shrink: 0;
        }
        .sidebar-profile-name  { font-weight: 700; font-size: 13px; color: #111827; }
        .sidebar-profile-badge {
            font-size: 10px; color: #6b7280; background: #e5e7eb;
            border-radius: 100px; padding: 1px 8px; display: inline-block; margin-top: 2px;
        }

        /* Nav */
        .sidebar-nav { flex: 1; padding: 8px 12px; overflow-y: auto; }
        .sidebar-nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px; margin-bottom: 2px;
            font-size: 14px; font-weight: 500; color: #6b7280;
            text-decoration: none; transition: all 0.15s ease;
            position: relative;
        }
        .sidebar-nav-item:hover { background: #f3f4f6; color: #111827; }
        .sidebar-nav-item.active { background: #f0fdfa; color: #0d9488; font-weight: 700; }
        .sidebar-nav-item.active svg { color: #0d9488; }
        .sidebar-nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .sidebar-badge {
            margin-left: auto; background: #14b8a6; color: white;
            font-size: 10px; font-weight: 700; border-radius: 100px;
            padding: 1px 7px; min-width: 18px; text-align: center;
        }

        /* Logout */
        .sidebar-logout {
            padding: 12px; border-top: 1px solid #f3f4f6;
        }
        .sidebar-logout-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px; width: 100%;
            font-size: 14px; font-weight: 600; color: #ef4444;
            background: none; border: none; cursor: pointer;
            text-decoration: none; transition: background 0.15s;
        }
        .sidebar-logout-btn:hover { background: #fef2f2; }
        .sidebar-logout-btn svg { width: 18px; height: 18px; }

        /* ── Main content ────────────────────────────────── */
        .portal-main {
            margin-left: 260px; min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .portal-topbar {
            background: #fff; border-bottom: 1px solid #e5e7eb;
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
        }
        .topbar-title { font-size: 20px; font-weight: 800; color: #111827; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-bell {
            position: relative; width: 36px; height: 36px;
            background: #f9fafb; border: 1px solid #e5e7eb;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            cursor: pointer;
        }
        .topbar-bell svg { width: 18px; height: 18px; color: #6b7280; }
        .topbar-user {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 600; color: #374151;
        }
        .topbar-user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, #14b8a6, #06b6d4);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 800; color: white;
        }

        /* ── Content ─────────────────────────────────────── */
        .portal-content { padding: 28px; flex: 1; }

        /* Cert actions bar */
        .cert-actions {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 14px;
        }
        .cert-nomor {
            font-size: 12px; font-weight: 700; letter-spacing: 0.08em;
            color: #9ca3af; text-transform: uppercase;
        }
        .cert-nomor span {
            font-family: monospace; color: #0d9488; background: #f0fdfa;
            border: 1px solid #99f6e4; border-radius: 100px;
            padding: 3px 12px; margin-left: 6px; font-size: 12px;
        }
        .btn-cetak {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; background: white; border: 1px solid #d1d5db;
            border-radius: 8px; font-size: 12px; font-weight: 600; color: #374151;
            cursor: pointer; transition: all 0.15s; text-decoration: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        }
        .btn-cetak:hover { background: #f9fafb; }
        .btn-cetak svg { width: 14px; height: 14px; }
        .btn-salin {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; background: #14b8a6; border: none;
            border-radius: 8px; font-size: 12px; font-weight: 600; color: white;
            cursor: pointer; transition: all 0.15s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .btn-salin:hover { background: #0d9488; }
        .btn-salin svg { width: 14px; height: 14px; }

        /* ── Certificate Card ────────────────────────────── */
        .cert-card {
            background: white; border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            border: 1px solid #f3f4f6; overflow: hidden;
            margin-bottom: 32px;
        }
        .cert-bar { height: 8px; background: linear-gradient(90deg, #2dd4bf, #06b6d4, #14b8a6); }
        .cert-body { padding: 48px 64px; }
        .cert-logo { display: flex; flex-direction: column; align-items: center; margin-bottom: 32px; }
        .cert-logo-icon {
            width: 56px; height: 56px; border-radius: 16px;
            background: #14b8a6; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(20,184,166,0.3); margin-bottom: 10px;
        }
        .cert-logo-icon svg { width: 32px; height: 32px; color: white; }
        .cert-school { font-size: 20px; font-weight: 900; color: #111827; letter-spacing: -0.02em; }

        .cert-title-section { text-align: center; margin-bottom: 32px; }
        .cert-title-text {
            font-size: 11px; font-weight: 900; letter-spacing: 0.3em;
            color: #14b8a6; text-transform: uppercase; margin-bottom: 10px;
        }
        .cert-title-line {
            width: 80px; height: 2px;
            background: linear-gradient(90deg, transparent, #14b8a6, transparent);
            margin: 0 auto;
        }

        .cert-recipient { text-align: center; margin-bottom: 32px; }
        .cert-recipient-label {
            font-size: 11px; color: #9ca3af; font-weight: 500;
            letter-spacing: 0.05em; margin-bottom: 8px;
        }
        .cert-name {
            font-size: 40px; font-weight: 900; color: #111827;
            letter-spacing: 0.08em; text-transform: uppercase; line-height: 1.1;
        }

        .cert-program { text-align: center; margin-bottom: 40px; }
        .cert-program-label {
            font-size: 11px; color: #9ca3af; font-weight: 500;
            letter-spacing: 0.05em; margin-bottom: 6px;
        }
        .cert-program-name { font-size: 20px; font-weight: 900; color: #14b8a6; }

        .cert-stats {
            display: flex; align-items: center; justify-content: center;
            gap: 36px; margin-bottom: 40px;
        }
        .cert-stat { text-align: center; }
        .cert-stat-label { font-size: 11px; color: #9ca3af; font-weight: 500; margin-bottom: 4px; }
        .cert-stat-value { font-size: 14px; font-weight: 700; color: #374151; }
        .cert-stat-stars { display: flex; gap: 2px; margin-top: 4px; justify-content: center; }
        .cert-stat-stars svg { width: 13px; height: 13px; }
        .cert-divider { width: 1px; height: 32px; background: #e5e7eb; }

        .cert-footer {
            border-top: 1px solid #f3f4f6; padding-top: 28px;
            display: flex; align-items: flex-end; justify-content: space-between;
        }
        .cert-footer-left { }
        .cert-footer-left-label { font-size: 11px; color: #9ca3af; font-weight: 500; margin-bottom: 4px; }
        .cert-footer-left-date { font-size: 14px; font-weight: 700; color: #374151; }
        .cert-footer-left-nomor { font-family: monospace; font-size: 11px; color: #9ca3af; margin-top: 4px; }

        .cert-footer-sign { text-align: center; }
        .cert-footer-sign-name { font-size: 16px; font-weight: 900; font-style: italic; color: #374151; margin-bottom: 6px; }
        .cert-footer-sign-divider { border-top: 1px solid #d1d5db; padding-top: 6px; }
        .cert-footer-sign-title { font-size: 11px; color: #9ca3af; }

        .cert-footer-seal {
            width: 64px; height: 64px; border-radius: 50%;
            background: #f0fdfa; border: 2px solid #99f6e4;
            display: flex; align-items: center; justify-content: center;
        }
        .cert-footer-seal svg { width: 32px; height: 32px; color: #14b8a6; }

        /* ── Empty state ─────────────────────────────────── */
        .empty-state {
            background: white; border-radius: 20px;
            border: 1px solid #e5e7eb; text-align: center; padding: 80px 40px;
        }
        .empty-icon {
            width: 64px; height: 64px; border-radius: 16px; background: #f3f4f6;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .empty-icon svg { width: 32px; height: 32px; color: #9ca3af; }
        .empty-title { font-size: 18px; font-weight: 700; color: #374151; margin-bottom: 6px; }
        .empty-sub { font-size: 14px; color: #9ca3af; }

        /* ── Print styles ────────────────────────────────── */
        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            body { background: white !important; margin: 0; padding: 0; }
            .portal-sidebar,
            .portal-topbar,
            .cert-actions,
            .no-print { display: none !important; }
            .portal-main { margin-left: 0 !important; }
            .portal-content { padding: 0 !important; }
            .cert-card {
                box-shadow: none !important;
                border: 1.5px solid #e5e7eb !important;
                border-radius: 12px !important;
                margin-bottom: 0 !important;
                page-break-inside: avoid;
            }
            .cert-body { padding: 32px 48px !important; }
            .cert-name { font-size: 32px !important; }
        }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════ --}}
{{-- SIDEBAR                                --}}
{{-- ═══════════════════════════════════════ --}}
<aside class="portal-sidebar">
    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <svg style="width:20px;height:20px;color:white;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <div class="sidebar-logo-text">RoboNesia</div>
            <div class="sidebar-logo-sub">Academy</div>
        </div>
    </div>

    {{-- Profile card --}}
    @auth
    @php
        $authUser = auth()->user();
        $initials = strtoupper(substr($authUser->nama_lengkap ?? $authUser->name ?? 'U', 0, 1));
        $kelasAktif = $authUser->siswa?->enrollmentKelas()
            ->where('status', 'Aktif')
            ->with('kelas.batch.program')
            ->first();
        $namaKelas = $kelasAktif?->kelas?->batch?->program?->nama_program
            ?? $kelasAktif?->kelas?->nama_kelas
            ?? 'Siswa';
        $statusBadge = $kelasAktif ? $namaKelas . ' · Aktif' : 'Siswa';
    @endphp
    <div class="sidebar-profile">
        <div class="sidebar-avatar">{{ $initials }}</div>
        <div>
            <div class="sidebar-profile-name">{{ $authUser->nama_lengkap ?? $authUser->name }}</div>
            <div class="sidebar-profile-badge">{{ $statusBadge }}</div>
        </div>
    </div>
    @endauth

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <a href="{{ url('/admin') }}" class="sidebar-nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>
        <a href="{{ url('/admin') }}" class="sidebar-nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Kursus Saya
        </a>
        <a href="{{ url('/admin') }}" class="sidebar-nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Jadwal
        </a>
        <a href="{{ url('/admin') }}" class="sidebar-nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Progress &amp; Nilai
        </a>
        <a href="{{ route('sertifikat.saya') }}" class="sidebar-nav-item active">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Sertifikat
        </a>
        <a href="{{ url('/admin') }}" class="sidebar-nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Notifikasi
        </a>
        <a href="{{ url('/admin/profile') }}" class="sidebar-nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil Saya
        </a>
    </nav>

    {{-- Logout --}}
    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- ═══════════════════════════════════════ --}}
{{-- MAIN CONTENT                           --}}
{{-- ═══════════════════════════════════════ --}}
<div class="portal-main">

    {{-- Top bar --}}
    <div class="portal-topbar no-print">
        <span class="topbar-title">Sertifikat</span>
        <div class="topbar-right">
            <div class="topbar-bell">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            @auth
            <div class="topbar-user">
                <div class="topbar-user-avatar">
                    {{ strtoupper(substr(auth()->user()->nama_lengkap ?? auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                {{ auth()->user()->nama_lengkap ?? auth()->user()->name }}
            </div>
            @endauth
        </div>
    </div>

    {{-- Content --}}
    <div class="portal-content">

        @if($sertifikats->isEmpty())
        {{-- Empty state --}}
        <div class="empty-state">
            <div class="empty-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="empty-title">Belum ada sertifikat</div>
            <div class="empty-sub">Selesaikan program untuk mendapatkan sertifikat dari akademi.</div>
        </div>
        @endif

        @foreach($sertifikats as $s)
        @php
            $progress    = $s->siswa?->progressAkademik()->where('kelas_id', $s->kelas_id)->first();
            $nilaiAkhir  = $progress?->rata_nilai_tugas;
            $kehadiran   = $progress?->persentase_kehadiran;
            $bintang     = $nilaiAkhir ? min(5, round($nilaiAkhir / 20)) : 0;
            $totalSesi   = $s->kelas?->sesiLive?->count() ?? 0;
            $namaProgram = $s->kelas?->batch?->program?->nama_program ?? $s->kelas?->nama_kelas ?? 'Program Robotika';
            $namaSiswa   = strtoupper($s->siswa?->user?->nama_lengkap ?? '');
            $namaPenerbit = $s->penerbit?->nama_lengkap ?? 'Ahmad Fauzi';
        @endphp

        {{-- Actions bar --}}
        <div class="cert-actions no-print">
            <div class="cert-nomor">
                Sertifikat <span>{{ $s->nomor_sertifikat }}</span>
            </div>
            <div style="display:flex;gap:8px;">
                <button onclick="window.print()" class="btn-cetak">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak
                </button>
                @if($s->verified_url)
                <button onclick="copyLink('{{ $s->verified_url }}')" class="btn-salin">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Salin Link Verifikasi
                </button>
                @endif
            </div>
        </div>

        {{-- Certificate Card --}}
        <div class="cert-card">
            <div class="cert-bar"></div>
            <div class="cert-body">

                {{-- Logo & School name --}}
                <div class="cert-logo">
                    <div class="cert-logo-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="cert-school">RoboNesia Academy</div>
                </div>

                {{-- Title --}}
                <div class="cert-title-section">
                    <div class="cert-title-text">Sertifikat Penyelesaian Program</div>
                    <div class="cert-title-line"></div>
                </div>

                {{-- Recipient --}}
                <div class="cert-recipient">
                    <div class="cert-recipient-label">Diberikan kepada:</div>
                    <div class="cert-name">{{ $namaSiswa ?: 'NAMA SISWA' }}</div>
                </div>

                {{-- Program --}}
                <div class="cert-program">
                    <div class="cert-program-label">Telah menyelesaikan program:</div>
                    <div class="cert-program-name">{{ $namaProgram }}</div>
                </div>

                {{-- Stats --}}
                <div class="cert-stats">
                    @if($totalSesi > 0)
                    <div class="cert-stat">
                        <div class="cert-stat-label">Durasi</div>
                        <div class="cert-stat-value">{{ $totalSesi }} Sesi</div>
                    </div>
                    <div class="cert-divider"></div>
                    @endif

                    @if($nilaiAkhir)
                    <div class="cert-stat">
                        <div class="cert-stat-label">Nilai Akhir</div>
                        <div class="cert-stat-value">{{ number_format($nilaiAkhir, 0) }}/100</div>
                        <div class="cert-stat-stars">
                            @for($i = 1; $i <= 5; $i++)
                            <svg fill="{{ $i <= $bintang ? '#facc15' : '#e5e7eb' }}" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                    </div>
                    <div class="cert-divider"></div>
                    @endif

                    @if($kehadiran)
                    <div class="cert-stat">
                        <div class="cert-stat-label">Kehadiran</div>
                        <div class="cert-stat-value">{{ number_format($kehadiran, 1) }}%</div>
                    </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="cert-footer">
                    <div class="cert-footer-left">
                        <div class="cert-footer-left-label">Diterbitkan</div>
                        <div class="cert-footer-left-date">
                            {{ \Carbon\Carbon::parse($s->tanggal_terbit)->translatedFormat('d F Y') }}
                        </div>
                        <div class="cert-footer-left-nomor">{{ $s->nomor_sertifikat }}</div>
                    </div>

                    <div class="cert-footer-sign">
                        <div class="cert-footer-sign-name">{{ $namaPenerbit }}</div>
                        <div class="cert-footer-sign-divider">
                            <div class="cert-footer-sign-title">{{ $namaPenerbit }} · Instruktur</div>
                        </div>
                    </div>

                    <div class="cert-footer-seal">
                        @if($s->qr_code)
                            <img src="{{ $s->qr_code }}" alt="QR" style="width:44px;height:44px;">
                        @else
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        @endforeach

    </div>{{-- /portal-content --}}
</div>{{-- /portal-main --}}

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url)
        .then(() => alert('✅ Link verifikasi berhasil disalin!'))
        .catch(() => prompt('Salin link ini:', url));
}
</script>
</body>
</html>
