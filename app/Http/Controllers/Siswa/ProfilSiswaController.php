<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * PBI-072 — Halaman profil siswa setelah login
 *
 * Siswa dapat:
 * - Melihat data profil dirinya sendiri
 * - Mengedit nama, no_hp, asal_sekolah
 * - Mengganti password
 *
 * Catatan: email & username hanya bisa diubah oleh admin (PBI-070)
 */
class ProfilSiswaController extends Controller
{
    /**
     * PBI-072 — Tampilkan halaman profil siswa yang sedang login
     * Route: GET /siswa/profil
     */
    public function show()
    {
        $user  = Auth::user();
        $siswa = Siswa::with('pendaftaran.program')
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('siswa.profil.show', compact('siswa', 'user'));
    }

    /**
     * PBI-072 — Form edit profil siswa (self-edit)
     * Route: GET /siswa/profil/edit
     */
    public function edit()
    {
        $user  = Auth::user();
        $siswa = Siswa::with('pendaftaran.program')
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('siswa.profil.edit', compact('siswa', 'user'));
    }

    /**
     * PBI-072 — Simpan perubahan profil oleh siswa sendiri
     * Route: PUT /siswa/profil
     *
     * Siswa hanya boleh mengubah: nama_lengkap, no_hp, asal_sekolah
     * Email & username dikunci (hanya admin)
     */
    public function update(Request $request)
    {
        $user  = Auth::user();
        $siswa = Siswa::with(['user', 'pendaftaran.calonPeserta'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'no_hp'        => ['nullable', 'string', 'max:20'],
            'asal_sekolah' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        ]);

        $siswa->user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp'        => $request->no_hp,
        ]);

        if ($siswa->pendaftaran && $siswa->pendaftaran->calonPeserta) {
            $siswa->pendaftaran->calonPeserta->update([
                'asal_sekolah_atau_instansi' => $request->asal_sekolah,
            ]);
        }

        return redirect()
            ->route('siswa.profil.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * PBI-072 — Halaman ganti password oleh siswa sendiri
     * Route: GET /siswa/profil/ganti-password
     */
    public function editPassword()
    {
        return view('siswa.profil.ganti-password');
    }

    /**
     * PBI-072 — Simpan password baru
     * Route: PUT /siswa/profil/ganti-password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password_lama'   => ['required', 'string'],
            'password'        => ['required', 'confirmed', Password::min(8)],
        ], [
            'password_lama.required'  => 'Password lama wajib diisi.',
            'password.required'       => 'Password baru wajib diisi.',
            'password.confirmed'      => 'Konfirmasi password tidak cocok.',
            'password.min'            => 'Password minimal 8 karakter.',
        ]);

        // Verifikasi password lama
        if (! Hash::check($request->password_lama, $user->password)) {
            return back()
                ->withErrors(['password_lama' => 'Password lama tidak sesuai.'])
                ->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()
            ->route('siswa.profil.show')
            ->with('success', 'Password berhasil diperbarui.');
    }
}
