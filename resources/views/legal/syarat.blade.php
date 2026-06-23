<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Syarat & Ketentuan - AI for Robotics</title>

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
            background: #f0f7fe;
            color: #0f4c81;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.4rem 1rem;
            border-radius: 60px;
            border: 1px solid #dce9f5;
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
            border-left: 4px solid #0f4c81;
        }

        .section {
            margin-bottom: 1.5rem;
        }

        .section h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0b1a33;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .section h2 .num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #0f4c81;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .section p {
            color: #64748b;
            line-height: 1.8;
            font-size: 0.92rem;
            padding-left: 2.7rem;
        }

        .section ul {
            padding-left: 3.2rem;
            margin-top: 0.35rem;
            list-style: none;
        }

        .section ul li {
            color: #64748b;
            line-height: 1.8;
            font-size: 0.9rem;
            padding: 0.15rem 0;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
        }

        .section ul li::before {
            content: "•";
            color: #0f4c81;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .section ul li strong {
            color: #0b1a33;
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

            .section p {
                padding-left: 0;
            }

            .section ul {
                padding-left: 1.2rem;
            }

            .section h2 .num {
                width: 24px;
                height: 24px;
                font-size: 0.65rem;
            }

            .content .intro {
                font-size: 0.92rem;
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

            .section p {
                font-size: 0.85rem;
            }

            .section ul li {
                font-size: 0.85rem;
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
                    <i class="fas fa-file-contract"></i>
                </div>
                <h1>Syarat &amp; Ketentuan</h1>
                <span class="badge"><i class="fas fa-check-circle"></i> Berlaku 2026</span>
            </div>

            <!-- ====== BREADCRUMB ====== -->
            <div class="breadcrumb">
                <a href="/"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-right"></i>
                <a href="/daftar">Pendaftaran</a>
                <i class="fas fa-chevron-right"></i>
                <span>Syarat &amp; Ketentuan</span>
            </div>

            <!-- ====== CONTENT ====== -->
            <div class="content">

                <div class="intro">
                    <i class="fas fa-info-circle" style="color:#0f4c81;margin-right:0.5rem;"></i>
                    Dengan menggunakan layanan ini, Anda setuju untuk mematuhi semua aturan yang berlaku.
                    Harap baca dengan saksama sebelum melanjutkan pendaftaran.
                </div>

                <!-- Section 1 -->
                <div class="section">
                    <h2><span class="num">1</span> Data &amp; Identitas</h2>
                    <p>Pendaftaran hanya dapat dilakukan dengan data yang valid, benar, dan dapat dipertanggungjawabkan sesuai dengan identitas resmi (KTP / Kartu Pelajar).</p>
                    <ul>
                        <li>Data yang tidak valid akan <strong>ditolak</strong> tanpa pemberitahuan.</li>
                        <li>Perubahan data hanya dapat dilakukan melalui <strong>CS</strong> maksimal 24 jam setelah pendaftaran.</li>
                    </ul>
                </div>

                <!-- Section 2 -->
                <div class="section">
                    <h2><span class="num">2</span> Pembayaran &amp; Refund</h2>
                    <p>Biaya pendaftaran bersifat <strong>non-refundable</strong> kecuali terdapat kebijakan khusus dari pihak penyelenggara.</p>
                    <ul>
                        <li>Pembayaran wajib dilakukan sebelum <strong>batas waktu</strong> yang ditentukan.</li>
                        <li>Setiap pelanggaran berakibat <strong>pembatalan pendaftaran</strong> tanpa refund.</li>
                    </ul>
                </div>

                <!-- Section 3 -->
                <div class="section">
                    <h2><span class="num">3</span> Kebijakan &amp; Keputusan</h2>
                    <p>Semua keputusan admin / penyelenggara bersifat <strong>final</strong> dan tidak dapat diganggu gugat.</p>
                    <ul>
                        <li>Peserta wajib mengikuti semua <strong>aturan akademik</strong> yang berlaku selama program berlangsung.</li>
                        <li>Pelanggaran serius dapat mengakibatkan <strong>diskualifikasi</strong> dari program.</li>
                    </ul>
                </div>

                <!-- Section 4 -->
                <div class="section">
                    <h2><span class="num">4</span> Privasi &amp; Keamanan</h2>
                    <p>Kami berkomitmen untuk menjaga kerahasiaan data pribadi Anda sesuai dengan kebijakan privasi yang berlaku.</p>
                    <ul>
                        <li>Data hanya akan digunakan untuk <strong>keperluan administratif</strong> dan akademik.</li>
                        <li>Data tidak akan <strong>dibagikan</strong> ke pihak ketiga tanpa izin tertulis.</li>
                    </ul>
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
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

        </div>

    </div>

</body>
</html>