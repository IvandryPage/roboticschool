<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        $role = $user?->role?->nama_role;

        // Redirect pengguna berdasarkan peran mereka
        return match ($role) {
            'Admin' => redirect('/admin/dashboard'),
            'Instruktur' => redirect('/instruktur/dashboard'),
            'Siswa' => redirect('/siswa/dashboard'),
            'Tim Publikasi' => redirect('/publikasi/dashboard'),
            'Direktur' => redirect('/direktur/dashboard'),
            default => redirect('/dashboard'),
        };
    }
}