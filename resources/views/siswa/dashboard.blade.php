<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — RoboNesia Academy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            display: flex;
            min-height: 100vh;
            color: #1e293b;
        }

        /* ═══════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════ */
        .sidebar {
            width: 240px;
            min-width: 240px;
            background: #0f1923;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            overflow-y: auto;
        }

        /* Logo */
        .sidebar-logo {
            padding: 20px 20px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .logo-icon {
            width: 36px; height: 36px;
            background: #14b8a6;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .logo-icon svg { width: 20px; height: 20px; color: white; }
        .logo-text { color: white; font-weight: 800; font-size: 15px; line-height: 1.1; }
        .logo-sub  { color: rgba(255,255,255,0.4); font-size: 10px; font-weight: 500; }

        /* Profile */
        .sidebar-profile {
            padding: 16px 16px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .profile-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #14b8a6, #0ea5e9);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 15px; color: white;
            flex-shrink: 0; border: 2px solid rgba(255,255,255,0.15);
        }
        .profile-name { color: white; font-weight: 700; font-size: 13px; }
        .profile-role { color: rgba(255,255,255,0.45); font-size: 11px; font-weight: 500; }
        .profile-status { display: flex; align-items: center; gap: 4px; margin-top: 2px; }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; background: #22c55e; }
        .status-text { color: #22c55e; font-size: 10px; font-weight: 600; }

        /* Nav */
        .sidebar-nav { flex: 1; padding: 12px 10px 4px; }
        .nav-section-label {
            color: rgba(255,255,255,0.3);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 8px 10px 6px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            margin-bottom: 1px;
            color: rgba(255,255,255,0.55);
            font-size: 13px; font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .nav-item:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }
        .nav-item.active { background: rgba(20,184,166,0.18); color: #2dd4bf; font-weight: 700; }
        .nav-item.active .nav-icon { color: #2dd4bf; }
        .nav-icon { width: 17px; height: 17px; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto;
            background: #ef4444; color: white;
            font-size: 10px; font-weight: 700;
            border-radius: 100px; padding: 1px 6px;
        }

        /* Logout */
        .sidebar-footer {
            padding: 10px 10px 16px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .logout-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px; width: 100%;
            color: #f87171; font-size: 13px; font-weight: 600;
            background: none; border: none; cursor: pointer;
            text-decoration: none; transition: background 0.15s;
        }
        .logout-btn:hover { background: rgba(239,68,68,0.1); }
        .logout-btn svg { width: 17px; height: 17px; }

        /* ═══════════════════════════════════
           MAIN
        ═══════════════════════════════════ */
        .main {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Topbar ── */
        .topbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 28px;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 20px; font-weight: 800; color: #0f172a; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .search-bar {
            display: flex; align-items: center; gap: 8px;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 8px; padding: 7px 14px;
            font-size: 13px; color: #94a3b8; min-width: 200px;
        }
        .search-bar svg { width: 15px; height: 15px; flex-shrink: 0; }
        .topbar-icon-btn {
            position: relative; width: 38px; height: 38px;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            cursor: pointer; text-decoration: none;
        }
        .topbar-icon-btn svg { width: 18px; height: 18px; color: #64748b; }
        .notif-badge {
            position: absolute; top: -2px; right: -2px;
            background: #ef4444; color: white;
            font-size: 9px; font-weight: 800;
            border-radius: 100px; padding: 1px 5px;
            border: 1.5px solid white;
        }
        .topbar-user { display: flex; align-items: center; gap: 8px; }
        .topbar-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #14b8a6, #0ea5e9);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 800; color: white;
        }
        .topbar-user-name { font-size: 13px; font-weight: 600; color: #334155; }

        /* ── Content ── */
        .content { padding: 28px; flex: 1; }

        /* Greeting */
        .greeting { margin-bottom: 24px; display: flex; align-items: flex-start; justify-content: space-between; }
        .greeting-text h2 { font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .greeting-text p  { font-size: 13px; color: #94a3b8; font-weight: 500; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 18px; background: #14b8a6; color: white;
            border: none; border-radius: 10px; font-size: 13px; font-weight: 700;
            cursor: pointer; text-decoration: none;
            box-shadow: 0 4px 14px rgba(20,184,166,0.35);
            transition: all 0.15s;
        }
        .btn-primary:hover { background: #0d9488; transform: translateY(-1px); }
        .btn-primary svg { width: 16px; height: 16px; }

        /* ── Stat Cards ── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: white; border-radius: 16px; padding: 20px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
            border: 1px solid #f1f5f9;
            display: flex; flex-direction: column; gap: 12px;
        }
        .stat-header { display: flex; align-items: center; justify-content: space-between; }
        .stat-icon {
            width: 42px; height: 42px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-icon svg { width: 22px; height: 22px; }
        .stat-icon.teal   { background: #f0fdfa; color: #14b8a6; }
        .stat-icon.blue   { background: #eff6ff; color: #3b82f6; }
        .stat-icon.amber  { background: #fffbeb; color: #f59e0b; }
        .stat-icon.purple { background: #faf5ff; color: #a855f7; }
        .stat-number { font-size: 28px; font-weight: 900; color: #0f172a; }
        .stat-label  { font-size: 13px; font-weight: 600; color: #64748b; }
        .stat-sub    { font-size: 11px; color: #94a3b8; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
        .stat-sub.green { color: #22c55e; }
        .stat-sub.amber { color: #f59e0b; }

        /* ── Middle grid ── */
        .middle-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }

        .card {
            background: white; border-radius: 16px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }
        .card-header {
            padding: 18px 20px 14px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #f8fafc;
        }
        .card-title { font-size: 15px; font-weight: 800; color: #0f172a; }
        .card-link  { font-size: 12px; font-weight: 600; color: #14b8a6; text-decoration: none; }
        .card-link:hover { text-decoration: underline; }

        /* Sesi card */
        .sesi-aktif {
            padding: 16px 20px;
            background: linear-gradient(135deg, #f0fdfa, #e0f7fa);
            border-bottom: 1px solid #d1faf4;
        }
        .sesi-badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 10px; font-weight: 800; letter-spacing: 0.08em;
            color: #dc2626; text-transform: uppercase; margin-bottom: 8px;
        }
        .sesi-badge-dot { width: 7px; height: 7px; border-radius: 50%; background: #dc2626; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:.4; } }
        .sesi-name  { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .sesi-class { font-size: 12px; font-weight: 600; color: #14b8a6; margin-bottom: 10px; }
        .sesi-meta  { display: flex; align-items: center; gap: 16px; margin-bottom: 14px; }
        .sesi-meta-item { display: flex; align-items: center; gap: 5px; font-size: 12px; color: #64748b; font-weight: 500; }
        .sesi-meta-item svg { width: 14px; height: 14px; }
        .sesi-jam { font-size: 12px; font-weight: 700; color: #374151; margin-left: auto; }
        .sesi-actions { display: flex; gap: 8px; }
        .btn-sm {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 7px 14px; border-radius: 8px;
            font-size: 12px; font-weight: 700; cursor: pointer;
            text-decoration: none; transition: all 0.15s; border: none;
        }
        .btn-teal   { background: #14b8a6; color: white; }
        .btn-teal:hover { background: #0d9488; }
        .btn-outline { background: white; color: #374151; border: 1px solid #d1d5db; }
        .btn-outline:hover { background: #f9fafb; }
        .btn-sm svg { width: 13px; height: 13px; }

        /* Next sessions */
        .sesi-next-list { padding: 0 20px; }
        .sesi-next-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 0; border-bottom: 1px solid #f8fafc;
        }
        .sesi-next-item:last-child { border-bottom: none; }
        .sesi-date-badge {
            width: 44px; flex-shrink: 0; text-align: center;
            background: #f8fafc; border-radius: 10px; padding: 6px 4px;
        }
        .sesi-date-month { font-size: 9px; font-weight: 700; color: #14b8a6; text-transform: uppercase; }
        .sesi-date-day   { font-size: 18px; font-weight: 900; color: #0f172a; line-height: 1; }
        .sesi-next-name  { font-size: 13px; font-weight: 700; color: #0f172a; }
        .sesi-next-sub   { font-size: 11px; color: #94a3b8; margin-top: 2px; }
        .sesi-empty { padding: 32px 20px; text-align: center; color: #94a3b8; font-size: 13px; }

        /* Progress card */
        .progress-list { padding: 0 20px; }
        .progress-item {
            padding: 14px 0;
            border-bottom: 1px solid #f8fafc;
        }
        .progress-item:last-child { border-bottom: none; }
        .progress-item-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
        .progress-item-name  { font-size: 13px; font-weight: 700; color: #0f172a; }
        .progress-item-pct   { font-size: 13px; font-weight: 800; color: #14b8a6; }
        .progress-item-sub   { font-size: 11px; color: #94a3b8; margin-bottom: 8px; }
        .progress-bar-bg { height: 6px; background: #f1f5f9; border-radius: 100px; overflow: hidden; }
        .progress-bar-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, #14b8a6, #06b6d4); }
        .progress-bar-fill.amber { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .progress-bar-fill.red   { background: linear-gradient(90deg, #ef4444, #f87171); }
        .progress-empty { padding: 32px 20px; text-align: center; color: #94a3b8; font-size: 13px; }

        /* ── Bottom table ── */
        .table-card { background: white; border-radius: 16px; box-shadow: 0 1px 8px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; overflow: hidden; }
        .table-header {
            padding: 18px 24px 14px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-title { font-size: 15px; font-weight: 800; color: #0f172a; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead tr { background: #f8fafc; }
        th {
            padding: 12px 20px; text-align: left;
            font-size: 11px; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.07em;
            border-bottom: 1px solid #f1f5f9;
        }
        td { padding: 14px 20px; color: #374151; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafafa; }
        .kelas-name  { font-weight: 700; color: #0f172a; font-size: 13px; }
        .kelas-since { font-size: 11px; color: #94a3b8; margin-top: 2px; }
        .program-dot { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; display: inline-block; margin-right: 5px; }
        .status-badge {
            display: inline-flex; align-items: center;
            padding: 4px 12px; border-radius: 100px;
            font-size: 11px; font-weight: 700;
        }
        .status-aktif   { background: #f0fdf4; color: #16a34a; }
        .status-selesai { background: #eff6ff; color: #2563eb; }
        .status-remedial{ background: #fef3c7; color: #d97706; }
        .progress-mini { width: 80px; }
        .progress-mini-bar { height: 5px; background: #f1f5f9; border-radius: 100px; overflow: hidden; margin-top: 4px; }
        .progress-mini-fill { height: 100%; border-radius: 100px; background: #14b8a6; }
        .action-btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 12px; border-radius: 7px; font-size: 11px; font-weight: 700;
            text-decoration: none; border: 1px solid #e2e8f0; color: #374151;
            background: white; cursor: pointer; transition: all 0.1s;
        }
        .action-btn:hover { background: #f8fafc; }
        .table-empty { text-align: center; padding: 48px; color: #94a3b8; font-size: 14px; }

        /* Sertifikat mini badge */
        .sert-badge {
            display: inline-flex; align-items: center; gap: 4px;
            background: #f0fdfa; color: #0d9488; border: 1px solid #99f6e4;
            border-radius: 100px; padding: 3px 10px; font-size: 11px; font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════
     SIDEBAR
═══════════════════════════════════ --}}
<aside class="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="logo-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <div class="logo-text">RoboNesia</div>
            <div class="logo-sub">Academy</div>
        </div>
    </div>

    {{-- Profile --}}
    @auth
    @php
        $authUser = auth()->user();
        $initials = strtoupper(substr($authUser->nama_lengkap ?? $authUser->name ?? 'U', 0, 1));
        $namaUser = $authUser->nama_lengkap ?? $authUser->name;
        $firstName = explode(' ', $namaUser)[0];
    @endphp
    <div class="sidebar-profile">
        <div class="profile-avatar">{{ $initials }}</div>
        <div>
            <div class="profile-name">{{ $namaUser }}</div>
            <div class="profile-status">
                <div class="status-dot"></div>
                <span class="status-text">Online</span>
            </div>
        </div>
    </div>
    @endauth

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu Utama</div>

        <a href="{{ route('siswa.dashboard') }}" class="nav-item active">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('siswa.profil.show') }}" class="nav-item">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil Saya
        </a>

        <a href="{{ route('sertifikat.saya') }}" class="nav-item">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Sertifikat Saya
            @if($stats['total_sertifikat'] > 0)
            <span style="margin-left:auto; background:#14b8a6; color:white; font-size:10px; font-weight:700; border-radius:100px; padding:1px 7px;">{{ $stats['total_sertifikat'] }}</span>
            @endif
        </a>

        <a href="{{ route('peminjaman.index') }}" class="nav-item">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Peminjaman Aset
        </a>

        <div class="nav-section-label" style="margin-top:8px;">Komunikasi</div>

        <a href="{{ route('forum.index') }}" class="nav-item">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Forum Diskusi
        </a>

        <a href="{{ route('keluhan.create') }}" class="nav-item">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
            Kirim Keluhan
        </a>

        <a href="{{ route('keluhan.saya') }}" class="nav-item">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Keluhan
        </a>

        <div class="nav-section-label" style="margin-top:8px;">Akun</div>

        <a href="{{ route('profile.edit') }}" class="nav-item">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Pengaturan
        </a>
    </nav>

    {{-- Logout --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- ═══════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════ --}}
<div class="main">

    {{-- Topbar --}}
    <header class="topbar">
        <span class="topbar-title">Dashboard</span>
        <div class="topbar-right">
            <div class="search-bar">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari...
            </div>
            <a href="{{ url('/admin') }}" class="topbar-icon-btn">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if(auth()->user()?->notifikasi?->whereNull('dibaca_at')->count() > 0)
                <span class="notif-badge">{{ auth()->user()->notifikasi->whereNull('dibaca_at')->count() }}</span>
                @endif
            </a>
            @auth
            <div class="topbar-user">
                <div class="topbar-avatar">{{ $initials }}</div>
                <span class="topbar-user-name">{{ $namaUser }}</span>
            </div>
            @endauth
        </div>
    </header>

    {{-- Content --}}
    <div class="content">

        {{-- Greeting --}}
        <div class="greeting">
            <div class="greeting-text">
                <h2>{{ $greeting }}, {{ $firstName ?? $namaUser }}! ☀️</h2>
                <p>{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <a href="{{ route('sertifikat.saya') }}" class="btn-primary">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Lihat Sertifikat
            </a>
        </div>

        {{-- Stat Cards --}}
        <div class="stats-grid">

            {{-- Kelas Aktif --}}
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Kelas Aktif</div>
                        <div class="stat-number">{{ $stats['kelas_aktif'] }}</div>
                    </div>
                    <div class="stat-icon teal">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-sub green">
                    <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $stats['kelas_selesai'] }} kelas selesai
                </div>
            </div>

            {{-- Kehadiran --}}
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Rata Kehadiran</div>
                        <div class="stat-number">{{ $stats['rata_kehadiran'] }}%</div>
                    </div>
                    <div class="stat-icon blue">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-sub {{ $stats['rata_kehadiran'] >= 75 ? 'green' : 'amber' }}">
                    @if($stats['rata_kehadiran'] >= 75)
                    ✓ Memenuhi syarat (≥75%)
                    @else
                    ⚠ Di bawah syarat minimum
                    @endif
                </div>
            </div>

            {{-- Nilai --}}
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Rata Nilai</div>
                        <div class="stat-number">{{ $stats['rata_nilai'] }}</div>
                    </div>
                    <div class="stat-icon amber">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-sub {{ $stats['rata_nilai'] >= 70 ? 'green' : 'amber' }}">
                    @if($stats['rata_nilai'] >= 70)
                    ✓ Memenuhi syarat (≥70)
                    @else
                    ⚠ Di bawah syarat minimum
                    @endif
                </div>
            </div>

            {{-- Sertifikat --}}
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Sertifikat</div>
                        <div class="stat-number">{{ $stats['total_sertifikat'] }}</div>
                    </div>
                    <div class="stat-icon purple">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-sub">
                    @if($stats['total_sertifikat'] > 0)
                    <a href="{{ route('sertifikat.saya') }}" style="color:#14b8a6; text-decoration:none; font-weight:600;">Lihat semua →</a>
                    @else
                    Belum ada sertifikat
                    @endif
                </div>
            </div>
        </div>

        {{-- Middle Grid --}}
        <div class="middle-grid">

            {{-- Kiri: Sesi Hari Ini --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Sesi Berlangsung Hari Ini</span>
                    <a href="{{ url('/admin') }}" class="card-link">Lihat Semua</a>
                </div>

                @if($sesiHariIni->isEmpty())
                    <div class="sesi-empty">
                        <svg style="width:36px;height:36px;color:#cbd5e1;margin:0 auto 8px;display:block;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Tidak ada sesi hari ini
                    </div>
                @else
                    @php $sesi = $sesiHariIni->first(); @endphp
                    <div class="sesi-aktif">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
                            <div>
                                <div class="sesi-badge">
                                    <div class="sesi-badge-dot"></div>
                                    Sedang Berlangsung
                                </div>
                                <div class="sesi-name">{{ $sesi->judul_sesi ?? 'Sesi Kelas' }}</div>
                                <div class="sesi-class">{{ $sesi->kelas?->batch?->program?->nama_program ?? $sesi->kelas?->nama_kelas }}</div>
                            </div>
                            <div class="sesi-jam">
                                {{ $sesi->jam_mulai ? \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') : '' }}
                                @if($sesi->jam_selesai) — {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }} WIB @endif
                            </div>
                        </div>
                        <div class="sesi-meta">
                            <div class="sesi-meta-item">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                {{ $sesi->platform ?? 'Online' }}
                            </div>
                        </div>
                        <div class="sesi-actions">
                            @if($sesi->link_akses)
                            <a href="{{ $sesi->link_akses }}" target="_blank" class="btn-sm btn-teal">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Buka Zoom
                            </a>
                            @endif
                            <a href="{{ url('/admin') }}" class="btn-sm btn-outline">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Next sessions --}}
                @if($sesiBerikutnya->isNotEmpty())
                <div class="sesi-next-list">
                    @foreach($sesiBerikutnya as $next)
                    @php $tgl = \Carbon\Carbon::parse($next->tanggal); @endphp
                    <div class="sesi-next-item">
                        <div class="sesi-date-badge">
                            <div class="sesi-date-month">{{ $tgl->format('M') }}</div>
                            <div class="sesi-date-day">{{ $tgl->format('d') }}</div>
                        </div>
                        <div style="flex:1;">
                            <div class="sesi-next-name">{{ $next->judul_sesi ?? 'Sesi Kelas' }}</div>
                            <div class="sesi-next-sub">
                                {{ $next->kelas?->nama_kelas }} ·
                                {{ $tgl->translatedFormat('l') }},
                                {{ $next->jam_mulai ? \Carbon\Carbon::parse($next->jam_mulai)->format('H:i') : '' }} WIB ·
                                {{ $next->platform ?? 'Online' }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @elseif($sesiHariIni->isEmpty())
                    {{-- already showed empty state above --}}
                @else
                    <div class="sesi-empty" style="padding:16px 20px; font-size:12px;">Tidak ada sesi berikutnya</div>
                @endif
            </div>

            {{-- Kanan: Progress Belajar --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Progress Belajar</span>
                    <a href="{{ route('sertifikat.saya') }}" class="card-link">Lihat Sertifikat</a>
                </div>

                @if($progressList->isEmpty())
                    <div class="progress-empty">
                        <svg style="width:36px;height:36px;color:#cbd5e1;margin:0 auto 8px;display:block;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Belum ada data progress
                    </div>
                @else
                <div class="progress-list">
                    @foreach($progressList->take(4) as $prog)
                    @php
                        $pct  = $prog->persentase_penyelesaian ?? $prog->persentase_kehadiran ?? 0;
                        $kls  = $kelasAktif->firstWhere('kelas_id', $prog->kelas_id)
                             ?? $kelasSelesai->firstWhere('kelas_id', $prog->kelas_id);
                        $nm   = $kls?->kelas?->batch?->program?->nama_program ?? $kls?->kelas?->nama_kelas ?? 'Kelas';
                        $color = $pct >= 75 ? '' : ($pct >= 50 ? 'amber' : 'red');
                    @endphp
                    <div class="progress-item">
                        <div class="progress-item-header">
                            <span class="progress-item-name">{{ $nm }}</span>
                            <span class="progress-item-pct">{{ number_format($pct, 0) }}%</span>
                        </div>
                        <div class="progress-item-sub">
                            Kehadiran: {{ number_format($prog->persentase_kehadiran ?? 0, 1) }}% ·
                            Nilai: {{ number_format($prog->rata_nilai_tugas ?? 0, 1) }} ·
                            <span style="color:{{ $prog->status === 'Lulus' ? '#22c55e' : ($prog->status === 'Remedial' ? '#ef4444' : '#f59e0b') }}; font-weight:600;">{{ $prog->status ?? 'Aktif' }}</span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill {{ $color }}" style="width: {{ min(100, $pct) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Bottom: Kelas yang Diikuti --}}
        <div class="table-card">
            <div class="table-header">
                <span class="table-title">Kelas yang Diikuti</span>
                <span style="font-size:12px;color:#94a3b8;">Total {{ $enrollments->count() }} kelas</span>
            </div>

            @if($enrollments->isEmpty())
                <div class="table-empty">Belum terdaftar di kelas manapun.</div>
            @else
            <table>
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Program</th>
                        <th>Instruktur</th>
                        <th>Sesi</th>
                        <th>Kehadiran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $enroll)
                    @php
                        $kls     = $enroll->kelas;
                        $prog    = $progressList->firstWhere('kelas_id', $kls?->id);
                        $kehadiran = $prog?->persentase_kehadiran ?? 0;
                        $sesiTotal = $kls?->sesiLive?->count() ?? 0;
                        $statusClass = match($enroll->status) {
                            'Aktif'   => 'status-aktif',
                            'Selesai' => 'status-selesai',
                            default   => 'status-remedial'
                        };
                        $hasSert = $sertifikat->firstWhere('kelas_id', $kls?->id);
                    @endphp
                    <tr>
                        <td>
                            <div class="kelas-name">{{ $kls?->nama_kelas ?? '–' }}</div>
                            <div class="kelas-since">Sejak {{ \Carbon\Carbon::parse($enroll->tanggal_bergabung)->translatedFormat('M Y') }}</div>
                        </td>
                        <td>
                            @if($kls?->batch?->program)
                            <span class="program-dot"></span>
                            {{ $kls->batch->program->nama_program }}
                            @else
                            <span style="color:#94a3b8;">–</span>
                            @endif
                        </td>
                        <td style="color:#374151;">{{ $kls?->instruktur?->nama_lengkap ?? '–' }}</td>
                        <td style="font-weight:700;">{{ $sesiTotal }}</td>
                        <td>
                            <div class="progress-mini">
                                <span style="font-size:12px;font-weight:700;color:{{ $kehadiran >= 75 ? '#16a34a' : '#d97706' }};">{{ number_format($kehadiran, 1) }}%</span>
                                <div class="progress-mini-bar">
                                    <div class="progress-mini-fill" style="width:{{ min(100,$kehadiran) }}%; background: {{ $kehadiran >= 75 ? '#14b8a6' : '#f59e0b' }};"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge {{ $statusClass }}">{{ $enroll->status }}</span>
                        </td>
                        <td>
                            @if($hasSert)
                                <a href="{{ route('sertifikat.saya') }}" class="sert-badge">
                                    <svg style="width:11px;height:11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Sertifikat
                                </a>
                            @else
                                <a href="{{ url('/admin') }}" class="action-btn">
                                    <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>{{-- /content --}}
</div>{{-- /main --}}

</body>
</html>
