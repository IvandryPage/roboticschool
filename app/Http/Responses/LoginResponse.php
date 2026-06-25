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
            // Filament panel
            'Admin Akademik' => redirect('/admin'),
            'Direktur'       => redirect('/admin'),

            // Generic blade dashboard (returns view('dashboard'))
            'Instruktur'     => redirect('/dashboard'),

            // Tim Publikasi: panel Filament terpisah di /publikasi
            'Tim Publikasi'  => redirect('/publikasi'),

            // Siswa: custom blade dashboard
            'Siswa'          => redirect()->route('siswa.dashboard'),

            default          => redirect('/dashboard'),
        };
    }
}