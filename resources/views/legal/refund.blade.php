<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kebijakan Refund - AI for Robotics</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Font Awesome 6 (Free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        /* ---------- RESET & BASE ---------- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }

        .container {
            max-width: 820px;
            width: 100%;
        }

        /* ---------- CARD ---------- */
        .card {
            background: #ffffff;
            border-radius: 24px;
            padding: 2.5rem 3rem;
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.06), 0 4px 12px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* ---------- HEADER ---------- */
        .header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 1.75rem;
        }

        .header-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(145deg, #0b1a33, #1a3a5c);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 6px 16px rgba(15, 76, 129, 0.20);
        }

        .header h1 {
            font-size: 1.65rem;
            font-weight: 800;
            color: #0b1a33;
            letter-spacing: -0.02em;
        }

        .header .badge {
            margin-left: auto;
            background: #fef3c7;
            color: #b45309;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.4rem 1rem;
            border-radius: 60px;
            border: 1px solid #fde68a;
            white-space: nowrap;
        }

        /* ---------- BREADCRUMB ---------- */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #94a3b8;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .breadcrumb a {
            color: #0f4c81;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb i {
            font-size: 0.6rem;
            color: #cbd5e1;
        }

        /* ---------- CONTENT ---------- */
        .content {
            padding: 0.25rem 0;
        }

        .content .intro {
            font-size: 1rem;
            color: #475569;
            line-height: 1.8;
            margin-bottom: 1.75rem;
            padding: 1rem 1.25rem;
            background: #f8fafc;
            border-radius: 14px;
            border-left: 4px solid #b45309;
        }

        /* ---------- TIMELINE / STEP ---------- */
        .timeline {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 1.25rem;
            background: #fafcff;
            border-radius: 14px;
            border: 1px solid #eef2f6;
            transition: all 0.2s ease;
        }

        .timeline-item:hover {
            border-color: #d1d9e6;
            background: #f8fafc;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            color: #fff;
        }

        .timeline-icon.blue {
            background: #0f4c81;
        }

        .timeline-icon.green {
            background: #0b7e5e;
        }

        .timeline-icon.orange {
            background: #b45309;
        }

        .timeline-icon.purple {
            background: #6d28d9;
        }

        .timeline-body {
            flex: 1;
        }

        .timeline-body h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0b1a33;
            margin-bottom: 0.15rem;
        }

        .timeline-body p {
            font-size: 0.88rem;
            color: #64748b;
            line-height: 1.6;
        }

        .timeline-body .label {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 0.15rem 0.6rem;
            border-radius: 60px;
            margin-top: 0.3rem;
        }

        .label.success {
            background: #d1fae5;
            color: #065f46;
        }

        .label.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .label.info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* ---------- INFO BOX ---------- */
        .info-box {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: #fef3c7;
            border-radius: 14px;
            border: 1px solid #fde68a;
            margin-top: 1.5rem;
        }

        .info-box i {
            color: #b45309;
            font-size: 1.1rem;
            margin-top: 0.1rem;
        }

        .info-box p {
            font-size: 0.9rem;
            color: #78350f;
            line-height: 1.6;
        }

        /* ---------- DIVIDER ---------- */
        .divider {
            height: 1px;
            background: #eef2f6;
            margin: 1.5rem 0;
        }

        /* ---------- FOOTER ---------- */
        .footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f1f5f9;
            margin-top: 1rem;
        }

        .footer .version {
            font-size: 0.78rem;
            color: #94a3b8;
        }

        .footer .version i {
            margin-right: 0.3rem;
        }

        /* ====== TOMBOL KEMBALI ====== */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.8rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            background: #0f4c81;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 14px rgba(15, 76, 129, 0.20);
        }

        .btn-back:hover {
            background: #0b3d69;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(15, 76, 129, 0.25);
        }

        .btn-back:active {
            transform: translateY(0);
        }

        .btn-back i {
            font-size: 0.85rem;
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 700px) {
            .card {
                padding: 1.5rem 1.5rem;
                border-radius: 20px;
            }

            .header {
                flex-wrap: wrap;
            }

            .header h1 {
                font-size: 1.35rem;
            }

            .header .badge {
                margin-left: 0;
                font-size: 0.6rem;
            }

            .timeline-item {
                padding: 0.85rem 1rem;
            }

            .footer {
                flex-direction: column-reverse;
                align-items: stretch;
                text-align: center;
            }

            .btn-back {
                justify-content: center;
                padding: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 1rem 0.75rem;
            }

            .card {
                padding: 1.25rem 1rem;
                border-radius: 18px;
            }

            .header-icon {
                width: 44px;
                height: 44px;
                font-size: 1.2rem;
            }

            .header h1 {
                font-size: 1.15rem;
            }

            .breadcrumb {
                font-size: 0.7rem;
            }

            .timeline-icon {
                width: 34px;
                height: 34px;
                font-size: 0.9rem;
            }

            .timeline-body h3 {
                font-size: 0.85rem;
            }

            .timeline-body p {
                font-size: 0.82rem;
            }

            .info-box p {
                font-size: 0.82rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">

        <div class="card">

            <!-- ====== HEADER ====== -->
            <div class="header">
                <div class="header-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <h1>Kebijakan Refund</h1>
                <span class="badge"><i class="fas fa-clock"></i> Berlaku 2026</span>
            </div>

            <!-- ====== BREADCRUMB ====== -->
            <div class="breadcrumb">
                <a href="/"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-right"></i>
                <a href="/daftar">Pendaftaran</a>
                <i class="fas fa-chevron-right"></i>
                <span>Kebijakan Refund</span>
            </div>

            <!-- ====== CONTENT ====== -->
            <div class="content">

                <div class="intro">
                    <i class="fas fa-info-circle" style="color:#b45309;margin-right:0.5rem;"></i>
                    Pengembalian dana hanya berlaku dalam kondisi tertentu yang ditentukan oleh pihak penyelenggara.
                    Harap baca kebijakan ini dengan saksama sebelum melakukan pembayaran.
                </div>

                <!-- ====== TIMELINE / STEP REFUND ====== -->
                <div class="timeline">

                    <div class="timeline-item">
                        <div class="timeline-icon blue">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="timeline-body">
                            <h3>Batas Waktu Pengajuan</h3>
                            <p>Permintaan refund harus diajukan maksimal <strong>3 hari</strong> setelah pembayaran.</p>
                            <span class="label warning"><i class="fas fa-exclamation-triangle"></i> Perhatikan Deadline!</span>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-icon orange">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="timeline-body">
                            <h3>Biaya Administrasi</h3>
                            <p>Biaya administrasi sebesar <strong>Rp 50.000</strong> tidak dapat dikembalikan dalam kondisi apapun.</p>
                            <span class="label info"><i class="fas fa-info-circle"></i> Non-Refundable</span>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-icon purple">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="timeline-body">
                            <h3>Proses Refund</h3>
                            <p>Proses pengembalian dana membutuhkan waktu <strong>7–14 hari kerja</strong> setelah pengajuan disetujui.</p>
                            <span class="label success"><i class="fas fa-check-circle"></i> Diproses</span>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-icon green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="timeline-body">
                            <h3>Konfirmasi Refund</h3>
                            <p>Anda akan menerima <strong>email konfirmasi</strong> setelah refund berhasil diproses.</p>
                            <span class="label success"><i class="fas fa-envelope"></i> Notifikasi Email</span>
                        </div>
                    </div>

                </div>

                <!-- ====== INFO BOX ====== -->
                <div class="info-box">
                    <i class="fas fa-lightbulb"></i>
                    <p>
                        <strong>💡 Tips:</strong> Pastikan Anda membaca seluruh kebijakan sebelum melakukan pembayaran.
                        Jika ada pertanyaan, hubungi <strong>CS kami</strong> di +62 812-3456-7890.
                    </p>
                </div>

            </div>

            <!-- ====== DIVIDER ====== -->
            <div class="divider"></div>

            <!-- ====== FOOTER ====== -->
            <div class="footer">
                <span class="version">
                    <i class="fas fa-clock"></i> Versi 2.0 &bull; Terakhir diperbarui: Juni 2026
                </span>
                <a href="javascript:history.back()" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali ke Pembayaran
                </a>
            </div>

        </div>

    </div>

</body>
</html>