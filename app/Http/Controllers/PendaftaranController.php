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
                'no_hp' => 'required|string|max:20',

                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|string',
                'domisili' => 'required|string|max:255',
                'alamat' => 'required|string',

                'pendidikan' => 'required|string',
                'institusi' => 'nullable|string|max:255',

                'motivasi' => 'nullable|string',
                'format_kelas' => 'required|string',

                'program_id' => 'required|exists:program_kursus,id',
            ]);

            $calonPeserta = CalonPeserta::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'],
                'asal_sekolah_atau_instansi' => $validated['institusi'] ?? '-',
                'jenjang_pendidikan' => $validated['pendidikan'],
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

    // STEP 3 - SIMPAN DOKUMEN
    public function storeDokumen(Request $request, Pendaftaran $pendaftaran)
{
    $request->validate([
        'dokumen_identitas' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        'pas_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'dokumen_pendukung' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    // Identitas
    $pathIdentitas = $request->file('dokumen_identitas')
        ->store('dokumen-pendaftaran', 'public');

    DokumenPendaftaran::create([
        'pendaftaran_id' => $pendaftaran->id,
        'jenis_dokumen' => 'Identitas',
        'nama_file' => $request->file('dokumen_identitas')->getClientOriginalName(),
        'file_path' => $pathIdentitas,
        'status_verifikasi' => 'Menunggu',
        'uploaded_at' => now(),
    ]);

    // Pas Foto
    $pathPasFoto = $request->file('pas_foto')
        ->store('dokumen-pendaftaran', 'public');

    DokumenPendaftaran::create([
        'pendaftaran_id' => $pendaftaran->id,
        'jenis_dokumen' => 'Pas Foto',
        'nama_file' => $request->file('pas_foto')->getClientOriginalName(),
        'file_path' => $pathPasFoto,
        'status_verifikasi' => 'Menunggu',
        'uploaded_at' => now(),
    ]);

    // Dokumen Pendukung (Opsional)
    if ($request->hasFile('dokumen_pendukung')) {

        $pathPendukung = $request->file('dokumen_pendukung')
            ->store('dokumen-pendaftaran', 'public');

        DokumenPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_dokumen' => 'Dokumen Pendukung',
            'nama_file' => $request->file('dokumen_pendukung')->getClientOriginalName(),
            'file_path' => $pathPendukung,
            'status_verifikasi' => 'Menunggu',
            'uploaded_at' => now(),
        ]);
    }

    return redirect()
        ->route('pembayaran.index', $pendaftaran->id);
}

  // STEP 4 - HALAMAN SELESAI
public function selesai(Pendaftaran $pendaftaran)
{
    return view(
        'pendaftaran.selesai',
        compact('pendaftaran')
    );
}

// STEP 5 - SELESAI (lama)
public function success()
{
    return view('pendaftaran.sukses');
}

// STEP 6 - CEK STATUS

public function cekStatus()
{
    return view('pendaftaran.status');
}


// STEP 7 - CARI STATUS
public function cariStatus(Request $request)
{

    $request->validate([
        'keyword'=>'required'
    ]);


    $pendaftaran = Pendaftaran::with([
        'calonPeserta',
        'program',
        'riwayatStatus'
    ])
    ->where('no_referensi',$request->keyword)
    ->orWhereHas('calonPeserta', function($q) use ($request){

        $q->where('email',$request->keyword);

    })
    ->latest()
    ->first();

    if(!$pendaftaran){
    return back()->with('error','Data pendaftaran tidak ditemukan');
}


    return view('pendaftaran.status', compact('pendaftaran'));
}

public function revisi(Pendaftaran $pendaftaran)
{
    return view('pendaftaran.revisi', compact('pendaftaran'));
}

// STEP 8 - REVISI DOKUMEN
public function storeRevisi(
    Request $request,
    Pendaftaran $pendaftaran
)
{
    $request->validate([
        'dokumen' => 'required|file|mimes:pdf,jpg,jpeg,png'
    ]);

    // simpan file revisi
    $path = $request
        ->file('dokumen')
        ->store('revisi-dokumen', 'public');

    // ubah status pendaftaran
    $pendaftaran->update([
        'status' => 'Verifikasi'
    ]);

    return redirect()
        ->route('pendaftaran.status')
        ->with(
            'success',
            'Dokumen revisi berhasil dikirim'
        );
}

}


