<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        if ($user && $user->role && $user->role->nama_role === 'Admin Akademik') {
            return redirect('/admin/aset');
        }

        return redirect('/dashboard');
    }
}
