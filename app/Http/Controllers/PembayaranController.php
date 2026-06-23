<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    public function index(Pendaftaran $pendaftaran)
    {
        return view('pembayaran.index', compact('pendaftaran'));
    }

    public function store(Request $request, Pendaftaran $pendaftaran)
    {
        // 1. validasi metode
        $request->validate([
            'metode' => 'required'
        ]);

        // 2. cek apakah invoice sudah ada
        $invoice = Invoice::where('pendaftaran_id', $pendaftaran->id)->first();

        // 3. kalau belum ada, buat invoice baru
        if (!$invoice) {
            $invoice = Invoice::create([
                'id' => (string) Str::uuid(),
                'pendaftaran_id' => $pendaftaran->id,
                'no_invoice' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'total' => 3525000,
                'status' => 'unpaid',
            ]);
        }

        // 4. buat pembayaran
        Pembayaran::create([
            'id' => (string) Str::uuid(),
            'invoice_id' => $invoice->id,
            'nominal' => 3525000,
            'metode_pembayaran' => $request->metode,
            'status' => 'pending',
        ]);

        // 5. lanjut ke halaman success
         return redirect()->route(
            'pendaftaran.success',
            $pendaftaran->id
        );
    }
}