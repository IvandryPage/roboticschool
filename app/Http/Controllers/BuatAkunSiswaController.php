<?php

namespace App\Http\Controllers;

use App\Models\CalonPeserta;
use App\Models\Pendaftaran;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class BuatAkunSiswaController extends Controller
{
    /**
     * Tampilkan form buat akun — email + nama pre-filled dari data pendaftaran.
     */
    public function show(Pendaftaran $pendaftaran): View
    {
        // Guard: kalau sudah punya akun, redirect ke dashboard
        if ($pendaftaran->user_id && Auth::check()) {
            return redirect()->route('siswa.dashboard');
        }

        $calonPeserta = $pendaftaran->calonPeserta;

        return view('pendaftaran.buat-akun', compact('pendaftaran', 'calonPeserta'));
    }

    /**
     * Proses buat akun — create User + Siswa, auto-login, redirect dashboard.
     */
    public function store(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        // Guard: akun sudah dibuat sebelumnya
        if ($pendaftaran->user_id) {
            Auth::loginUsingId($pendaftaran->user_id);
            return redirect()->route('siswa.dashboard');
        }

        $calonPeserta = $pendaftaran->calonPeserta;

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'password'     => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $roleSiswa = Role::where('nama_role', 'Siswa')->firstOrFail();

        DB::transaction(function () use ($request, $pendaftaran, $calonPeserta, $roleSiswa) {

            $user = User::create([
                'id'           => (string) Str::uuid(),
                'name'         => $request->nama_lengkap,
                'nama_lengkap' => $request->nama_lengkap,
                'email'        => $request->email,
                'no_hp'        => $calonPeserta?->no_hp,
                'password'     => Hash::make($request->password),
                'role_id'      => $roleSiswa->id,
                'status_aktif' => true,
            ]);

            Siswa::create([
                'id'             => (string) Str::uuid(),
                'user_id'        => $user->id,
                'pendaftaran_id' => $pendaftaran->id,
            ]);

            // Simpan relasi pendaftaran → user agar tidak bisa dibuat duplikat
            $pendaftaran->update(['user_id' => $user->id]);

            // Auto-login langsung
            Auth::login($user);
        });

        return redirect()
            ->route('siswa.dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di RoboNesia Academy.');
    }
}
