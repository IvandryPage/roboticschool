<?php
#PB01
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->get();

        return view('pages.admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('nama_role')->get();

        return view('pages.admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'status_aktif' => 'nullable|boolean',
        ]);

        User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'name' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'password' => $validated['password'],
            'role_id' => $validated['role_id'],
            'status_aktif' => $request->boolean('status_aktif'),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('nama_role')->get();

        return view('pages.admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'status_aktif' => 'nullable|boolean',
        ]);

        $data = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'name' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'role_id' => $validated['role_id'],
            'status_aktif' => $request->boolean('status_aktif'),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->update([
            'status_aktif' => false,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dinonaktifkan.');
    }
}