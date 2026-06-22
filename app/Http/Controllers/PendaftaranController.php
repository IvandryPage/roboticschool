<?php

namespace App\Http\Controllers;

use App\Models\CalonPeserta;
use App\Models\Pendaftaran;
use App\Models\ProgramKursus;
use App\Models\DokumenPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    public function create()
    {
        $programs = ProgramKursus::where('status_tampil', true)->get();

        return view('pendaftaran.form', compact('programs'));
    }

    // STEP 1 - DATA DIRI
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email',
            'no_hp' => 'required|regex:/^(08|\+628)[0-9]{8,13}$/',
            'asal_sekolah_atau_instansi' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|max:255',
            'program_id' => 'required|exists:program_kursus,id',
        ]);

        $calonPeserta = CalonPeserta::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'asal_sekolah_atau_instansi' => $validated['asal_sekolah_atau_instansi'],
            'jenjang_pendidikan' => $validated['jenjang_pendidikan'],
        ]);

        $noReferensi = 'REG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        $pendaftaran = Pendaftaran::create([
            'calon_peserta_id' => $calonPeserta->id,
            'program_id' => $validated['program_id'],
            'no_referensi' => $noReferensi,
            'tanggal_daftar' => now(),
            'status' => 'Menunggu',
        ]);

        return redirect()->route('pendaftaran.dokumen', $pendaftaran->id);
    }

    // STEP 2 - FORM DOKUMEN
    public function dokumen(Pendaftaran $pendaftaran)
    {
        return view('pendaftaran.dokumen', compact('pendaftaran'));
    }

    // STEP 2 - SIMPAN DOKUMEN
    public function storeDokumen(Request $request, Pendaftaran $pendaftaran)
    {
        $validated = $request->validate([
            'dokumen_identitas' =>
                'required|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'pas_foto' =>
                'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pathIdentitas = $request
            ->file('dokumen_identitas')
            ->store('dokumen-pendaftaran', 'public');

        $pathPasFoto = $request
            ->file('pas_foto')
            ->store('dokumen-pendaftaran', 'public');

        DokumenPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_dokumen' => 'Identitas',
            'nama_file' => $request->file('dokumen_identitas')->getClientOriginalName(),
            'file_path' => $pathIdentitas,
            'status_verifikasi' => 'Menunggu',
            'uploaded_at' => now(),
        ]);

        DokumenPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_dokumen' => 'Pas Foto',
            'nama_file' => $request->file('pas_foto')->getClientOriginalName(),
            'file_path' => $pathPasFoto,
            'status_verifikasi' => 'Menunggu',
            'uploaded_at' => now(),
        ]);

        // sementara ke halaman sukses dulu
        return redirect()
            ->route('pendaftaran.success')
            ->with('no_referensi', $pendaftaran->no_referensi);
    }

    // STEP 4 - SELESAI
    public function success()
    {
        return view('pendaftaran.sukses');
    }
}