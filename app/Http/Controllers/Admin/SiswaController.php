<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\ProgramKursus;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * PBI-067 : Buat akun siswa oleh admin setelah pendaftaran disetujui
 * PBI-068 : Halaman daftar siswa aktif
 * PBI-069 : Filter & pencarian siswa aktif
 * PBI-070 : Edit data profil siswa oleh admin
 * PBI-071 : Admin nonaktifkan / aktifkan kembali akun siswa
 */
class SiswaController extends Controller
{
    /**
     * PBI-068 & PBI-069 — Daftar siswa + filter + search
     * PBI-071 — Tambahan filter status_akun
     */
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $program = $request->input('program');
        $status  = $request->input('status'); // PBI-071

        $siswaList = Siswa::with(['user', 'pendaftaran.program'])
            ->when($search, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('nama_lengkap', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                       ->orWhereHas('user', fn($q3) =>
                           $q3->where('name', 'like', "%{$search}%")
                       )
                )
            )
            ->when($program, fn($q) =>
                $q->whereHas('pendaftaran', fn($q2) =>
                    $q2->where('program_id', $program)
                )
            )
            ->when($status, fn($q) =>
                $q->where('status_akun', $status)
            )
            ->orderByDesc('tanggal_bergabung')
            ->paginate(10)
            ->withQueryString();

        $programList = ProgramKursus::orderBy('nama_program')->get();

        $stats = [
            'total'    => Siswa::count(),
            'aktif'    => Siswa::where('status_akun', 'aktif')->count(),
            'nonaktif' => Siswa::where('status_akun', 'nonaktif')->count(),
        ];

        return view('admin.siswa.index', compact(
            'siswaList', 'programList', 'stats',
            'search', 'program', 'status'
        ));
    }

    /**
     * PBI-067 — Form buat akun siswa
     */
    public function createAkun(string $pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with(['calonPeserta', 'program', 'siswa'])
            ->findOrFail($pendaftaranId);

        abort_if($pendaftaran->status !== 'disetujui', 403, 'Pendaftaran belum disetujui.');

        if ($pendaftaran->siswa) {
            return redirect()
                ->route('admin.siswa.show', $pendaftaran->siswa->id)
                ->with('info', 'Akun siswa sudah pernah dibuat.');
        }

        return view('admin.siswa.create-akun', compact('pendaftaran'));
    }

    /**
     * PBI-067 — Simpan akun siswa baru
     */
    public function storeAkun(Request $request, string $pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with(['calonPeserta', 'siswa'])
            ->findOrFail($pendaftaranId);

        abort_if($pendaftaran->status !== 'disetujui', 403, 'Pendaftaran belum disetujui.');

        if ($pendaftaran->siswa) {
            return redirect()
                ->route('admin.siswa.show', $pendaftaran->siswa->id)
                ->with('info', 'Akun siswa sudah pernah dibuat.');
        }

        $request->validate([
            'username' => ['required', 'string', 'min:4', 'max:50', 'unique:users,name'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'username.required'  => 'Username wajib diisi.',
            'username.min'       => 'Username minimal 4 karakter.',
            'username.unique'    => 'Username sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        DB::transaction(function () use ($request, $pendaftaran) {
            $cp = $pendaftaran->calonPeserta;

            $user = User::create([
                'name'     => $request->username,
                'email'    => $cp->email,
                'password' => Hash::make($request->password),
            ]);

            Siswa::create([
                'user_id'           => $user->id,
                'pendaftaran_id'    => $pendaftaran->id,
                'nama_lengkap'      => $cp->nama_lengkap,
                'email'             => $cp->email,
                'no_hp'             => $cp->no_hp,
                'asal_sekolah'      => $cp->asal_sekolah_atau_instansi,
                'tanggal_bergabung' => now(),
                'status_akun'       => 'aktif',
            ]);
        });

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Akun siswa berhasil dibuat.');
    }

    /**
     * PBI-070 — Detail siswa
     */
    public function show(string $id)
    {
        $siswa = Siswa::with(['user', 'pendaftaran.program'])->findOrFail($id);

        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * PBI-070 — Form edit profil siswa
     */
    public function edit(string $id)
    {
        $siswa = Siswa::with(['user', 'pendaftaran.program'])->findOrFail($id);

        return view('admin.siswa.edit', compact('siswa'));
    }

    /**
     * PBI-070 — Simpan perubahan profil siswa
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email,' . $siswa->user_id],
            'no_hp'        => ['nullable', 'string', 'max:20'],
            'asal_sekolah' => ['nullable', 'string', 'max:255'],
            'status_akun'  => ['required', 'in:aktif,nonaktif'],
            'password'     => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.unique'          => 'Email sudah digunakan akun lain.',
            'status_akun.required'  => 'Status akun wajib dipilih.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'password.min'          => 'Password minimal 8 karakter.',
        ]);

        DB::transaction(function () use ($request, $siswa) {
            $siswa->update([
                'nama_lengkap' => $request->nama_lengkap,
                'email'        => $request->email,
                'no_hp'        => $request->no_hp,
                'asal_sekolah' => $request->asal_sekolah,
                'status_akun'  => $request->status_akun,
            ]);

            $siswa->user->update(['email' => $request->email]);

            if ($request->filled('password')) {
                $siswa->user->update(['password' => Hash::make($request->password)]);
            }
        });

        return redirect()
            ->route('admin.siswa.show', $siswa->id)
            ->with('success', 'Profil siswa berhasil diperbarui.');
    }

    // =========================================================================
    // PBI-071 — Nonaktifkan / Aktifkan kembali akun siswa
    // =========================================================================

    /**
     * PBI-071 — Toggle status akun siswa (aktif ↔ nonaktif)
     * Route: POST /admin/siswa/{id}/toggle-status
     * Digunakan oleh tombol cepat di tabel daftar siswa
     */
    public function toggleStatus(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $statusBaru = $siswa->status_akun === 'aktif' ? 'nonaktif' : 'aktif';
        $siswa->update(['status_akun' => $statusBaru]);

        $pesan = $statusBaru === 'nonaktif'
            ? "Akun {$siswa->nama_lengkap} berhasil dinonaktifkan."
            : "Akun {$siswa->nama_lengkap} berhasil diaktifkan kembali.";

        return redirect()->back()->with('success', $pesan);
    }

    /**
     * PBI-071 — Nonaktifkan akun siswa (dengan konfirmasi di halaman detail)
     * Route: POST /admin/siswa/{id}/nonaktifkan
     */
    public function nonaktifkan(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        abort_if($siswa->status_akun === 'nonaktif', 422, 'Akun siswa sudah nonaktif.');

        $siswa->update(['status_akun' => 'nonaktif']);

        return redirect()
            ->route('admin.siswa.show', $siswa->id)
            ->with('success', "Akun {$siswa->nama_lengkap} berhasil dinonaktifkan.");
    }

    /**
     * PBI-071 — Aktifkan kembali akun siswa
     * Route: POST /admin/siswa/{id}/aktifkan
     */
    public function aktifkan(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        abort_if($siswa->status_akun === 'aktif', 422, 'Akun siswa sudah aktif.');

        $siswa->update(['status_akun' => 'aktif']);

        return redirect()
            ->route('admin.siswa.show', $siswa->id)
            ->with('success', "Akun {$siswa->nama_lengkap} berhasil diaktifkan kembali.");
    }
}
