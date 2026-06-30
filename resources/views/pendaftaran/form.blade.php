@php /** @var \Illuminate\Support\ViewErrorBag $errors */ @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kursus Robotik</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Segoe UI, sans-serif;
        }

        body {
            background: #f5f7fb;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            margin-bottom: 25px;
        }

        .program-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 24px 30px;
            margin-bottom: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
        }

        .program-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .program-icon {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            background: linear-gradient(135deg, #0f2745, #0891b2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(14, 165, 233, .25);
        }

        .program-icon i {
            font-size: 34px;
            color: #fff;
        }

        .program-info h3 {
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: .5px;
        }

        .program-info h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
        }

        .biaya {
            text-align: right;
        }

        .biaya small {
            display: block;
            color: #64748b;
            font-size: 15px;
            margin-bottom: 6px;
        }

        .biaya h2 {
            font-size: 22px;
            font-weight: 700;
            color: #0891d1;
        }

        .stepper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .step {
            text-align: center;
            flex: 1;
        }

        .circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #d6dbe5;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-weight: bold;
        }

        .active {
            background: #06b6d4;
            color: white;
            border: none;
        }

        .line {
            height: 2px;
            background: #d6dbe5;
            flex: 1;
            margin: 0 10px;
        }

        .title {
            margin-bottom: 25px;
        }

        .title h1 {
            color: #1f2937;
        }

        .title p {
            color: #6b7280;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: 1/3;
        }

        label {
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }

        .gender-card {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #dbe3ef;
            border-radius: 12px;
            padding: 14px 16px;
            cursor: pointer;
            transition: .2s;
        }

        .gender-card:hover {
            border-color: #06b6d4;
        }

        .gender-card input[type="radio"] {
            width: 18px;
            height: 18px;
        }

        .gender-card span {
            font-size: 15px;
            color: #374151;
            font-weight: 500;
        }

        input,
        select,
        textarea {
            padding: 12px;
            border: 1px solid #dbe3ef;
            border-radius: 10px;
            font-size: 14px;
        }

        textarea {
            resize: none;
            min-height: 120px;
        }

        .btn-area {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn {
            background: #06b6d4;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
        }

        .btn:hover {
            background: #0891b2;
        }

        .error-box {
            background: #fee2e2;
            color: #b91c1c;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        @media(max-width:768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full {
                grid-column: auto;
            }
        }

        /* ============ STYLE FORMAT KELAS ============ */
        .format-section {
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .format-section .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
        }

        .format-section .section-title span {
            color: #ef4444;
        }

        .format-grid {
            display: flex;
            gap: 20px;
        }

        .format-option {
            flex: 1;
            border: 2px solid #dbe3ef;
            border-radius: 12px;
            padding: 20px 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            position: relative;
        }

        .format-option:hover {
            border-color: #0891b2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.15);
        }

        .format-option.selected {
            border-color: #06b6d4;
            background: #f0f9ff;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
        }

        .format-option input[type="radio"] {
            display: none;
        }

        .format-option .format-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .format-option .format-name {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .format-option .format-desc {
            font-size: 14px;
            color: #64748b;
        }

        .format-option .format-check {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #dbe3ef;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .format-option.selected .format-check {
            background: #06b6d4;
            border-color: #06b6d4;
        }

        .format-option.selected .format-check::after {
            content: "✓";
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .format-grid {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <div class="container">

        <div class="program-header">
            <div class="program-left">
                <div class="program-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="program-info">
                    <h3>MENDAFTAR PROGRAM</h3>
                    @php
                        $selectedProgram = null;
                        $programId = request('program_id') ?? old('program_id') ?? (isset($pendaftaran) ? $pendaftaran->program_id : null);
                        if ($programId) $selectedProgram = $programs->firstWhere('id', $programId);
                        if (!$selectedProgram) $selectedProgram = $programs->first();
                    @endphp
                    <h2 id="header-program-name">{{ $selectedProgram?->nama_program ?? 'Pilih Program' }}</h2>
                </div>
            </div>
            <div class="biaya">
                <small>Biaya</small>
                <h2 id="header-program-biaya">{{ $selectedProgram ? 'Rp ' . number_format($selectedProgram->biaya ?? 0, 0, ',', '.') : '-' }}</h2>
            </div>
        </div>

        <div class="card">
            <div class="stepper">
                <div class="step">
                    <div class="circle active">1</div>
                    <small>Data Diri</small>
                </div>
                <div class="line"></div>
                <div class="step">
                    <div class="circle">2</div>
                    <small>Dokumen</small>
                </div>
                <div class="line"></div>
                <div class="step">
                    <div class="circle">3</div>
                    <small>Pembayaran</small>
                </div>
                <div class="line"></div>
                <div class="step">
                    <div class="circle">4</div>
                    <small>Selesai</small>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="title">
                <h1>Data Diri</h1>
                <p>Isi data diri sesuai identitas resmi</p>
            </div>

            @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

        <form method="POST" action="{{ route('pendaftaran.store') }}">

            @csrf

            <div class="form-grid">

    <div class="form-group">
        <label>Nama Lengkap *</label>
        <input type="text"
               name="nama_lengkap"
               value="{{ old('nama_lengkap', auth()->user()?->nama_lengkap ?? '') }}"
               placeholder="Sesuai KTP/Kartu Pelajar">
    </div>

    <div class="form-group">
        <label>Email *</label>
        <input type="email"
               name="email"
               value="{{ old('email', auth()->user()?->email ?? '') }}"
               placeholder="email@contoh.com"
               required>
        @auth
        <small style="color:#64748b;font-size:12px;">Email diisi otomatis dari akun kamu. Bisa diubah jika perlu.</small>
        @endauth
    </div>

    <div class="form-group">
        <label>Nomor HP / WhatsApp *</label>
        <input type="text"
               name="no_hp"
               placeholder="08xx-xxxx-xxxx">
    </div>

    <div class="form-group">
        <label>Tanggal Lahir *</label>
        <input type="date"
               name="tanggal_lahir">
    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin *</label>
                    </div>

                    <div class="form-group">
                        <label>&nbsp;</label>
                    </div>

<div class="form-group">
    <label class="gender-card">
        <input type="radio"
               name="jenis_kelamin"
               value="Laki-laki">

        <span>Laki-laki</span>
    </label>
</div>

<div class="form-group">
    <label class="gender-card">
        <input type="radio"
               name="jenis_kelamin"
               value="Perempuan">

        <span>Perempuan</span>
    </label>
</div>

                    <div></div>

    <div class="form-group full">
        <label>Domisili (Kota) *</label>
        <input type="text"
               name="domisili"
               placeholder="Bandung, Jawa Barat">
    </div>

    <div class="form-group full">
        <label>Alamat Lengkap *</label>

        <textarea
            name="alamat"
            placeholder="Jalan, RT/RW, Kelurahan, Kecamatan"></textarea>
    </div>

                    <div class="form-group">
                        <label>Pendidikan / Pekerjaan *</label>
                        <select name="pendidikan">
                            <option value="">Pilih kategori</option>
                            <option>SMP</option>
                            <option>SMA/SMK</option>
                            <option>Mahasiswa</option>
                            <option>Guru</option>
                            <option>Karyawan</option>
                            <option>Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Institusi / Sekolah</label>
                        <input type="text" name="institusi" placeholder="Nama institusi">
                    </div>

    <div class="form-group full">
        <label>Motivasi Mengikuti Program</label>

        <textarea
            name="motivasi"
            placeholder="Ceritakan motivasi dan target setelah lulus"></textarea>
    </div>

                <!-- ============ PILIH FORMAT KELAS ============ -->
                <div class="format-section">
                    <div class="section-title">
                        Pilih Format Kelas <span>*</span>
                    </div>

                    <div class="format-grid">
                        <label class="format-option selected" onclick="selectFormat(this)">
                            <input type="radio" name="format_kelas" value="Online" checked>
                            <div class="format-content">
                                <span class="format-name">Online</span>
                                <span class="format-desc">Live via Zoom • Fleksibel</span>
                            </div>
                            <div class="format-check"></div>
                        </label>

                        <label class="format-option" onclick="selectFormat(this)">
                            <input type="radio" name="format_kelas" value="Semi Offline">
                            <div class="format-content">
                                <span class="format-name">Semi-Offline</span>
                                <span class="format-desc">6 online + 2 tatap muka di lab</span>
                            </div>
                            <div class="format-check"></div>
                        </label>
                    </div>
                </div>

        <div class="form-group full" style="margin-top:30px;">
    <label>Program Kursus *</label>

    <select name="program_id" required>
        <option value="">Pilih Program</option>

        @foreach($programs as $program)
            <option value="{{ $program->id }}"
                data-nama="{{ $program->nama_program }}"
                data-biaya="Rp {{ number_format($program->biaya ?? 0, 0, ',', '.') }}"
                {{ (request('program_id') == $program->id) || (old('program_id') == $program->id) || ($selectedProgram?->id == $program->id) ? 'selected' : '' }}>
                {{ $program->nama_program }}
            </option>
        @endforeach
    </select>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.querySelector('select[name="program_id"]');
    if (!select) return;

    function updateHeader() {
        const opt = select.options[select.selectedIndex];
        if (opt && opt.value) {
            document.getElementById('header-program-name').textContent = opt.dataset.nama || opt.text;
            document.getElementById('header-program-biaya').textContent = opt.dataset.biaya || '-';
        }
    }

    select.addEventListener('change', updateHeader);
    updateHeader();
});
</script>


                <div class="btn-area">
                    <button type="submit" class="btn">
                        Lanjutkan →
                    </button>
                </div>

            </form>
        </div>

    </div>

    <!-- ============ JAVASCRIPT ============ -->
    <script>
        function selectFormat(element) {
            // Hapus class selected dari semua option
            document.querySelectorAll('.format-option').forEach(opt => {
                opt.classList.remove('selected');
            });

            // Tambah class selected ke yang diklik
            element.classList.add('selected');

            // Check radio button di dalamnya
            const radio = element.querySelector('input[type="radio"]');
            radio.checked = true;
        }

        // Tambahan: biar bisa diklik juga lewat radio button langsung
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.format-option input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Hapus semua selected
                    document.querySelectorAll('.format-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    // Tambah ke parent
                    this.closest('.format-option').classList.add('selected');
                });
            });
        });
    </script>

</body>
</html>