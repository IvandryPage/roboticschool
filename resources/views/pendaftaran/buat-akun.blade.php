<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun — RoboNesia Academy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', Inter, sans-serif; }
        body { background: #f0f9ff; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 30px; }
        .card {
            width: 100%; max-width: 520px;
            background: #fff;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 8px 32px rgba(0,0,0,.08);
        }
        .logo { display: flex; align-items: center; gap: 10px; margin-bottom: 32px; }
        .logo-icon { width: 36px; height: 36px; background: #06b6d4; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .logo-icon svg { width: 20px; height: 20px; fill: white; }
        .logo-text { font-size: 18px; font-weight: 700; color: #0f172a; }
        .logo-text span { color: #06b6d4; }

        .step-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #ecfdf5; color: #059669;
            border: 1px solid #6ee7b7;
            font-size: 12px; font-weight: 600;
            padding: 4px 12px; border-radius: 100px;
            margin-bottom: 16px;
        }
        .step-badge svg { width: 14px; height: 14px; }

        h1 { font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
        .sub { font-size: 14px; color: #64748b; margin-bottom: 32px; }
        .sub strong { color: #06b6d4; }

        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px; color: #0f172a;
            transition: border-color .15s;
            outline: none;
        }
        input:focus { border-color: #06b6d4; box-shadow: 0 0 0 3px rgba(6,182,212,.1); }
        input.prefilled { background: #f8fafc; color: #475569; }
        .hint { font-size: 11px; color: #94a3b8; margin-top: 5px; }

        .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; }
        .error-box {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 10px; padding: 12px 16px;
            margin-bottom: 20px; font-size: 13px; color: #dc2626;
        }
        .error-box ul { margin-left: 16px; }

        .btn-submit {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white; font-size: 15px; font-weight: 700;
            border: none; border-radius: 12px; cursor: pointer;
            transition: opacity .15s, transform .1s;
            margin-top: 8px;
        }
        .btn-submit:hover { opacity: .9; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .divider { border: none; border-top: 1px solid #f1f5f9; margin: 24px 0; }
        .login-link { text-align: center; font-size: 13px; color: #64748b; }
        .login-link a { color: #06b6d4; font-weight: 600; text-decoration: none; }

        .info-box {
            background: #f0f9ff; border: 1px solid #bae6fd;
            border-radius: 10px; padding: 12px 16px;
            font-size: 12px; color: #0369a1; margin-bottom: 24px;
            display: flex; gap: 10px; align-items: flex-start;
        }
        .info-box svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }
    </style>
</head>
<body>
<div class="card">
    {{-- Logo --}}
    <div class="logo">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
            </svg>
        </div>
        <span class="logo-text">Robo<span>Nesia</span> Academy</span>
    </div>

    {{-- Step badge --}}
    <div class="step-badge">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
        Pendaftaran & Pembayaran Selesai
    </div>

    <h1>Buat Akun Siswa</h1>
    <p class="sub">
        Satu langkah lagi! Buat akun untuk akses dashboard belajar kamu.
        Nomor referensi: <strong>{{ $pendaftaran->no_referensi }}</strong>
    </p>

    {{-- Info box --}}
    <div class="info-box">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Data diri kamu diambil dari formulir pendaftaran. Akses dashboard aktif setelah admin memverifikasi pembayaranmu.</span>
    </div>

    {{-- Error --}}
    @if($errors->any())
        <div class="error-box">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pendaftaran.buat-akun.store', $pendaftaran) }}">
        @csrf

        {{-- Nama Lengkap - pre-filled dari calon peserta --}}
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input
                type="text"
                name="nama_lengkap"
                value="{{ old('nama_lengkap', $calonPeserta?->nama_lengkap) }}"
                class="prefilled"
                required>
            <p class="hint">Diambil dari data pendaftaran. Bisa diubah jika perlu.</p>
        </div>

        {{-- Email - pre-filled tapi bisa diedit --}}
        <div class="form-group">
            <label>Email <span style="color:#dc2626">*</span></label>
            <input
                type="email"
                name="email"
                value="{{ old('email', $calonPeserta?->email) }}"
                placeholder="email@contoh.com"
                required>
            @error('email')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label>Password <span style="color:#dc2626">*</span></label>
            <input
                type="password"
                name="password"
                placeholder="Minimal 8 karakter, kombinasi huruf & angka"
                required>
            @error('password')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="form-group">
            <label>Konfirmasi Password <span style="color:#dc2626">*</span></label>
            <input
                type="password"
                name="password_confirmation"
                placeholder="Ulangi password"
                required>
        </div>

        <button type="submit" class="btn-submit">
            Buat Akun & Masuk ke Dashboard →
        </button>
    </form>

    <hr class="divider">
    <p class="login-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </p>
</div>
</body>
</html>
