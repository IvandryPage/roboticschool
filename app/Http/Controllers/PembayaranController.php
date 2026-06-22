<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;

class PembayaranController extends Controller
{
    public function index(Pendaftaran $pendaftaran)
    {
        return view('pembayaran.index', compact('pendaftaran'));
    }

    public function store(Pendaftaran $pendaftaran)
{
    return redirect()->route('pendaftaran.success');
}
}