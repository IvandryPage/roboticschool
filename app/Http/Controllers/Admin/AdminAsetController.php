<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsetRobotik;
use App\Models\ItemKitRobotik;
use App\Models\PeminjamanItemAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminAsetController extends Controller
{
    public function index()
    {
        $assets = AsetRobotik::latest()->get()->map(function ($asset) {
            $items = ItemKitRobotik::where('aset_id', $asset->id)->get();
            $asset->total_stock = $items->count();
            $asset->available_stock = $items->where('status_kondisi', 'Bagus')->filter(function ($item) {
                return !PeminjamanItemAset::where('item_kit_id', $item->id)->where('status', 'Dipinjam')->exists();
            })->count();
            return $asset;
        });
        return view('admin.aset.index', compact('assets'));
    }

    public function create()
    {
        return view('admin.aset.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kit' => 'required|string|max:255',
            'jumlah_stok' => 'required|integer|min:0|max:100',
            'kondisi' => 'required|string|in:Bagus,Rusak,Perbaikan',
            'deskripsi' => 'nullable|string',
        ]);

        $slug = strtoupper(Str::slug($request->nama_kit));
        $slug = preg_replace('/[^A-Z0-9\-]/', '', $slug);
        $base_kode = 'KIT-' . ($slug ?: 'ROBOT');
        $kode_aset = $base_kode;
        $counter = 1;
        while (AsetRobotik::where('kode_aset', $kode_aset)->exists()) {
            $kode_aset = $base_kode . '-' . $counter;
            $counter++;
        }

        $aset = AsetRobotik::create([
            'id' => (string) Str::uuid(),
            'kode_aset' => $kode_aset,
            'nama_kit' => $request->nama_kit,
            'kategori' => 'Lainnya',
            'stok_minimal' => 1,
            'deskripsi' => $request->deskripsi,
        ]);

        $cleanName = preg_replace('/[^A-Za-z0-9]/', '', Str::slug($request->nama_kit));
        $prefix = strtoupper(substr($cleanName, 0, 3)) ?: 'KIT';

        for ($i = 1; $i <= $request->jumlah_stok; $i++) {
            $sn_counter = 1;
            do {
                $serial_number = 'SN-' . $prefix . '-' . str_pad($sn_counter, 3, '0', STR_PAD_LEFT);
                $sn_counter++;
            } while (ItemKitRobotik::where('serial_number', $serial_number)->exists());

            ItemKitRobotik::create([
                'id' => (string) Str::uuid(),
                'aset_id' => $aset->id,
                'serial_number' => $serial_number,
                'status_kondisi' => $request->kondisi,
                'lokasi_rak' => 'RAK-' . $prefix . '1',
            ]);
        }

        return redirect()->route('admin.aset.index')->with('success', 'Aset robotik beserta item kit berhasil ditambahkan.');
    }

    public function edit(AsetRobotik $aset)
    {
        $itemKits = ItemKitRobotik::where('aset_id', $aset->id)->get()->map(function ($item) {
            $item->is_available = ($item->status_kondisi === 'Bagus') && !PeminjamanItemAset::where('item_kit_id', $item->id)->where('status', 'Dipinjam')->exists();
            return $item;
        });
        $aset->setRelation('itemKits', $itemKits);
        return view('admin.aset.edit', compact('aset'));
    }

    public function update(Request $request, AsetRobotik $aset)
    {
        $request->validate([
            'nama_kit' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $aset->update([
            'nama_kit' => $request->nama_kit,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.aset.index')->with('success', 'Aset robotik berhasil diperbarui.');
    }

    public function destroy(AsetRobotik $aset)
    {
        ItemKitRobotik::where('aset_id', $aset->id)->delete();
        $aset->delete();

        return redirect()->route('admin.aset.index')->with('success', 'Aset robotik beserta item kit di dalamnya berhasil dihapus.');
    }

    public function storeItemKit(Request $request, AsetRobotik $aset)
    {
        $request->validate([
            'jumlah_stok' => 'required|integer|min:1|max:50',
            'status_kondisi' => 'required|string|in:Bagus,Rusak,Perbaikan',
            'lokasi_rak' => 'nullable|string|max:255',
        ]);

        $cleanName = preg_replace('/[^A-Za-z0-9]/', '', Str::slug($aset->nama_kit));
        $prefix = strtoupper(substr($cleanName, 0, 3)) ?: 'KIT';

        for ($i = 1; $i <= $request->jumlah_stok; $i++) {
            $sn_counter = 1;
            do {
                $serial_number = 'SN-' . $prefix . '-' . str_pad($sn_counter, 3, '0', STR_PAD_LEFT);
                $sn_counter++;
            } while (ItemKitRobotik::where('serial_number', $serial_number)->exists());

            ItemKitRobotik::create([
                'id' => (string) Str::uuid(),
                'aset_id' => $aset->id,
                'serial_number' => $serial_number,
                'status_kondisi' => $request->status_kondisi,
                'lokasi_rak' => $request->lokasi_rak ?: ('RAK-' . $prefix . '1'),
            ]);
        }

        return back()->with('success', $request->jumlah_stok . ' item kit baru (stok) berhasil ditambahkan.');
    }

    public function updateItemKitCondition(Request $request, ItemKitRobotik $itemKit)
    {
        $request->validate([
            'status_kondisi' => 'required|string|in:Bagus,Rusak,Perbaikan',
        ]);

        $itemKit->update([
            'status_kondisi' => $request->status_kondisi,
        ]);

        return back()->with('success', 'Kondisi item kit ' . $itemKit->serial_number . ' berhasil diperbarui menjadi ' . $request->status_kondisi . '.');
    }

    public function destroyItemKit(ItemKitRobotik $itemKit)
    {
        $hasActiveLoan = PeminjamanItemAset::where('item_kit_id', $itemKit->id)->whereIn('status', ['Diajukan', 'Dipinjam'])->exists();
        if ($hasActiveLoan) {
            return back()->withErrors(['error' => 'Item kit tidak dapat dihapus karena sedang dalam proses peminjaman.']);
        }

        $itemKit->delete();
        return back()->with('success', 'Item kit berhasil dihapus.');
    }
}
