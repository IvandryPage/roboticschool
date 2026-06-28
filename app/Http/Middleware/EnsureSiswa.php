<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role?->nama_role !== 'Siswa') {
            abort(403, 'Halaman ini hanya dapat diakses oleh Siswa.');
        }

        return $next($request);
    }
}
