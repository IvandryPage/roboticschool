<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\EnrollmentKelas;
use App\Models\Invoice;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\Siswa;
use Carbon\Carbon;
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
            'metode'             => 'required',
            'bukti_pembayaran'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Biaya dari program (tidak hardcoded)
        $biayaProgram = $pendaftaran->program?->biaya ?? 0;

        // Buat atau ambil invoice
        $invoice = Invoice::firstOrCreate(
            ['pendaftaran_id' => $pendaftaran->id],
            [
                'id'         => (string) Str::uuid(),
                'no_invoice' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'total'      => $biayaProgram,
                'status'     => 'unpaid',
            ]
        );

        // Simpan bukti pembayaran (FR-35)
        $buktPath = $request->file('bukti_pembayaran')
            ->store('bukti-pembayaran', 'public');

        // Buat pembayaran
        Pembayaran::create([
            'id'                => (string) Str::uuid(),
            'invoice_id'        => $invoice->id,
            'nominal'           => $biayaProgram,
            'metode_pembayaran' => $request->metode,
            'bukti_file'        => $buktPath,
            'status'            => 'Pending',
        ]);

        // Update status invoice & pendaftaran
        $invoice->update(['status' => 'menunggu_verifikasi']);
        $pendaftaran->update(['status' => 'lunas']);

        // Setelah bayar: daftarkan siswa ke kelas otomatis
        // Cari kelas aktif/tersedia untuk program & batch ini
        $siswa = Siswa::where('user_id', auth()->id())->first();

        if ($siswa) {
            $kelas = Kelas::whereHas('batch', fn($q) =>
                    $q->where('program_id', $pendaftaran->program_id)
                      ->where('status_aktif', true)
                )
                ->whereDoesntHave('enrollmentKelas', fn($q) =>
                    $q->where('siswa_id', $siswa->id)
                )
                ->first();

            if ($kelas) {
                EnrollmentKelas::firstOrCreate(
                    ['kelas_id' => $kelas->id, 'siswa_id' => $siswa->id],
                    [
                        'id'                => (string) Str::uuid(),
                        'kelas_id'          => $kelas->id,
                        'siswa_id'          => $siswa->id,
                        'tanggal_bergabung' => now(),
                        'status'            => 'Terdaftar',
                    ]
                );
            }
        }

        return redirect()->route('pendaftaran.success', $pendaftaran->id);
    }
}
