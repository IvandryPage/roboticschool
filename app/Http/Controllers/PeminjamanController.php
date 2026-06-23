<?php

namespace App\Http\Controllers;

use App\Models\AsetRobotik;
use App\Models\ItemKitRobotik;
use App\Models\PeminjamanItemAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PeminjamanController extends Controller
{
    public function index()
    {
        if (auth()->user()->role && auth()->user()->role->nama_role === 'Admin Akademik') {
            return redirect('/admin/aset');
        }

        $userId = auth()->id();

        $activeBorrowings = PeminjamanItemAset::with(['itemKit.aset'])
            ->where('user_id', $userId)
            ->whereIn('status', ['Diajukan', 'Dipinjam'])
            ->latest()
            ->get();

        $historyBorrowings = PeminjamanItemAset::with(['itemKit.aset'])
            ->where('user_id', $userId)
            ->whereNotIn('status', ['Diajukan', 'Dipinjam'])
            ->latest()
            ->get();

        // Calculate available stock dynamically
        $assets = AsetRobotik::get()->map(function ($asset) {
            $items = ItemKitRobotik::where('aset_id', $asset->id)->get();
            $asset->available_stock = $items->where('status_kondisi', 'Bagus')->filter(function ($item) {
                return !PeminjamanItemAset::where('item_kit_id', $item->id)->where('status', 'Dipinjam')->exists();
            })->count();
            return $asset;
        });

        return view('peminjaman.index', compact('activeBorrowings', 'historyBorrowings', 'assets'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role && auth()->user()->role->nama_role === 'Admin Akademik') {
            return redirect('/admin/aset');
        }

        $request->validate([
            'aset_id' => 'required|exists:aset_robotik,id',
            'tanggal_jatuh_tempo' => 'required|date|after:today',
        ], [
            'aset_id.required' => 'Silakan pilih kit robotik yang ingin dipinjam.',
            'tanggal_jatuh_tempo.required' => 'Silakan tentukan batas tanggal pengembalian.',
            'tanggal_jatuh_tempo.after' => 'Tanggal jatuh tempo harus di masa depan.',
        ]);

        $availableItem = ItemKitRobotik::where('aset_id', $request->aset_id)
            ->where('status_kondisi', 'Bagus')
            ->whereDoesntHave('peminjamans', function ($query) {
                $query->where('status', 'Dipinjam');
            })
            ->first();

        if (! $availableItem) {
            return back()->withErrors(['aset_id' => 'Maaf, seluruh item kit robotik untuk kategori ini sedang tidak tersedia atau dalam kondisi rusak.']);
        }

        // Bypassing mass-assignment constraints
        $peminjaman = new PeminjamanItemAset();
        $peminjaman->id = (string) Str::uuid();
        $peminjaman->user_id = auth()->id();
        $peminjaman->item_kit_id = $availableItem->id;
        $peminjaman->tanggal_pinjam = null;
        $peminjaman->tanggal_jatuh_tempo = $request->tanggal_jatuh_tempo;
        $peminjaman->tanggal_kembali = null;
        $peminjaman->status = 'Diajukan';
        $peminjaman->kondisi_awal = 'Baik';
        $peminjaman->kondisi_akhir = null;
        $peminjaman->diverifikasi_oleh = null;
        $peminjaman->save();

        return back()->with('success', 'Permohonan peminjaman berhasil diajukan. Silakan tunggu verifikasi dari Admin.');
    }
}
