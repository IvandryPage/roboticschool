<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentKelas;
use App\Models\Invoice;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\Siswa;
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
        $request->validate([
            'metode'           => 'required',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $biayaProgram = $pendaftaran->program?->biaya ?? 0;

        $invoice = Invoice::firstOrCreate(
            ['pendaftaran_id' => $pendaftaran->id],
            [
                'id'         => (string) Str::uuid(),
                'no_invoice' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'total'      => $biayaProgram,
                'status'     => 'unpaid',
            ]
        );

        $buktiPath = $request->file('bukti_pembayaran')
            ->store('bukti-pembayaran', 'public');

        Pembayaran::create([
            'id'                => (string) Str::uuid(),
            'invoice_id'        => $invoice->id,
            'nominal'           => $biayaProgram,
            'metode_pembayaran' => $request->metode,
            'bukti_file'        => $buktiPath,
            'status'            => 'Pending',
        ]);

        $invoice->update(['status' => 'menunggu_verifikasi']);
        $pendaftaran->update(['status' => 'lunas']);

        // Redirect ke buat akun — bukan ke sukses lagi
        return redirect()->route('pendaftaran.buat-akun', $pendaftaran->id);
    }
}
