<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $role = Auth::user()?->role?->nama_role;

        return match ($role) {
            'Admin Akademik' => redirect('/admin'),
            'Instruktur'     => redirect('/admin'),   // Filament panel — bukan blade
            'Direktur'       => redirect('/admin'),
            'Tim Publikasi'  => redirect('/publikasi'),
            'Siswa'          => redirect()->route('siswa.dashboard'),
            default          => redirect('/dashboard'),
        };
    }
}
