<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: Inter, sans-serif;
        }

        body{
            background:#f5f7fb;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:30px;
        }

        .success-card{
            width:100%;
            max-width:620px;
            background:#fff;
            border-radius:28px;
            padding:60px;
            text-align:center;
            box-shadow:
                0 10px 25px rgba(0,0,0,.04),
                0 25px 60px rgba(0,0,0,.05);
        }

        .success-icon-wrap{
            width:110px;
            height:110px;
            margin:auto;
            border-radius:999px;
            background:#dff8e9;
            display:flex;
            justify-content:center;
            align-items:center;
            margin-bottom:35px;
        }

        .success-icon{
            width:60px;
            height:60px;
            border-radius:999px;
            border:5px solid #0ea663;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .success-icon svg{
            width:30px;
            height:30px;
            color:#0ea663;
        }

        h1{
            font-size:52px;
            font-weight:700;
            color:#111827;
            margin-bottom:20px;
            line-height:1.2;
        }

        .desc{
            color:#6b7280;
            font-size:24px;
            line-height:1.8;
            margin-bottom:40px;
        }

        .desc strong{
            color:#374151;
        }

        .number-box{
            background:#eafcff;
            border-radius:18px;
            padding:28px;
            text-align:left;
            margin-bottom:35px;
        }

        .number-label{
            color:#64748b;
            font-size:20px;
            margin-bottom:12px;
        }

        .number-value{
            color:#0891b2;
            font-size:38px;
            font-weight:700;
        }

        .btn-dashboard{
            display:flex;
            justify-content:center;
            align-items:center;
            gap:10px;
            width:100%;
            background:#06b6d4;
            color:white;
            text-decoration:none;
            padding:20px;
            border-radius:16px;
            font-size:24px;
            font-weight:600;
            transition:.3s;
        }

        .btn-dashboard:hover{
            background:#0891b2;
        }

        .btn-dashboard svg{
            width:24px;
            height:24px;
        }

        @media(max-width:768px){

            .success-card{
                padding:35px;
            }

            h1{
                font-size:34px;
            }

            .desc{
                font-size:18px;
            }

            .number-value{
                font-size:24px;
            }

            .btn-dashboard{
                font-size:18px;
            }
        }
    </style>
</head>
<body>

<div class="success-card">

    <div class="success-icon-wrap">

        <div class="success-icon">
            <svg fill="none"
                 stroke="currentColor"
                 stroke-width="3"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M5 13l4 4L19 7"/>
            </svg>
        </div>

    </div>

    <h1>
        Pendaftaran Berhasil! 🎉
    </h1>

    <div class="desc">
        Selamat! Kamu telah berhasil mendaftar program
        <strong>AI for Robotics</strong>.
        Tim kami akan menghubungi via email dalam 1×24 jam.
    </div>

    <div class="number-box">
        <div class="number-label">
            Nomor Pendaftaran:
        </div>

        <div class="number-value">
            {{ $pendaftaran->no_referensi ?? 'REG-20260622-ABC123' }}
        </div>
    </div>

    <a href="{{ route('dashboard') }}" class="btn-dashboard">

        Lanjut ke Dashboard

        <svg fill="none"
             stroke="currentColor"
             stroke-width="2"
             viewBox="0 0 24 24">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>

    </a>

</div>

</body>
</html>