<?php

use Illuminate\Support\Facades\Route;

// --- RUTE ASLI KELOMPOK ---
Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';


// --- RUTE REVIEW UI INVOICE (LANGSUNG DARI WEB.PHP) ---
Route::get('/dashboard/invoice', function () {
    $html = <<<HTML
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Review UI Invoice - PBI-145</title>
        <style>
            * { box-sizing: border-box; }
            body {
                font-family: 'Segoe UI', system-ui, sans-serif;
                background: #f8fafc;
                margin: 0;
                padding: 40px 20px;
            }
            .wrapper { max-width: 560px; margin: 0 auto; }
            .invoice-card {
                background: #fff;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                border: 1px solid #e2e8f0;
            }
            .invoice-header {
                background: linear-gradient(135deg, #00b4d8, #0077b6);
                color: #fff;
                padding: 24px;
            }
            .invoice-header .label {
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                opacity: 0.9;
                margin: 0 0 4px 0;
            }
            .invoice-header .no-invoice {
                font-size: 24px;
                font-weight: 700;
                margin: 0;
            }
            .invoice-body { padding: 24px; }
            .row {
                display: flex;
                justify-content: space-between;
                padding: 12px 0;
                border-bottom: 1px solid #f1f5f9;
                font-size: 14px;
            }
            .row:last-of-type { border-bottom: none; }
            .row .key { color: #64748b; }
            .row .val { color: #0f172a; font-weight: 600; text-align: right; }
            .total-box {
                background: #e6f7ff;
                border: 1px solid #bae7ff;
                border-radius: 8px;
                padding: 16px;
                margin-top: 16px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .total-box .key { font-size: 13px; color: #0050b3; font-weight: 600; }
            .total-box .val { font-size: 20px; font-weight: 700; color: #0050b3; }
            .status-pill {
                display: inline-block;
                background: #fef3c7;
                color: #92400e;
                font-size: 12px;
                font-weight: 700;
                padding: 4px 10px;
                border-radius: 999px;
            }
            .btn-dashboard {
                display: block;
                width: 100%;
                background: #00b4d8;
                color: white;
                text-align: center;
                text-decoration: none;
                padding: 14px;
                border-radius: 8px;
                font-weight: 600;
                margin-top: 20px;
                transition: background 0.2s;
            }
            .btn-dashboard:hover {
                background: #0077b6;
            }
            .footnote {
                font-size: 12px;
                color: #94a3b8;
                text-align: center;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="invoice-card">
                <div class="invoice-header">
                    <p class="label">Nomor Invoice</p>
                    <p class="no-invoice">INV-53631814</p>
                </div>
                <div class="invoice-body">
                    <div class="row">
                        <span class="key">No. Referensi Pendaftaran</span>
                        <span class="val">RBN-53631814</span>
                    </div>
                    <div class="row">
                        <span class="key">Program Kursus</span>
                        <span class="val">IoT Development</span>
                    </div>
                    <div class="row">
                        <span class="key">Tanggal Terbit</span>
                        <span class="val">21 Juni 2026</span>
                    </div>
                    <div class="row">
                        <span class="key">Status Pembayaran</span>
                        <span class="val"><span class="status-pill">Pending</span></span>
                    </div>

                    <div class="total-box">
                        <span class="key">Total Tagihan</span>
                        <span class="val">Rp 1.500.000</span>
                    </div>

                    <a href="/dashboard" class="btn-dashboard">Lanjut ke Dashboard &rarr;</a>
                </div>
            </div>
            <p class="footnote">Pratinjau Halaman Invoice &middot; PBI-145</p>
        </div>
    </body>
    </html>
    HTML;

    return response($html)->header('Content-Type', 'text/html');
});