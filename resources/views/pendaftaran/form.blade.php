<?php
session_start();

if (empty($errors)) {
    // Simpan data ke session dulu
    session_start();
    $_SESSION['data_diri'] = [
        'nama'          => $nama,
        'email'         => $email,
        'no_hp'         => $no_hp,
        'tanggal_lahir' => $tanggal_lahir,
        'jenis_kelamin' => $jenis_kelamin,
        'domisili'      => $domisili,
        'alamat'        => $alamat,
        'pendidikan'    => $pendidikan,
        'institusi'     => $institusi,
        'motivasi'      => $motivasi,
        'format_kelas'  => $format_kelas,
    ];
    // Redirect ke step 2
    header('Location: upload-dokumen.php');
    exit;
}

// Handle form submission
$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
    $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
    $domisili = trim($_POST['domisili'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $pendidikan = trim($_POST['pendidikan'] ?? '');
    $institusi = trim($_POST['institusi'] ?? '');
    $motivasi = trim($_POST['motivasi'] ?? '');
    $format_kelas = trim($_POST['format_kelas'] ?? '');

    if (!$nama) $errors['nama'] = 'Nama lengkap wajib diisi.';
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email tidak valid.';
    if (!$no_hp) $errors['no_hp'] = 'Nomor HP wajib diisi.';
    if (!$tanggal_lahir) $errors['tanggal_lahir'] = 'Tanggal lahir wajib diisi.';
    if (!$jenis_kelamin) $errors['jenis_kelamin'] = 'Jenis kelamin wajib dipilih.';
    if (!$domisili) $errors['domisili'] = 'Domisili wajib diisi.';
    if (!$alamat) $errors['alamat'] = 'Alamat wajib diisi.';
    if (!$pendidikan) $errors['pendidikan'] = 'Pendidikan/Pekerjaan wajib dipilih.';
    if (!$format_kelas) $errors['format_kelas'] = 'Format kelas wajib dipilih.';

    if (empty($errors)) {
        $success = true;
    }
}

$format_kelas_selected = $_POST['format_kelas'] ?? 'online';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mendaftar Program – AI for Robotics</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            padding: 24px 16px 48px;
            color: #1a1f2e;
        }

        /* ── back link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #4a5568;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            transition: color .15s;
        }
        .back-link:hover { color: #00b8b0; }
        .back-link svg { width: 16px; height: 16px; }

        /* ── card shell ── */
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,.07);
            max-width: 780px;
            margin: 0 auto 16px;
            overflow: hidden;
        }

        /* ── program header ── */
        .program-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 28px;
        }
        .program-left { display: flex; align-items: center; gap: 16px; }
        .program-icon {
            width: 52px; height: 52px;
            background: #1a2e4a;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .program-icon svg { width: 26px; height: 26px; color: #fff; }
        .program-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .08em;
            color: #8a95a3;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .program-name {
            font-size: 20px;
            font-weight: 700;
            color: #1a1f2e;
        }
        .program-price-block { text-align: right; }
        .price-label { font-size: 12px; color: #8a95a3; margin-bottom: 2px; }
        .price-value { font-size: 22px; font-weight: 700; color: #00b8b0; }

        /* ── stepper ── */
        .stepper-card { padding: 24px 28px; }
        .stepper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex: 1;
            position: relative;
        }
        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #e2e8f0;
            z-index: 0;
        }
        .step-item.active:not(:last-child)::after { background: #00b8b0; }
        .step-circle {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            font-weight: 600;
            border: 2px solid #e2e8f0;
            background: #fff;
            color: #8a95a3;
            z-index: 1;
            position: relative;
        }
        .step-item.active .step-circle {
            background: #00b8b0;
            border-color: #00b8b0;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(0,184,176,.15);
        }
        .step-label {
            font-size: 12px;
            font-weight: 500;
            color: #8a95a3;
        }
        .step-item.active .step-label { color: #00b8b0; font-weight: 600; }

        /* ── form card ── */
        .form-card { padding: 28px 28px 32px; }
        .form-title {
            font-size: 22px;
            font-weight: 700;
            color: #1a1f2e;
            margin-bottom: 4px;
        }
        .form-subtitle {
            font-size: 14px;
            color: #8a95a3;
            margin-bottom: 28px;
        }

        /* ── grid ── */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-row.single { grid-template-columns: 1fr; }
        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }

        /* ── field ── */
        .field { display: flex; flex-direction: column; gap: 6px; }
        .field label {
            font-size: 13px;
            font-weight: 600;
            color: #1a1f2e;
        }
        .field label .req { color: #e53e3e; margin-left: 2px; }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrap .icon {
            position: absolute;
            left: 13px;
            color: #a0aab4;
            display: flex;
            align-items: center;
        }
        .input-wrap .icon svg { width: 16px; height: 16px; }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px;
            color: #1a1f2e;
            background: #fff;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            font-family: inherit;
        }
        .has-icon input,
        .has-icon select { padding-left: 38px; }

        input::placeholder, textarea::placeholder { color: #b0bcc8; }

        input:focus, select:focus, textarea:focus {
            border-color: #00b8b0;
            box-shadow: 0 0 0 3px rgba(0,184,176,.12);
        }
        input.error, select.error, textarea.error {
            border-color: #e53e3e;
        }
        .error-msg {
            font-size: 11.5px;
            color: #e53e3e;
            margin-top: 2px;
        }

        select { appearance: none; cursor: pointer; }
        .select-wrap { position: relative; }
        .select-wrap::after {
            content: '';
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 0; height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #8a95a3;
            pointer-events: none;
        }

        textarea { resize: vertical; min-height: 100px; line-height: 1.55; }

        /* ── gender radio card ── */
        .gender-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        @media (max-width: 480px) { .gender-group { grid-template-columns: 1fr; } }

        .gender-label {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 13px 16px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #1a1f2e;
            transition: border-color .15s, background .15s;
        }
        .gender-label:hover { border-color: #00b8b0; background: #f0fffe; }
        .format-option:checked + .gender-label {
            border-color: #00b8b0;
            background: #f0fffe;
        }
        .gender-radio {
            width: 18px; height: 18px;
            border-radius: 50%;
            border: 2px solid #c8d0da;
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: border-color .15s;
        }
        .format-option:checked + .gender-label .gender-radio {
            border-color: #00b8b0;
            border-width: 5px;
        }

        /* ── class format ── */
        .format-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        @media (max-width: 480px) { .format-group { grid-template-columns: 1fr; } }

        .format-option { display: none; }
        .format-label {
            display: flex;
            flex-direction: column;
            gap: 4px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 18px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            position: relative;
        }
        .format-label:hover { border-color: #00b8b0; background: #f0fffe; }
        .format-option:checked + .format-label {
            border-color: #00b8b0;
            background: #f0fffe;
        }
        .format-check {
            position: absolute;
            top: 14px; right: 14px;
            width: 20px; height: 20px;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            transition: background .15s, border-color .15s;
        }
        .format-option:checked + .format-label .format-check {
            background: #00b8b0;
            border-color: #00b8b0;
        }
        .format-option:checked + .format-label .format-check::after {
            content: '';
            width: 6px; height: 6px;
            background: #fff;
            border-radius: 50%;
        }
        .format-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; margin-right: 6px; }
        .format-name {
            font-size: 15px;
            font-weight: 600;
            color: #1a1f2e;
            display: flex;
            align-items: center;
        }
        .format-desc { font-size: 12.5px; color: #8a95a3; margin-left: 15px; }

        /* ── action buttons ── */
        .action-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 32px;
        }
        .btn-batal {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 12px 22px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            color: #4a5568;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: border-color .15s, color .15s;
        }
        .btn-batal:hover { border-color: #8a95a3; color: #1a1f2e; }
        .btn-lanjut {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 30px;
            background: #00b8b0;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s, transform .1s;
        }
        .btn-lanjut:hover { background: #009e97; transform: translateY(-1px); }
        .btn-lanjut svg { width: 18px; height: 18px; }

        /* ── success banner ── */
        .success-banner {
            background: #e6faf9;
            border: 1.5px solid #00b8b0;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            color: #007c77;
            font-size: 14px;
            font-weight: 600;
        }
        .success-banner svg { width: 22px; height: 22px; flex-shrink: 0; }

        /* divider */
        .section-divider { margin: 24px 0; border: none; border-top: 1px solid #f0f2f5; }
    </style>
</head>
<body>

<a href="#" class="back-link" style="max-width:780px;display:flex;margin:0 auto 16px;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
    </svg>
    Kembali ke detail program
</a>

<?php if ($success): ?>
<div class="success-banner" style="max-width:780px;margin:0 auto 16px;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
    </svg>
    Data diri berhasil disimpan! Lanjut ke langkah berikutnya.
</div>
<?php endif; ?>

<!-- Program Header -->
<div class="card">
    <div class="program-header">
        <div class="program-left">
            <div class="program-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                </svg>
            </div>
            <div>
                <div class="program-label">Mendaftar Program</div>
                <div class="program-name">AI for Robotics</div>
            </div>
        </div>
        <div class="program-price-block">
            <div class="price-label">Biaya</div>
            <div class="price-value">Rp 3.500.000</div>
        </div>
    </div>
</div>

<!-- Stepper -->
<div class="card stepper-card">
    <div class="stepper">
        <div class="step-item active">
            <div class="step-circle">1</div>
            <div class="step-label">Data Diri</div>
        </div>
        <div class="step-item">
            <div class="step-circle">2</div>
            <div class="step-label">Dokumen</div>
        </div>
        <div class="step-item">
            <div class="step-circle">3</div>
            <div class="step-label">Pembayaran</div>
        </div>
        <div class="step-item">
            <div class="step-circle">4</div>
            <div class="step-label">Selesai</div>
        </div>
    </div>
</div>

<!-- Form -->
<div class="card form-card">
    <div class="form-title">Data Diri</div>
    <div class="form-subtitle">Isi data diri sesuai identitas resmi</div>

    <form method="POST"
      action="{{ route('pendaftaran.store') }}"
      enctype="multipart/form-data">

        <!-- Row 1: Nama + Email -->
        <div class="form-row">
            <div class="field">
                <label>Nama Lengkap <span class="req">*</span></label>
                <div class="input-wrap has-icon">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                    </span>
                    <input type="text" name="nama" placeholder="Sesuai KTP/Kartu Pelajar"
                        value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                        class="<?= isset($errors['nama']) ? 'error' : '' ?>">
                </div>
                <?php if (isset($errors['nama'])): ?><div class="error-msg"><?= $errors['nama'] ?></div><?php endif; ?>
            </div>

            <div class="field">
                <label>Email <span class="req">*</span></label>
                <div class="input-wrap has-icon">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                    </span>
                    <input type="email" name="email" placeholder="email@contoh.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        class="<?= isset($errors['email']) ? 'error' : '' ?>">
                </div>
                <?php if (isset($errors['email'])): ?><div class="error-msg"><?= $errors['email'] ?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 2: No HP + Tanggal Lahir -->
        <div class="form-row">
            <div class="field">
                <label>Nomor HP / WhatsApp <span class="req">*</span></label>
                <div class="input-wrap has-icon">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                        </svg>
                    </span>
                    <input type="tel" name="no_hp" placeholder="08xx-xxxx-xxxx"
                        value="<?= htmlspecialchars($_POST['no_hp'] ?? '') ?>"
                        class="<?= isset($errors['no_hp']) ? 'error' : '' ?>">
                </div>
                <?php if (isset($errors['no_hp'])): ?><div class="error-msg"><?= $errors['no_hp'] ?></div><?php endif; ?>
            </div>

            <div class="field">
                <label>Tanggal Lahir <span class="req">*</span></label>
                <div class="input-wrap has-icon">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                        </svg>
                    </span>
                    <input type="date" name="tanggal_lahir"
                        value="<?= htmlspecialchars($_POST['tanggal_lahir'] ?? '') ?>"
                        class="<?= isset($errors['tanggal_lahir']) ? 'error' : '' ?>">
                </div>
                <?php if (isset($errors['tanggal_lahir'])): ?><div class="error-msg"><?= $errors['tanggal_lahir'] ?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 3: Jenis Kelamin (radio card) -->
        <div class="form-row single">
            <div class="field">
                <label>Jenis Kelamin <span class="req">*</span></label>
                <div class="gender-group" style="margin-top:8px;">
                    <div>
                        <input type="radio" name="jenis_kelamin" id="jk_laki" value="laki" class="format-option"
                            <?= (($_POST['jenis_kelamin'] ?? '') === 'laki') ? 'checked' : '' ?>>
                        <label for="jk_laki" class="gender-label">
                            <span class="gender-radio"></span>
                            Laki-laki
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="jenis_kelamin" id="jk_perempuan" value="perempuan" class="format-option"
                            <?= (($_POST['jenis_kelamin'] ?? '') === 'perempuan') ? 'checked' : '' ?>>
                        <label for="jk_perempuan" class="gender-label">
                            <span class="gender-radio"></span>
                            Perempuan
                        </label>
                    </div>
                </div>
                <?php if (isset($errors['jenis_kelamin'])): ?><div class="error-msg" style="margin-top:6px;"><?= $errors['jenis_kelamin'] ?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 4: Domisili (Kota) -->
        <div class="form-row single">
            <div class="field">
                <label>Domisili (Kota) <span class="req">*</span></label>
                <div class="input-wrap has-icon">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                    </span>
                    <input type="text" name="domisili" placeholder="Bandung, Jawa Barat"
                        value="<?= htmlspecialchars($_POST['domisili'] ?? '') ?>"
                        class="<?= isset($errors['domisili']) ? 'error' : '' ?>">
                </div>
                <?php if (isset($errors['domisili'])): ?><div class="error-msg"><?= $errors['domisili'] ?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 5: Alamat Lengkap full width -->
        <div class="form-row single">
            <div class="field">
                <label>Alamat Lengkap <span class="req">*</span></label>
                <textarea name="alamat" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan"
                    class="<?= isset($errors['alamat']) ? 'error' : '' ?>"><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                <?php if (isset($errors['alamat'])): ?><div class="error-msg"><?= $errors['alamat'] ?></div><?php endif; ?>
            </div>
        </div>

        <!-- Row 5: Pendidikan + Institusi -->
        <div class="form-row">
            <div class="field">
                <label>Pendidikan / Pekerjaan <span class="req">*</span></label>
                <div class="select-wrap">
                    <select name="pendidikan" class="<?= isset($errors['pendidikan']) ? 'error' : '' ?>">
                        <option value="">Pilih kategori...</option>
                        <option value="sma" <?= ($_POST['pendidikan'] ?? '') === 'sma' ? 'selected' : '' ?>>Siswa SMA/SMK</option>
                        <option value="mahasiswa" <?= ($_POST['pendidikan'] ?? '') === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                        <option value="fresh_graduate" <?= ($_POST['pendidikan'] ?? '') === 'fresh_graduate' ? 'selected' : '' ?>>Fresh Graduate</option>
                        <option value="karyawan" <?= ($_POST['pendidikan'] ?? '') === 'karyawan' ? 'selected' : '' ?>>Karyawan/Profesional</option>
                        <option value="wirausaha" <?= ($_POST['pendidikan'] ?? '') === 'wirausaha' ? 'selected' : '' ?>>Wirausaha</option>
                        <option value="lainnya" <?= ($_POST['pendidikan'] ?? '') === 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                    </select>
                </div>
                <?php if (isset($errors['pendidikan'])): ?><div class="error-msg"><?= $errors['pendidikan'] ?></div><?php endif; ?>
            </div>

            <div class="field">
                <label>Institusi / Sekolah</label>
                <div class="input-wrap has-icon">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                        </svg>
                    </span>
                    <input type="text" name="institusi" placeholder="Nama institusi"
                        value="<?= htmlspecialchars($_POST['institusi'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Row 6: Motivasi -->
        <div class="form-row single">
            <div class="field">
                <label>Motivasi Mengikuti Program</label>
                <textarea name="motivasi" placeholder="Ceritakan motivasi dan target setelah lulus..."><?= htmlspecialchars($_POST['motivasi'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Row 7: Format Kelas -->
        <div class="form-row single">
            <div class="field">
                <label>Pilih Format Kelas <span class="req">*</span></label>
                <div class="format-group" style="margin-top:8px;">

                    <div>
                        <input type="radio" name="format_kelas" id="fmt_online" value="online" class="format-option"
                            <?= ($format_kelas_selected === 'online' || !isset($_POST['format_kelas'])) ? 'checked' : '' ?>>
                        <label for="fmt_online" class="format-label">
                            <div class="format-check"></div>
                            <div class="format-name">
                                <span class="format-dot" style="background:#22c55e;"></span>Online
                            </div>
                            <div class="format-desc">Live via Zoom · Fleksibel</div>
                        </label>
                    </div>

                    <div>
                        <input type="radio" name="format_kelas" id="fmt_semi" value="semi_offline" class="format-option"
                            <?= ($format_kelas_selected === 'semi_offline') ? 'checked' : '' ?>>
                        <label for="fmt_semi" class="format-label">
                            <div class="format-check"></div>
                            <div class="format-name">
                                <span class="format-dot" style="background:#f97316;"></span>Semi-Offline
                            </div>
                            <div class="format-desc">6 online + 2 tatap muka di lab</div>
                        </label>
                    </div>

                </div>
                <?php if (isset($errors['format_kelas'])): ?><div class="error-msg" style="margin-top:8px;"><?= $errors['format_kelas'] ?></div><?php endif; ?>
            </div>
        </div>

        <!-- Actions -->
        <div class="action-row">
            <a href="index.php" class="btn-batal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                Batal
            </a>
            <button type="submit" class="btn-lanjut">
                Lanjutkan
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </button>
        </div>

    </form>
</div>

</body>
</html>