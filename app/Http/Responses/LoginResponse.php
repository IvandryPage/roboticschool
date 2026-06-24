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

        return match ($role) {
            'Admin Akademik' => redirect('/admin'),
            'Instruktur'     => redirect('/dashboard'),
            'Siswa'          => redirect('/siswa/dashboard'),
            'Tim Publikasi'  => redirect('/publikasi'),
            'Direktur'       => redirect('/admin'),
            default          => redirect('/dashboard'),
        };
    }
}
