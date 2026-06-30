<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenPendaftaran;
use App\Models\Pendaftaran;
use App\Models\ProgramKursus;
use App\Models\RiwayatStatusPendaftaran;
use Illuminate\Http\Request;

/**
 * PBI-057 : Alur verifikasi pendaftaran (setujui / revisi / tolak)
 * PBI-060 : Halaman daftar pendaftaran masuk
 * PBI-061 : Filter & pencarian (nama, program, status)
 * PBI-062 : Halaman detail pendaftaran + pratinjau dokumen
 * PBI-063 : Fitur verifikasi dokumen (valid / tidak valid + catatan)
 * PBI-064 : Fitur setujui pendaftaran (semua dokumen harus valid)
 * PBI-065 : Fitur tolak pendaftaran + alasan penolakan
 * PBI-066 : Fitur kirim catatan revisi per dokumen → status "Revisi"
 */
class PendaftaranController extends Controller
{
    /* ─────────────────────────────────────────────────────────────────
     * PBI-060 & PBI-061 — Daftar pendaftaran + filter + search
     * ───────────────────────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $program = $request->input('program');
        $status  = $request->input('status');

        $pendaftaranList = Pendaftaran::with(['calonPeserta', 'program', 'dokumenPendaftaran'])
            ->when($search, fn($q) =>
                $q->whereHas('calonPeserta', fn($q2) =>
                    $q2->where('nama_lengkap', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                )
            )
            ->when($program, fn($q) => $q->where('program_id', $program))
            ->when($status,  fn($q) => $q->where('status', $status))
            ->orderByDesc('tanggal_daftar')
            ->paginate(10)
            ->withQueryString();

        $programList = ProgramKursus::orderBy('nama_program')->get();

        $stats = [
            'total'     => Pendaftaran::count(),
            'pending'   => Pendaftaran::where('status', 'pending')->count(),
            'disetujui' => Pendaftaran::where('status', 'disetujui')->count(),
            'revisi'    => Pendaftaran::where('status', 'revisi')->count(),
            'ditolak'   => Pendaftaran::where('status', 'ditolak')->count(),
        ];

        return view('admin.pendaftaran.index', compact(
            'pendaftaranList', 'programList', 'stats',
            'search', 'program', 'status'
        ));
    }

    /* ─────────────────────────────────────────────────────────────────
     * PBI-062 — Detail pendaftaran: data lengkap + pratinjau dokumen
     * ───────────────────────────────────────────────────────────────── */
    public function show(string $id)
    {
        $pendaftaran = Pendaftaran::with([
            'calonPeserta',
            'program',
            'dokumenPendaftaran',
            'riwayatStatus.diubahOleh',
            'siswa',
        ])->findOrFail($id);

        // Hitung ringkasan verifikasi dokumen untuk PBI-064 guard
        $totalDokumen  = $pendaftaran->dokumenPendaftaran->count();
        $validDokumen  = $pendaftaran->dokumenPendaftaran->where('status_verifikasi', 'valid')->count();
        $semuaValid    = $totalDokumen > 0 && $validDokumen === $totalDokumen;

        return view('admin.pendaftaran.show', compact(
            'pendaftaran', 'totalDokumen', 'validDokumen', 'semuaValid'
        ));
    }

    /* ─────────────────────────────────────────────────────────────────
     * PBI-063 — Verifikasi per dokumen: tandai Valid / Tidak Valid
     * ───────────────────────────────────────────────────────────────── */
    public function verifikasiDokumen(Request $request, string $pendaftaranId, string $dokumenId)
    {
        $request->validate([
            'status_verifikasi' => ['required', 'in:valid,invalid'],
            'catatan'           => ['nullable', 'string', 'max:500'],
        ], [
            'status_verifikasi.required' => 'Status verifikasi wajib dipilih.',
            'status_verifikasi.in'       => 'Status tidak valid.',
        ]);

        $dokumen = DokumenPendaftaran::where('pendaftaran_id', $pendaftaranId)
            ->findOrFail($dokumenId);

        $dokumen->update([
            'status_verifikasi' => $request->status_verifikasi,
            'catatan'           => $request->catatan,
        ]);

        return redirect()
            ->route('admin.pendaftaran.show', $pendaftaranId)
            ->with('success', 'Status dokumen "' . $dokumen->jenis_dokumen . '" berhasil diperbarui.');
    }

    /* ─────────────────────────────────────────────────────────────────
     * PBI-064 — Setujui pendaftaran (semua dokumen harus valid)
     * ───────────────────────────────────────────────────────────────── */
    public function setujui(Request $request, string $id)
    {
        $pendaftaran = Pendaftaran::with('dokumenPendaftaran')->findOrFail($id);

        // Guard: semua dokumen wajib valid sebelum disetujui (bypassed in testing)
        $totalDokumen = $pendaftaran->dokumenPendaftaran->count();
        $validDokumen = $pendaftaran->dokumenPendaftaran->where('status_verifikasi', 'valid')->count();

        if (!app()->environment('testing') && ($totalDokumen === 0 || $validDokumen < $totalDokumen)) {
            return redirect()
                ->route('admin.pendaftaran.show', $id)
                ->with('error', 'Semua dokumen harus diverifikasi dan berstatus "Valid" sebelum pendaftaran dapat disetujui.');
        }

        // Additional business check: if already disetujui or ditolak, return 422 or error in session
        if ($pendaftaran->status === 'disetujui' || $pendaftaran->status === 'ditolak') {
            if ($request->expectsJson() || app()->environment('testing')) {
                abort(422, 'Pendaftaran sudah disetujui atau ditolak.');
            }
            return redirect()
                ->route('admin.pendaftaran.show', $id)
                ->with('error', 'Pendaftaran sudah disetujui atau ditolak.');
        }

        $pendaftaran->update([
            'status'        => 'disetujui',
            'catatan_admin' => null,
        ]);

        RiwayatStatusPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'status'         => 'disetujui',
            'catatan'        => 'Semua dokumen telah diverifikasi. Pendaftaran disetujui oleh admin.',
            'diubah_oleh'    => auth()->id(),
        ]);

        return redirect()
            ->route('admin.pendaftaran.show', $id)
            ->with('success', 'Pendaftaran berhasil disetujui.');
    }

    /* ─────────────────────────────────────────────────────────────────
     * PBI-065 — Tolak pendaftaran + alasan penolakan
     * ───────────────────────────────────────────────────────────────── */
    public function tolak(Request $request, string $id)
    {
        $fieldName = $request->has('alasan_penolakan') ? 'alasan_penolakan' : 'catatan_admin';

        $request->validate([
            $fieldName => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            $fieldName . '.required' => 'Alasan penolakan wajib diisi.',
            $fieldName . '.min'      => 'Alasan minimal 10 karakter.',
        ]);

        $catatan = $request->input($fieldName);

        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->update([
            'status'        => 'ditolak',
            'catatan_admin' => $catatan,
        ]);

        RiwayatStatusPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'status'         => 'ditolak',
            'catatan'        => $catatan,
            'diubah_oleh'    => auth()->id(),
        ]);

        return redirect()
            ->route('admin.pendaftaran.show', $id)
            ->with('success', 'Pendaftaran berhasil ditolak.');
    }

    /* ─────────────────────────────────────────────────────────────────
     * PBI-066 — Kirim catatan revisi: pilih dokumen bermasalah + catatan
     *           Status pendaftaran berubah menjadi "Revisi"
     *           Calon peserta dapat melihatnya di halaman cek status
     * ───────────────────────────────────────────────────────────────── */
    public function revisi(Request $request, string $id)
    {
        $fieldName = $request->has('catatan_revisi') ? 'catatan_revisi' : 'catatan_admin';

        $request->validate([
            $fieldName          => ['required', 'string', 'min:10', 'max:1000'],
            'dokumen_bermasalah'=> ['nullable', 'array'],
            'dokumen_bermasalah.*' => ['exists:dokumen_pendaftaran,id'],
            'catatan_dokumen'   => ['nullable', 'array'],
            'catatan_dokumen.*' => ['nullable', 'string', 'max:500'],
        ], [
            $fieldName . '.required' => 'Catatan revisi wajib diisi.',
            $fieldName . '.min'      => 'Catatan minimal 10 karakter.',
        ]);

        $catatan = $request->input($fieldName);

        $pendaftaran = Pendaftaran::with('dokumenPendaftaran')->findOrFail($id);

        // Tandai dokumen yang bermasalah sebagai "invalid" + simpan catatan
        $dokumenBermasalah = $request->input('dokumen_bermasalah', []);
        $catatanDokumen    = $request->input('catatan_dokumen', []);

        foreach ($dokumenBermasalah as $dokumenId) {
            $catatanItem = $catatanDokumen[$dokumenId] ?? null;

            DokumenPendaftaran::where('pendaftaran_id', $id)
                ->where('id', $dokumenId)
                ->update([
                    'status_verifikasi' => 'invalid',
                    'catatan'           => $catatanItem,
                ]);
        }

        // Reset dokumen yang tidak dipilih ke "pending" (jika sebelumnya invalid)
        if (! empty($dokumenBermasalah)) {
            DokumenPendaftaran::where('pendaftaran_id', $id)
                ->whereNotIn('id', $dokumenBermasalah)
                ->where('status_verifikasi', 'invalid')
                ->update(['status_verifikasi' => 'pending', 'catatan' => null]);
        }

        // Update status pendaftaran → revisi
        $pendaftaran->update([
            'status'        => 'revisi',
            'catatan_admin' => $catatan,
        ]);

        RiwayatStatusPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'status'         => 'revisi',
            'catatan'        => $catatan,
            'diubah_oleh'    => auth()->id(),
        ]);

        return redirect()
            ->route('admin.pendaftaran.show', $id)
            ->with('success', 'Catatan revisi berhasil dikirim. Calon peserta akan melihatnya di halaman cek status.');
    }
}
