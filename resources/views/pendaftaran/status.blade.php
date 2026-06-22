<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pendaftaran</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f3f4f6;
            color: #1f2937;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: #111827;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 32px;
        }

        .navbar .brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
        }

        .navbar .brand span {
            color: #06b6d4;
        }

        .navbar .nav-back {
            color: #9ca3af;
            font-size: 0.875rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .navbar .nav-back:hover {
            color: white;
        }

        /* ── HEADER ── */
        .header {
            background: #111827;
            text-align: center;
            padding: 40px 20px 48px;
        }

        .header .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #374151;
            border-radius: 999px;
            padding: 5px 14px;
            color: #9ca3af;
            font-size: 0.8rem;
            margin-bottom: 18px;
        }

        .header h1 {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            color: #9ca3af;
            font-size: 0.95rem;
            max-width: 520px;
            margin: 0 auto;
        }

        /* ── MAIN CONTENT ── */
        .container {
            max-width: 680px;
            margin: -20px auto 40px;
            padding: 0 16px;
        }

        /* ── FORM CARD ── */
        .card {
            background: white;
            border-radius: 16px;
            padding: 28px 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        }

        /* Tab toggle */
        .tab-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .tab-btn {
            padding: 11px;
            background: white;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background 0.15s, color 0.15s;
        }

        .tab-btn.active {
            background: #f9fafb;
            color: #1f2937;
            font-weight: 600;
        }

        .tab-btn:first-child {
            border-right: 1px solid #e5e7eb;
        }

        .tab-dot {
            width: 8px;
            height: 8px;
            border: 2px solid currentColor;
            border-radius: 2px;
            display: inline-block;
            opacity: 0.5;
        }

        /* Form fields */
        .form-label {
            display: block;
            font-size: 0.85rem;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 13px 16px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 0.95rem;
            color: #111827;
            outline: none;
            transition: border-color 0.15s;
        }

        .form-input:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6,182,212,0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            margin-top: 14px;
            background: white;
            color: #1f2937;
            border: 1.5px solid #d1d5db;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s, border-color 0.15s;
        }

        .btn-primary:hover {
            background: #f9fafb;
            border-color: #06b6d4;
        }

        /* ── STATUS RESULT ── */
        .status-card {
            background: #e0f7fb;
            border-radius: 14px;
            padding: 20px 22px;
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 16px;
        }

        .status-avatar {
            width: 52px;
            height: 52px;
            background: #b2ebf2;
            border-radius: 12px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-avatar span {
            font-size: 1.4rem;
            color: #0e7490;
        }

        .status-badge {
            font-size: 0.72rem;
            font-weight: 700;
            color: #0e7490;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .status-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #111827;
        }

        .status-sub {
            font-size: 0.82rem;
            color: #6b7280;
            margin-top: 2px;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 16px;
        }

        .info-item {
            background: white;
            border-radius: 12px;
            padding: 16px 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        .info-item .info-label {
            font-size: 0.78rem;
            color: #9ca3af;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .info-item .info-label .dot {
            width: 7px;
            height: 7px;
            border: 1.5px solid #d1d5db;
            border-radius: 2px;
            display: inline-block;
        }

        .info-item .info-value {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
        }

        /* Admin note */
        .admin-note {
            background: #e0f7fb;
            border-left: 4px solid #06b6d4;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 20px;
        }

        .admin-note .note-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: #0e7490;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .admin-note p {
            font-size: 0.88rem;
            color: #374151;
            line-height: 1.55;
        }

        /* Timeline */
        .timeline-title {
            font-size: 0.92rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .timeline {
            list-style: none;
            position: relative;
            padding-left: 28px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 10px;
            bottom: 10px;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline li {
            position: relative;
            margin-bottom: 22px;
        }

        .timeline li:last-child {
            margin-bottom: 0;
        }

        .tl-dot {
            position: absolute;
            left: -24px;
            top: 3px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid #06b6d4;
            background: #06b6d4;
            z-index: 1;
        }

        .tl-dot.pending {
            background: white;
            border-color: #d1d5db;
        }

        .tl-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #111827;
        }

        .tl-time {
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 2px;
        }

        .tl-title.pending-text {
            color: #9ca3af;
        }

        /* Bottom buttons */
        .btn-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
        }

        .btn-outline {
            padding: 13px 16px;
            background: white;
            border: 1.5px solid #d1d5db;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-decoration: none;
            transition: border-color 0.15s;
        }

        .btn-outline:hover {
            border-color: #06b6d4;
            color: #0e7490;
        }

        /* Error message */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            padding: 14px 18px;
            color: #b91c1c;
            font-size: 0.88rem;
            margin-bottom: 16px;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            max-width: 680px;
            margin: 0 auto;
        }

        .footer .footer-left {
            font-size: 0.8rem;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .footer .footer-right a {
            font-size: 0.8rem;
            color: #06b6d4;
            text-decoration: none;
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">
    <div class="brand">Robo<span>Nesia</span></div>
    <a href="{{ url('/') }}" class="nav-back">
        Kembali ke beranda
    </a>
</nav>

{{-- HEADER --}}
<div class="header">
    <div class="badge">
        Portal peserta
    </div>
    <h1>Cek status pendaftaran</h1>
    <p>Masukkan nomor referensi atau email terdaftar untuk melihat perkembangan pendaftaran kamu.</p>
</div>

{{-- MAIN --}}
<div class="container">

    {{-- FORM CEK STATUS --}}
    <div class="card">
        <form action="{{ route('pendaftaran.cari') }}" method="POST">
            @csrf

            {{-- Tab toggle --}}
            <div class="tab-row">
                <button type="button" class="tab-btn active" onclick="switchTab('referensi', this)">
                    <span class="tab-dot"></span> Nomor referensi
                </button>
                <button type="button" class="tab-btn" onclick="switchTab('email', this)">
                    <span class="tab-dot"></span> Email
                </button>
            </div>

            {{-- Field Nomor Referensi --}}
            <div id="tab-referensi">
                <label class="form-label">Nomor referensi</label>
                <input
                    type="text"
                    name="keyword"
                    class="form-input"
                    placeholder="RBN-2026-00421"
                    value="{{ old('keyword') }}"
                >
            </div>

            {{-- Field Email --}}
            <div id="tab-email" style="display:none;">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-input"
                    placeholder="nama@email.com"
                >
            </div>

            <button type="submit" class="btn-primary">
                Cek status sekarang
            </button>
        </form>
    </div>

    {{-- ERROR --}}
    @if(session('error'))
    <div class="alert-error">
        {{ session('error') }}
    </div>
    @endif

    {{-- HASIL PENDAFTARAN --}}
@if(isset($pendaftaran) && $pendaftaran)

    {{-- Status Card --}}
    <div class="status-card">
        <div class="status-avatar">
            
        </div>
        <div>
            <div class="status-badge">
                @if($pendaftaran->status == 'diproses') SEDANG DIPROSES
                @elseif($pendaftaran->status == 'dikonfirmasi') DIKONFIRMASI
                @elseif($pendaftaran->status == 'ditolak') DITOLAK
                @else {{ strtoupper($pendaftaran->status) }}
                @endif
            </div>
            <div class="status-name">{{ $pendaftaran->calonPeserta->nama_lengkap }}</div>
            <div class="status-sub">Tim kami sedang meninjau pendaftaranmu</div>
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label"><span class="dot"></span> Program</div>
            <div class="info-value">{{ $pendaftaran->program->nama_program }}</div>
        </div>
        <div class="info-item">
            <div class="info-label"><span class="dot"></span> Level</div>
            <div class="info-value">{{ $pendaftaran->program->level ?? 'Pemula' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label"><span class="dot"></span> Biaya</div>
            <div class="info-value">Rp {{ number_format($pendaftaran->program->biaya ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label"><span class="dot"></span> Tanggal daftar</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($pendaftaran->created_at)->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    {{-- Catatan Admin --}}
    @if($pendaftaran->catatan_admin)
    <div class="admin-note">
        <div class="note-label">
            CATATAN DARI ADMIN
        </div>
        <p>{{ $pendaftaran->catatan_admin }}</p>
    </div>
    @endif

    {{-- Timeline --}}
    <div class="card">
        <div class="timeline-title">
            Riwayat proses
        </div>
        <ul class="timeline">
            <li>
                <div class="tl-dot"></div>
                <div class="tl-title">Pendaftaran diterima</div>
                <div class="tl-time">{{ \Carbon\Carbon::parse($pendaftaran->created_at)->translatedFormat('d M Y, H.i') }}</div>
            </li>
            <li>
                <div class="tl-dot {{ in_array($pendaftaran->status, ['verifikasi','diproses','dikonfirmasi']) ? '' : 'pending' }}"></div>
                <div class="tl-title {{ in_array($pendaftaran->status, ['verifikasi','diproses','dikonfirmasi']) ? '' : 'pending-text' }}">Dokumen diverifikasi</div>
                <div class="tl-time">
                    @if($pendaftaran->tanggal_verifikasi)
                        {{ \Carbon\Carbon::parse($pendaftaran->tanggal_verifikasi)->translatedFormat('d M Y, H.i') }}
                    @else
                        Dalam proses
                    @endif
                </div>
            </li>
            <li>
                <div class="tl-dot {{ in_array($pendaftaran->status, ['diproses','dikonfirmasi']) ? '' : 'pending' }}"></div>
                <div class="tl-title {{ in_array($pendaftaran->status, ['diproses','dikonfirmasi']) ? '' : 'pending-text' }}">Menunggu konfirmasi pembayaran</div>
                <div class="tl-time">
                    @if($pendaftaran->tanggal_bayar)
                        {{ \Carbon\Carbon::parse($pendaftaran->tanggal_bayar)->translatedFormat('d M Y, H.i') }}
                    @else
                        Dalam proses
                    @endif
                </div>
            </li>
            <li>
                <div class="tl-dot {{ $pendaftaran->status == 'dikonfirmasi' ? '' : 'pending' }}"></div>
                <div class="tl-title {{ $pendaftaran->status == 'dikonfirmasi' ? '' : 'pending-text' }}">Pendaftaran dikonfirmasi</div>
                <div class="tl-time">
                    @if($pendaftaran->status == 'dikonfirmasi' && $pendaftaran->tanggal_konfirmasi)
                        {{ \Carbon\Carbon::parse($pendaftaran->tanggal_konfirmasi)->translatedFormat('d M Y, H.i') }}
                    @else
                        Dalam proses
                    @endif
                </div>
            </li>
        </ul>

        {{-- Tombol Aksi --}}
        <div class="btn-row">
            <!--<a href="https://wa.me/6281234567890" class="btn-outline" target="_blank">
                🗓 Tanya admin ↗
            </a>//-->
            <a href="{{ url('/daftar') }}" class="btn-outline">
                Daftar program lain
            </a>
        </div>
    </div>

    @endif

</div>

{{-- FOOTER --}}
<div class="footer">
    <div class="footer-left">
        Data kamu aman dan terenkripsi
    </div>
    <div class="footer-right">
        <a href="#">Butuh bantuan? ↗</a>
    </div>
</div>

<script>
    function switchTab(tab, btn) {
        document.getElementById('tab-referensi').style.display = 'none';
        document.getElementById('tab-email').style.display = 'none';
        document.getElementById('tab-' + tab).style.display = 'block';

        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
</script>

</body>
</html>