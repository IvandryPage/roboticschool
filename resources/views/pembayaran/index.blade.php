<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pembayaran</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
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
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .05);
            margin-bottom: 24px;
        }

        .program-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .program-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .program-icon {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            background: linear-gradient(135deg, #0f172a, #0891b2);

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;

            box-shadow: 0 8px 20px rgba(14, 165, 233, .25);
        }

        .program-info h3 {
            color: #94a3b8;
            font-size: 13px;
            letter-spacing: .5px;
        }

        .program-info h2 {
            color: #1f2937;
            margin-top: 4px;
        }

        .biaya {
            text-align: right;
        }

        .biaya small {
            color: #94a3b8;
        }

        .biaya h2 {
            color: #06b6d4;
            margin-top: 5px;
        }

        .stepper {
            display: flex;
            align-items: center;
        }

        .step {
            flex: 1;
            text-align: center;
        }

        .circle {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            font-weight: bold;
            border: 2px solid #dbe3ef;
            color: #94a3b8;
        }

        .done {
            background: #10b981;
            color: white;
            border: none;
        }

        .active {
            background: #06b6d4;
            color: white;
            border: none;
        }

        .line {
            flex: 1;
            height: 3px;
            background: #dbe3ef;
            margin: 0 10px;
        }

        .line-done {
            background: #10b981;
        }

        .step small {
            display: block;
            margin-top: 10px;
            font-weight: 600;
        }

        .title h1 {
            color: #1f2937;
            margin-bottom: 8px;
        }

        .title p {
            color: #64748b;
            margin-bottom: 25px;
        }

        .summary {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            color: #64748b;
        }

        .summary-row strong {
            color: #1f2937;
        }

        .total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 30px;
            font-weight: bold;
            color: #06b6d4;
        }

        .method {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 14px;
            cursor: pointer;
            transition: .2s;
            display: block;
        }

        .method:hover {
            border-color: #06b6d4;
        }

        .method.active-method {
            background: #ecfeff;
            border-color: #06b6d4;
        }

        .method-wrap {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .method-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #64748b;
        }

        .method-title {
            font-weight: 600;
            color: #1f2937;
        }

        .method-sub {
            font-size: 14px;
            color: #64748b;
            margin-top: 4px;
        }

        .method input {
            display: none;
        }

        .agreement {
            background: #f8fafc;
            padding: 18px;
            border-radius: 14px;
            margin-top: 25px;
        }

        .agreement label {
            color: #475569;
        }

        .agreement a {
            color: #06b6d4;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-area {
            display: flex;
            justify-content: space-between;
            margin-top: 35px;
        }

        .btn {
            padding: 14px 30px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-back {
            background: white;
            border: 1px solid #dbe3ef;
        }

        .btn-next {
            background: #06b6d4;
            color: white;
        }

        .btn-next:hover {
            background: #0891b2;
        }

        @media(max-width:768px) {

            .program-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .btn-area {
                flex-direction: column;
                gap: 15px;
            }

            .btn {
                width: 100%;
            }

        }
    </style>
</head>
<<<<<<< HEAD

=======
<script>
document.addEventListener('DOMContentLoaded', function () {

    const methods = document.querySelectorAll('.method');

    methods.forEach(method => {
        method.addEventListener('click', function () {

            methods.forEach(m => m.classList.remove('active-method'));

            this.classList.add('active-method');

            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });

});
</script>
>>>>>>> 8a75dc455d1be3e29c9372bbfafb87453645237b
<body>

    <div class="container">

        <div class="card program-header">

            <div class="program-left">

                <div class="program-icon">

                    <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">

                        <path d="M22 10L12 5L2 10L12 15L22 10Z" />
                        <path d="M6 12V16C6 17.5 8.7 19 12 19C15.3 19 18 17.5 18 16V12" />

                    </svg>

                </div>

                <div class="program-info">
                    <h3>MENDAFTAR PROGRAM</h3>
                    <h2>{{ $pendaftaran->program->nama_program ?? 'AI for Robotics' }}</h2>
                </div>

            </div>

            <div class="biaya">
                <small>Biaya</small>
                <h2>Rp 3.500.000</h2>
            </div>

        </div>

        <div class="card">

            <div class="stepper">

                <div class="step">
                    <div class="circle done">✓</div>
                    <small>Data Diri</small>
                </div>

                <div class="line line-done"></div>

                <div class="step">
                    <div class="circle done">✓</div>
                    <small>Dokumen</small>
                </div>

                <div class="line"></div>

                <div class="step">
                    <div class="circle active">3</div>
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
                <h1>Pembayaran</h1>
                <p>Pilih metode pembayaran yang sesuai</p>
            </div>

            <div class="summary">

                <div class="summary-row">
                    <span>No Referensi</span>
                    <strong>{{ $pendaftaran->no_referensi }}</strong>
                </div>

                <div class="summary-row">
                    <span>Biaya Program</span>
                    <strong>Rp 3.500.000</strong>
                </div>

                <div class="summary-row">
                    <span>Biaya Admin</span>
                    <strong>Rp 25.000</strong>
                </div>

                <div class="summary-row total">
                    <span>Total</span>
                    <span>Rp 3.525.000</span>
                </div>

            </div>

            <form method="POST" action="{{ route('pembayaran.store', $pendaftaran->id) }}">

                @csrf

<<<<<<< HEAD
                <label class="method active-method">
                    <input type="radio" checked name="metode">
=======
<label class="method">
<input type="radio" name="metode" value="transfer" required>
>>>>>>> 8a75dc455d1be3e29c9372bbfafb87453645237b

                    <div class="method-wrap">

                        <div class="method-icon">🏦</div>

                        <div>
                            <div class="method-title">Transfer Bank</div>
                            <div class="method-sub">BCA, Mandiri, BNI, BRI</div>
                        </div>

                    </div>
                </label>

<<<<<<< HEAD
                <label class="method">
                    <input type="radio" name="metode">
=======
<label class="method">
<input type="radio" name="metode" value="virtual_account">
>>>>>>> 8a75dc455d1be3e29c9372bbfafb87453645237b

                    <div class="method-wrap">

                        <div class="method-icon">💳</div>

                        <div>
                            <div class="method-title">Virtual Account</div>
                            <div class="method-sub">Verifikasi otomatis</div>
                        </div>

                    </div>
                </label>

<<<<<<< HEAD
                <label class="method">
                    <input type="radio" name="metode">
=======
<label class="method">
<input type="radio" name="metode" value="ewallet">
>>>>>>> 8a75dc455d1be3e29c9372bbfafb87453645237b

                    <div class="method-wrap">

                        <div class="method-icon">📱</div>

                        <div>
                            <div class="method-title">E-Wallet</div>
                            <div class="method-sub">OVO, Dana, GoPay, ShopeePay</div>
                        </div>

                    </div>
                </label>

                <div class="agreement">

                    <label>
                        <input type="checkbox" required>

<<<<<<< HEAD
                        Saya menyetujui
                        <a href="#">Syarat & Ketentuan</a>
                        dan
                        <a href="#">Kebijakan Refund</a>
=======
Saya menyetujui
<a href="{{ route('syarat', $pendaftaran) }}">Syarat & Ketentuan</a>
dan
<a href="{{ route('refund', $pendaftaran) }}">Kebijakan Refund</a>
>>>>>>> 8a75dc455d1be3e29c9372bbfafb87453645237b

                    </label>

                </div>

                <div class="btn-area">

                    <button type="button" class="btn btn-back" onclick="history.back()">

                        ← Kembali

                    </button>

                    <button type="submit" class="btn btn-next">

                        Bayar & Selesaikan →

                    </button>

                </div>

            </form>

        </div>

    </div>

</body>

</html>