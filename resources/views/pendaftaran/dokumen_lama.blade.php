<?php
session_start();

// Guard: kalau belum isi data diri, balik ke step 1
if (empty($_SESSION['data_diri'])) {
    header('Location: daftar-program.php');
    exit;
}

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── Validasi KTP / Kartu Pelajar ──
    $ktp = $_FILES['ktp'] ?? null;
    if (!$ktp || $ktp['error'] === UPLOAD_ERR_NO_FILE) {
        $errors['ktp'] = 'KTP / Kartu Pelajar wajib diupload.';
    } elseif ($ktp['error'] !== UPLOAD_ERR_OK) {
        $errors['ktp'] = 'Terjadi kesalahan saat upload KTP.';
    } else {
        $ktp_ext  = strtolower(pathinfo($ktp['name'], PATHINFO_EXTENSION));
        $ktp_size = $ktp['size'];
        $allowed_ktp = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_ktp     = 5 * 1024 * 1024; // 5MB

        if (!in_array($ktp_ext, $allowed_ktp)) {
            $errors['ktp'] = 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.';
        } elseif ($ktp_size > $max_ktp) {
            $errors['ktp'] = 'Ukuran file melebihi batas maksimum 5MB.';
        }
    }

    // ── Validasi Pas Foto 3x4 ──
    $foto = $_FILES['pas_foto'] ?? null;
    if (!$foto || $foto['error'] === UPLOAD_ERR_NO_FILE) {
        $errors['pas_foto'] = 'Pas foto wajib diupload.';
    } elseif ($foto['error'] !== UPLOAD_ERR_OK) {
        $errors['pas_foto'] = 'Terjadi kesalahan saat upload pas foto.';
    } else {
        $foto_ext  = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        $foto_size = $foto['size'];
        $allowed_foto = ['jpg', 'jpeg', 'png'];
        $max_foto     = 2 * 1024 * 1024; // 2MB

        if (!in_array($foto_ext, $allowed_foto)) {
            $errors['pas_foto'] = 'Format file tidak didukung. Gunakan JPG atau PNG.';
        } elseif ($foto_size > $max_foto) {
            $errors['pas_foto'] = 'Ukuran file melebihi batas maksimum 2MB.';
        }
    }

    // ── Validasi Bukti Status (opsional, tapi kalau diisi validasi format) ──
    $bukti = $_FILES['bukti_status'] ?? null;
    if ($bukti && $bukti['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($bukti['error'] !== UPLOAD_ERR_OK) {
            $errors['bukti_status'] = 'Terjadi kesalahan saat upload bukti status.';
        } else {
            $bukti_ext  = strtolower(pathinfo($bukti['name'], PATHINFO_EXTENSION));
            $bukti_size = $bukti['size'];
            $allowed_bukti = ['jpg', 'jpeg', 'png', 'pdf'];
            $max_bukti     = 5 * 1024 * 1024; // 5MB

            if (!in_array($bukti_ext, $allowed_bukti)) {
                $errors['bukti_status'] = 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.';
            } elseif ($bukti_size > $max_bukti) {
                $errors['bukti_status'] = 'Ukuran file melebihi batas maksimum 5MB.';
            }
        }
    }

    if (empty($errors)) {
        // Simpan nama file ke session (nanti diproses di step pembayaran/submit akhir)
        $_SESSION['dokumen'] = [
            'ktp'         => $ktp['name'],
            'pas_foto'    => $foto['name'],
            'bukti_status'=> $bukti ? $bukti['name'] : null,
        ];
        $success = true;
        // TODO: redirect ke step 3 pembayaran
        // header('Location: pembayaran.php');
        // exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Dokumen – AI for Robotics | RoboNesia</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --cyan-50 : #ECFEFF;
  --cyan-500: #00C2CB;
  --cyan-700: #009CA5;
  --slate-50 : #F8FAFC;
  --slate-100: #F1F5F9;
  --slate-200: #E2E8F0;
  --slate-300: #CBD5E1;
  --slate-400: #94A3B8;
  --slate-500: #64748B;
  --slate-600: #475569;
  --slate-700: #334155;
  --slate-800: #1E293B;
  --slate-900: #0F172A;
  --color-primary      : var(--cyan-500);
  --color-primary-dark : var(--cyan-700);
  --color-primary-light: var(--cyan-50);
  --color-primary-ring : rgba(0,194,203,.18);
  --color-bg     : var(--slate-100);
  --color-surface: #fff;
  --color-border : var(--slate-200);
  --color-text   : var(--slate-900);
  --color-muted  : var(--slate-500);
  --color-subtle : var(--slate-400);
  --color-error  : #EF4444;
  --color-success: #10B981;
  --color-warning: #F97316;
  --radius-md: 8px;
  --radius-lg: 12px;
  --radius-xl: 16px;
  --shadow-sm: 0 1px 4px rgba(15,23,42,.08), 0 0 0 1px rgba(15,23,42,.04);
  --font-sans: 'Plus Jakarta Sans', -apple-system, sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: var(--font-sans);
  background : var(--color-bg);
  color      : var(--color-text);
  min-height : 100vh;
  padding    : 24px 16px 48px;
  font-size  : 14px;
  -webkit-font-smoothing: antialiased;
}
.page-wrap { max-width: 780px; margin: 0 auto; }

/* back link */
.back-link {
  display: inline-flex; align-items: center; gap: 6px;
  color: var(--color-muted); text-decoration: none;
  font-size: 13px; font-weight: 500; margin-bottom: 20px;
  transition: color .15s;
}
.back-link:hover { color: var(--color-primary); }
.back-link svg { width: 16px; height: 16px; }

/* card */
.card {
  background: var(--color-surface);
  border-radius: var(--radius-xl);
  box-shadow: var(--shadow-sm);
  margin-bottom: 16px;
  overflow: hidden;
}

/* program header */
.program-header {
  display: flex; align-items: center;
  justify-content: space-between;
  padding: 20px 28px; gap: 16px;
}
.program-left  { display: flex; align-items: center; gap: 16px; }
.program-icon  {
  width: 52px; height: 52px;
  background: var(--slate-900);
  border-radius: var(--radius-lg);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.program-icon svg { width: 26px; height: 26px; color: #fff; }
.program-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: .07em; text-transform: uppercase; color: var(--color-muted); margin-bottom: 2px; }
.program-name    { font-size: 20px; font-weight: 800; }
.price-eyebrow   { font-size: 11px; color: var(--color-subtle); margin-bottom: 2px; text-align: right; }
.price-value     { font-size: 22px; font-weight: 800; color: var(--color-primary); }

/* stepper */
.stepper-card { padding: 24px 28px; }
.stepper { display: flex; align-items: flex-start; justify-content: space-between; }
.step-item { display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1; position: relative; }
.step-item:not(:last-child)::after {
  content: ''; position: absolute;
  top: 19px; left: 50%; width: 100%; height: 2px;
  background: var(--color-border); z-index: 0;
}
.step-item.done:not(:last-child)::after,
.step-item.active:not(:last-child)::after { background: var(--color-primary); }
.step-circle {
  width: 38px; height: 38px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700;
  border: 2px solid var(--color-border);
  background: var(--color-surface); color: var(--color-subtle);
  z-index: 1; position: relative; transition: all .2s;
}
.step-item.active .step-circle {
  background: var(--color-primary); border-color: var(--color-primary);
  color: #fff; box-shadow: 0 0 0 5px var(--color-primary-ring);
}
.step-item.done .step-circle {
  background: #ECFEFF; border-color: var(--color-primary);
  color: var(--color-primary-dark);
}
.step-label { font-size: 12px; font-weight: 500; color: var(--color-subtle); text-align: center; }
.step-item.active .step-label { color: var(--color-primary); font-weight: 700; }
.step-item.done   .step-label { color: var(--color-primary-dark); }

/* form card */
.form-card { padding: 28px 28px 32px; }
.form-title    { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
.form-subtitle { font-size: 13px; color: var(--color-muted); margin-bottom: 28px; }

/* upload field */
.upload-field { margin-bottom: 24px; }
.upload-field label {
  display: block; font-size: 13px; font-weight: 600;
  color: var(--slate-700); margin-bottom: 8px;
}
.upload-field label .req { color: var(--color-error); margin-left: 2px; }
.upload-field label .opt { font-size: 12px; font-weight: 400; color: var(--color-muted); margin-left: 4px; }

/* drop zone */
.drop-zone {
  border: 1.5px dashed var(--slate-300);
  border-radius: var(--radius-lg);
  padding: 28px 20px;
  display: flex; align-items: center; gap: 16px;
  cursor: pointer;
  transition: border-color .15s, background .15s;
  position: relative;
  background: var(--color-surface);
}
.drop-zone:hover, .drop-zone.dragover {
  border-color: var(--color-primary);
  background: var(--color-primary-light);
}
.drop-zone.is-error { border-color: var(--color-error); }
.drop-zone.has-file { border-color: var(--color-success); background: #F0FDF4; }

.drop-icon {
  width: 40px; height: 40px; border-radius: var(--radius-md);
  background: var(--slate-100);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  transition: background .15s;
}
.drop-zone:hover .drop-icon,
.drop-zone.dragover .drop-icon { background: var(--cyan-50); }
.drop-icon svg { width: 20px; height: 20px; color: var(--color-muted); }

.drop-text { flex: 1; }
.drop-text-main { font-size: 14px; font-weight: 600; color: var(--slate-700); }
.drop-text-sub  { font-size: 12px; color: var(--color-muted); margin-top: 2px; }

/* hidden real input */
.drop-zone input[type="file"] {
  position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}

/* file preview */
.file-preview {
  display: none;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  background: #F0FDF4;
  border: 1.5px solid var(--color-success);
  border-radius: var(--radius-md);
  margin-top: 8px;
}
.file-preview.visible { display: flex; }
.file-preview-name { font-size: 13px; font-weight: 600; color: #065F46; flex: 1; }
.file-preview-size { font-size: 12px; color: var(--color-muted); }
.file-preview-remove {
  width: 24px; height: 24px; border-radius: 50%;
  border: none; background: #D1FAE5; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; transition: background .15s;
}
.file-preview-remove:hover { background: #A7F3D0; }
.file-preview-remove svg { width: 14px; height: 14px; color: #065F46; }

/* error msg */
.error-msg {
  display: flex; align-items: center; gap: 4px;
  font-size: 12px; color: var(--color-error); font-weight: 500;
  margin-top: 6px;
}
.error-msg::before {
  content: '!'; display: inline-flex;
  width: 14px; height: 14px; border-radius: 50%;
  background: var(--color-error); color: #fff;
  font-size: 10px; font-weight: 700;
  align-items: center; justify-content: center; flex-shrink: 0;
}

/* privacy notice */
.privacy-notice {
  display: flex; gap: 12px; align-items: flex-start;
  background: #FFF7ED;
  border: 1.5px solid #FDBA74;
  border-radius: var(--radius-lg);
  padding: 16px 18px;
  margin-top: 8px; margin-bottom: 8px;
}
.privacy-notice svg { width: 20px; height: 20px; color: var(--color-warning); flex-shrink: 0; margin-top: 1px; }
.privacy-notice-text strong { display: block; font-size: 14px; font-weight: 700; color: #C2410C; margin-bottom: 4px; }
.privacy-notice-text p { font-size: 13px; color: #9A3412; line-height: 1.55; }

/* action row */
.action-row {
  display: flex; align-items: center; justify-content: space-between;
  margin-top: 32px; padding-top: 24px;
  border-top: 1px solid var(--color-border);
}
.btn-secondary {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 10px 20px;
  border: 1.5px solid var(--color-border); border-radius: var(--radius-md);
  background: var(--color-surface); color: var(--slate-600);
  font-family: var(--font-sans); font-size: 14px; font-weight: 600;
  cursor: pointer; text-decoration: none;
  transition: border-color .15s, color .15s, background .15s;
}
.btn-secondary:hover { border-color: var(--slate-400); color: var(--slate-800); background: var(--slate-50); }
.btn-secondary svg { width: 16px; height: 16px; }
.btn-primary {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 11px 28px;
  background: var(--color-primary); color: #fff; border: none;
  border-radius: var(--radius-md);
  font-family: var(--font-sans); font-size: 14px; font-weight: 700;
  cursor: pointer; transition: background .15s, transform .1s;
  box-shadow: 0 2px 8px rgba(0,194,203,.30);
}
.btn-primary:hover { background: var(--color-primary-dark); transform: translateY(-1px); }
.btn-primary svg { width: 16px; height: 16px; }

/* success banner */
.success-banner {
  display: flex; align-items: center; gap: 12px;
  background: #ECFDF5; border: 1.5px solid var(--color-success);
  border-radius: var(--radius-lg); padding: 14px 18px;
  margin-bottom: 16px; font-size: 14px; font-weight: 600; color: #065F46;
}
.success-banner svg { width: 22px; height: 22px; color: var(--color-success); flex-shrink: 0; }
</style>
</head>
<body>
<div class="page-wrap">

  <a href="daftar-program.php" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
    </svg>
    Kembali ke detail program
  </a>

  <?php if ($success): ?>
  <div class="success-banner">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
    </svg>
    Dokumen berhasil diupload! Lanjut ke tahap pembayaran.
  </div>
  <?php endif; ?>

  <!-- Program Header -->
  <div class="card">
    <div class="program-header">
      <div class="program-left">
        <div class="program-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
          </svg>
        </div>
        <div>
          <div class="program-eyebrow">Mendaftar Program</div>
          <div class="program-name">AI for Robotics</div>
        </div>
      </div>
      <div>
        <div class="price-eyebrow">Biaya</div>
        <div class="price-value">Rp 3.500.000</div>
      </div>
    </div>
  </div>

  <!-- Stepper: step 1 done, step 2 active -->
  <div class="card stepper-card">
    <div class="stepper">
      <div class="step-item done">
        <div class="step-circle">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
          </svg>
        </div>
        <div class="step-label">Data Diri</div>
      </div>
      <div class="step-item active">
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

  <!-- Form Upload -->
  <div class="card form-card">
    <div class="form-title">Upload Dokumen</div>
    <div class="form-subtitle">Lampirkan dokumen pendukung pendaftaran</div>

    <form method="POST"
        action="{{ route('pendaftaran.dokumen.store', $pendaftaran->id) }}"
        enctype="multipart/form-data">

        @csrf

        <!-- KTP / Kartu Pelajar -->
        <div class="upload-field">
            <label>KTP / Kartu Pelajar <span class="req">*</span></label>

            <div class="drop-zone @error('ktp') is-error @enderror" id="zone-ktp">

                <div class="drop-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                    </svg>
                </div>

                <div class="drop-text">
                    <div class="drop-text-main">
                        Klik untuk upload atau drag file
                    </div>

                    <div class="drop-text-sub">
                        Format JPG/PNG/PDF · Max 5MB
                    </div>
                </div>

                <input
                    type="file"
                    name="ktp"
                    id="input-ktp"
                    accept=".jpg,.jpeg,.png,.pdf"
                    required>
            </div>

            <div class="file-preview" id="preview-ktp">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#10B981" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>

                <span class="file-preview-name" id="name-ktp"></span>
                <span class="file-preview-size" id="size-ktp"></span>

                <button type="button" class="file-preview-remove" onclick="removeFile('ktp')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @error('ktp')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <!-- PAS FOTO -->
        <div class="upload-field">

            <label>Pas Foto 3×4 <span class="req">*</span></label>

            <div class="drop-zone @error('pas_foto') is-error @enderror" id="zone-pas_foto">

                <div class="drop-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                    </svg>
                </div>

                <div class="drop-text">
                    <div class="drop-text-main">
                        Klik untuk upload atau drag file
                    </div>

                    <div class="drop-text-sub">
                        Format JPG/PNG · Max 2MB · Background putih
                    </div>
                </div>

                <input
                    type="file"
                    name="pas_foto"
                    id="input-pas_foto"
                    accept=".jpg,.jpeg,.png"
                    required>
            </div>

            <div class="file-preview" id="preview-pas_foto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#10B981" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>

                <span class="file-preview-name" id="name-pas_foto"></span>
                <span class="file-preview-size" id="size-pas_foto"></span>

                <button type="button" class="file-preview-remove" onclick="removeFile('pas_foto')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @error('pas_foto')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Lanjut ke Pembayaran
        </button>

    </form>
  </div>

</div>

<script>
// Format bytes
function formatBytes(bytes) {
  if (bytes < 1024)       return bytes + ' B';
  if (bytes < 1048576)    return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / 1048576).toFixed(1) + ' MB';
}

// Setup tiap drop zone
['ktp', 'pas_foto', 'bukti_status'].forEach(name => {
  const zone    = document.getElementById('zone-' + name);
  const input   = document.getElementById('input-' + name);
  const preview = document.getElementById('preview-' + name);
  const nameEl  = document.getElementById('name-' + name);
  const sizeEl  = document.getElementById('size-' + name);

  // Klik area → trigger input
  zone.addEventListener('click', e => {
    if (e.target !== input) input.click();
  });

  // File dipilih via input
  input.addEventListener('change', () => {
    if (input.files.length) showPreview(name, input.files[0]);
  });

  // Drag & drop
  zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
  zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
  zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (!file) return;

    // Transfer ke input
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    showPreview(name, file);
  });

  function showPreview(name, file) {
    const zone = document.getElementById('zone-' + name);
    zone.classList.add('has-file');
    zone.classList.remove('is-error');
    document.getElementById('name-' + name).textContent = file.name;
    document.getElementById('size-' + name).textContent = formatBytes(file.size);
    document.getElementById('preview-' + name).classList.add('visible');
  }
});

function removeFile(name) {
  const input   = document.getElementById('input-' + name);
  const zone    = document.getElementById('zone-' + name);
  const preview = document.getElementById('preview-' + name);
  input.value = '';
  zone.classList.remove('has-file', 'is-error');
  preview.classList.remove('visible');
}

// Client-side validasi sebelum submit
document.querySelector('form').addEventListener('submit', e => {
  let hasError = false;

  // KTP: wajib, maks 5MB, JPG/PNG/PDF
  const ktpInput = document.getElementById('input-ktp');
  const ktpZone  = document.getElementById('zone-ktp');
  if (!ktpInput.files.length) {
    ktpZone.classList.add('is-error');
    showClientError('ktp', 'KTP / Kartu Pelajar wajib diupload.');
    hasError = true;
  } else {
    const f = ktpInput.files[0];
    const ext = f.name.split('.').pop().toLowerCase();
    if (!['jpg','jpeg','png','pdf'].includes(ext)) {
      ktpZone.classList.add('is-error');
      showClientError('ktp', 'Format tidak didukung. Gunakan JPG, PNG, atau PDF.');
      hasError = true;
    } else if (f.size > 5 * 1024 * 1024) {
      ktpZone.classList.add('is-error');
      showClientError('ktp', 'Ukuran file melebihi batas 5MB.');
      hasError = true;
    }
  }

  // Pas Foto: wajib, maks 2MB, JPG/PNG
  const fotoInput = document.getElementById('input-pas_foto');
  const fotoZone  = document.getElementById('zone-pas_foto');
  if (!fotoInput.files.length) {
    fotoZone.classList.add('is-error');
    showClientError('pas_foto', 'Pas foto wajib diupload.');
    hasError = true;
  } else {
    const f = fotoInput.files[0];
    const ext = f.name.split('.').pop().toLowerCase();
    if (!['jpg','jpeg','png'].includes(ext)) {
      fotoZone.classList.add('is-error');
      showClientError('pas_foto', 'Format tidak didukung. Gunakan JPG atau PNG.');
      hasError = true;
    } else if (f.size > 2 * 1024 * 1024) {
      fotoZone.classList.add('is-error');
      showClientError('pas_foto', 'Ukuran file melebihi batas 2MB.');
      hasError = true;
    }
  }

  // Bukti status: opsional, tapi kalau diisi validasi
  const buktiInput = document.getElementById('input-bukti_status');
  const buktiZone  = document.getElementById('zone-bukti_status');
  if (buktiInput.files.length) {
    const f = buktiInput.files[0];
    const ext = f.name.split('.').pop().toLowerCase();
    if (!['jpg','jpeg','png','pdf'].includes(ext)) {
      buktiZone.classList.add('is-error');
      showClientError('bukti_status', 'Format tidak didukung. Gunakan JPG, PNG, atau PDF.');
      hasError = true;
    } else if (f.size > 5 * 1024 * 1024) {
      buktiZone.classList.add('is-error');
      showClientError('bukti_status', 'Ukuran file melebihi batas 5MB.');
      hasError = true;
    }
  }

  if (hasError) {
    e.preventDefault();
    document.querySelector('.is-error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
});

function showClientError(name, msg) {
  // Hapus error lama kalau ada
  const zone = document.getElementById('zone-' + name);
  let existing = zone.parentElement.querySelector('.error-msg.js-err');
  if (!existing) {
    existing = document.createElement('div');
    existing.className = 'error-msg js-err';
    zone.parentElement.appendChild(existing);
  }
  existing.textContent = msg;
}
</script>
</body>
</html>