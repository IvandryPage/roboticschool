<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTimPublikasi
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role?->nama_role !== 'Tim Publikasi') {
            abort(403, 'Halaman ini hanya dapat diakses oleh Tim Publikasi.');
        }

        return $next($request);
    }
}
