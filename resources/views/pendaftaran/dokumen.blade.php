<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Upload Dokumen</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f5f7fb;
    padding:40px;
}

.container{
    max-width:900px;
    margin:auto;
}

.card{
    background:#fff;
    border-radius:18px;
    padding:30px;
    box-shadow:0 2px 12px rgba(0,0,0,.05);
    margin-bottom:24px;
}

.program-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.program-left{
    display:flex;
    align-items:center;
    gap:18px;
}

.program-icon{
    width:72px;
    height:72px;
    border-radius:18px;
    background:linear-gradient(135deg,#0f2745,#0ea5e9);

    display:flex;
    justify-content:center;
    align-items:center;

    flex-shrink:0;

    box-shadow:0 8px 18px rgba(14,165,233,.25);
}

.program-icon i{
    font-size:34px;
    color:#fff;
}

.program-info small{
    display:block;
    color:#64748b;
    font-size:14px;
    font-weight:600;
    letter-spacing:.5px;
    margin-bottom:4px;
}

.program-info h2{
    font-size:22px;
    font-weight:700;
    color:#0f172a;
}

.biaya{
    text-align:right;
}

.biaya small{
    display:block;
    color:#64748b;
    font-size:15px;
    margin-bottom:5px;
}

.biaya h2{
    color:#0ea5e9;
    font-size:22px;
    font-weight:700;
}


.stepper{
    display:flex;
    align-items:center;
}

.step{
    flex:1;
    text-align:center;
}

.circle{
    width:44px;
    height:44px;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:auto;
    font-weight:bold;
    border:2px solid #d8dee8;
    color:#999;
}

.done{
    background:#10b981;
    color:#fff;
    border:none;
}

.active{
    background:#06b6d4;
    color:#fff;
    border:none;
}

.line{
    height:2px;
    flex:1;
    background:#d8dee8;
    margin:0 10px;
}

.title h1{
    color:#1f2937;
    margin-bottom:8px;
}

.title p{
    color:#6b7280;
    margin-bottom:30px;
}

.upload-box{
    border:2px dashed #d7dee8;
    border-radius:14px;
    padding:25px;
    margin-bottom:22px;
    cursor:pointer;
    transition:.2s;
}

.upload-box:hover{
    border-color:#06b6d4;
}

.upload-content{
    display:flex;
    align-items:center;
    gap:18px;
}

.upload-icon{
    font-size:34px;
    color:#94a3b8;
}

.upload-content h3{
    color:#374151;
    margin-bottom:5px;
}

.upload-content small{
    color:#6b7280;
}

.notice{
    margin-top:30px;
    background:#fff8e7;
    border:1px solid #f4d58d;
    border-radius:14px;
    padding:18px;
    display:flex;
    gap:15px;
}

.notice-icon{
    font-size:24px;
}

.notice h4{
    color:#b45309;
    margin-bottom:6px;
}

.notice p{
    color:#92400e;
    font-size:14px;
}

.btn-area{
    display:flex;
    justify-content:space-between;
    margin-top:35px;
}

.btn{
    padding:14px 28px;
    border-radius:12px;
    font-size:15px;
    cursor:pointer;
    border:none;
}

.btn-back{
    background:#fff;
    border:1px solid #d6dbe5;
}

.btn-next{
    background:#06b6d4;
    color:#fff;
}

.btn-next:hover{
    background:#0891b2;
}

input[type=file]{
    display:none;
}

.error-box{
    background:#fee2e2;
    color:#b91c1c;
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
}

@media(max-width:768px){

.program-header{
flex-direction:column;
gap:20px;
align-items:flex-start;
}

.btn-area{
flex-direction:column;
gap:15px;
}

.btn{
width:100%;
}

}

</style>

</head>
<body>

<div class="container">

<div class="card program-header">

    <div class="program-left">

        <div class="program-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>

        <div class="program-info">
            <small>MENDAFTAR PROGRAM</small>
            <h2>Arduino Basic</h2>
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

<div class="line"></div>

<div class="step">
<div class="circle active">2</div>
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
<h1>Upload Dokumen</h1>
<p>Lampirkan dokumen pendukung pendaftaran</p>
</div>

@if($errors->any())
<div class="error-box">
<ul>
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form method="POST"
action="{{ route('pendaftaran.dokumen.store',$pendaftaran->id) }}"
enctype="multipart/form-data">

@csrf

<label>

<div class="upload-box">

<input
type="file"
name="dokumen_identitas"
required>

<div class="upload-content">

<div class="upload-icon">⬆</div>

<div>
<h3>KTP / Kartu Pelajar *</h3>
<small>JPG / PNG / PDF • Maks 5 MB</small>
</div>

</div>

</div>

</label>

<label>

<div class="upload-box">

<input
type="file"
name="pas_foto"
required>

<div class="upload-content">

<div class="upload-icon">⬆</div>

<div>
<h3>Pas Foto 3x4 *</h3>
<small>JPG / PNG • Maks 2 MB</small>
</div>

</div>

</div>

</label>

<label>

<div class="upload-box">

<input
type="file"
name="dokumen_pendukung">

<div class="upload-content">

<div class="upload-icon">⬆</div>

<div>
<h3>Bukti Status (Opsional)</h3>
<small>Kartu Mahasiswa / Surat Kerja / dll</small>
</div>

</div>

</div>

</label>

<div class="notice">

<div class="notice-icon">🛡</div>

<div>

<h4>Privasi Data Terjamin</h4>

<p>
Semua dokumen yang diupload hanya digunakan untuk proses
verifikasi pendaftaran dan tidak dibagikan kepada pihak lain.
</p>

</div>

</div>

<div class="btn-area">

<button
type="button"
class="btn btn-back"
onclick="history.back()">

← Kembali

</button>

<button
type="submit"
class="btn btn-next">

Lanjutkan →

</button>

</div>

</form>

</div>

</div>

</body>
</html>