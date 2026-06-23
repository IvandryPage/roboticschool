<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanItemAset;
use Illuminate\Http\Request;

class AdminPeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = PeminjamanItemAset::with(['borrower', 'itemKit.aset', 'verifikator'])
            ->latest()
            ->get();
            
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    public function approve(PeminjamanItemAset $peminjaman)
    {
        if ($peminjaman->status !== 'Diajukan') {
            return back()->withErrors(['error' => 'Permohonan ini tidak sedang dalam status diajukan.']);
        }

        $itemKit = $peminjaman->itemKit;

        if ($itemKit->status_kondisi !== 'Bagus') {
            return back()->withErrors(['error' => 'Item kit rusak atau sedang dalam perbaikan.']);
        }

        $isBorrowed = PeminjamanItemAset::where('item_kit_id', $itemKit->id)
            ->where('status', 'Dipinjam')
            ->exists();

        if ($isBorrowed) {
            return back()->withErrors(['error' => 'Item kit sedang dipinjam oleh user lain.']);
        }

        $peminjaman->status = 'Dipinjam';
        $peminjaman->tanggal_pinjam = now();
        $peminjaman->diverifikasi_oleh = auth()->id();
        $peminjaman->save();

        return back()->with('success', 'Permohonan peminjaman berhasil disetujui.');
    }

    public function reject(PeminjamanItemAset $peminjaman)
    {
        if ($peminjaman->status !== 'Diajukan') {
            return back()->withErrors(['error' => 'Permohonan ini tidak sedang dalam status diajukan.']);
        }

        $peminjaman->status = 'Ditolak';
        $peminjaman->diverifikasi_oleh = auth()->id();
        $peminjaman->save();

        return back()->with('success', 'Permohonan peminjaman berhasil ditolak.');
    }

    public function confirmReturn(Request $request, PeminjamanItemAset $peminjaman)
    {
        if ($peminjaman->status !== 'Dipinjam') {
            return back()->withErrors(['error' => 'Aset ini tidak sedang dipinjam.']);
        }

        $request->validate([
            'kondisi_akhir' => 'required|string|in:Baik,Rusak,Hilang',
        ]);

        $peminjaman->status = 'Dikembalikan';
        $peminjaman->tanggal_kembali = now();
        $peminjaman->kondisi_akhir = $request->kondisi_akhir;
        $peminjaman->save();

        // Update the ItemKit condition
        $statusKondisi = 'Bagus';
        if ($request->kondisi_akhir === 'Rusak' || $request->kondisi_akhir === 'Hilang') {
            $statusKondisi = 'Rusak';
        }
        
        $itemKit = $peminjaman->itemKit;
        $itemKit->status_kondisi = $statusKondisi;
        $itemKit->save();

        return back()->with('success', 'Konfirmasi pengembalian berhasil diproses dengan kondisi akhir: ' . $request->kondisi_akhir);
    }
}
