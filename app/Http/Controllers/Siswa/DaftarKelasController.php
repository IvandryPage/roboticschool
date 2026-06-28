<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\EnrollmentKelas;
use App\Models\Invoice;
use App\Models\Kelas;
use App\Models\ProgramKursus;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DaftarKelasController extends Controller
{
    /**
     * Tampilkan program yang tersedia untuk siswa yang sudah punya akun.
     * Exclude program yang sudah di-enroll siswa ini.
     */
    public function index(): View
    {
        $siswa = Siswa::where('user_id', auth()->id())->with('kelas')->firstOrFail();

        $kelasEnrolled = $siswa->kelas()->pluck('kelas.id');

        $programs = ProgramKursus::where('status_tampil', true)
            ->with([
                'batches' => fn ($q) => $q->where('status_aktif', true)
                    ->with(['kelas' => fn ($q) => $q->whereNotIn('id', $kelasEnrolled)])
                    ->orderBy('tanggal_mulai'),
            ])
            ->get()
            ->filter(fn ($program) => $program->batches
                ->filter(fn ($batch) => $batch->kelas->isNotEmpty())
                ->isNotEmpty()
            );

        return view('siswa.daftar-kelas.index', compact('programs', 'siswa'));
    }

    /**
     * Proses pendaftaran kelas baru.
     * Siswa existing tidak perlu review dokumen — langsung buat invoice.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'kelas_id'          => ['required', 'uuid', 'exists:kelas,id'],
            'bukti_pembayaran'  => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $siswa = Siswa::where('user_id', auth()->id())->firstOrFail();
        $kelas = Kelas::with('batch.program')->findOrFail($request->kelas_id);

        // Guard: cegah double enrollment
        $sudahEnroll = EnrollmentKelas::where('siswa_id', $siswa->id)
            ->where('kelas_id', $kelas->id)
            ->exists();

        if ($sudahEnroll) {
            return back()->with('error', 'Kamu sudah terdaftar di kelas ini.');
        }

        // Guard: cek kapasitas kelas
        $jumlahSiswa = EnrollmentKelas::where('kelas_id', $kelas->id)->count();
        if ($jumlahSiswa >= $kelas->kapasitas) {
            return back()->with('error', 'Kelas ini sudah penuh. Silakan pilih kelas lain.');
        }

        // Simpan bukti pembayaran
        $pathBukti = $request->file('bukti_pembayaran')
            ->store('bukti-daftar-ulang', 'public');

        // Buat Invoice — Admin masih harus verifikasi
        $invoice = Invoice::create([
            'id'                 => (string) Str::uuid(),
            'pendaftaran_id'     => null,       // siswa existing — tidak punya pendaftaran baru
            'nomor_invoice'      => 'INV-RE-' . strtoupper(Str::random(8)),
            'nominal'            => $kelas->batch->program->biaya ?? 0,
            'status_pembayaran'  => 'Menunggu Verifikasi',
            'tanggal_jatuh_tempo'=> now()->addDays(3),
        ]);

        // Buat record Pembayaran
        \App\Models\Pembayaran::create([
            'id'                => (string) Str::uuid(),
            'invoice_id'        => $invoice->id,
            'nominal'           => $invoice->nominal,
            'metode_pembayaran' => 'Transfer Manual',
            'status'            => 'Menunggu Verifikasi',
            'bukti_file'        => $pathBukti,
        ]);

        // EnrollmentKelas dibuat dengan status Pending — aktif setelah pembayaran verified
        EnrollmentKelas::create([
            'id'               => (string) Str::uuid(),
            'kelas_id'         => $kelas->id,
            'siswa_id'         => $siswa->id,
            'tanggal_bergabung'=> now(),
            'status'           => 'Pending',
        ]);

        return redirect()
            ->route('siswa.daftar-kelas.status')
            ->with('success', 'Pendaftaran kelas berhasil! Menunggu verifikasi pembayaran oleh Admin. Invoice: ' . $invoice->nomor_invoice);
    }

    /** Halaman tracking status pendaftaran kelas ulang */
    public function status(): View
    {
        $siswa = Siswa::where('user_id', auth()->id())
            ->with([
                'enrollmentKelas' => fn ($q) => $q->where('status', 'Pending')
                    ->with('kelas.batch.program'),
            ])
            ->firstOrFail();

        return view('siswa.daftar-kelas.status', compact('siswa'));
    }
}
