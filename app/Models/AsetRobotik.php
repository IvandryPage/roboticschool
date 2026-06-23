<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PeminjamanItemAset;

class AsetRobotik extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'aset_robotik';

    protected $fillable = ['id', 'kode_aset', 'nama_kit', 'deskripsi', 'kategori', 'stok_minimal'];

    public function getAvailableStockAttribute(): int
    {
        return $this->itemKits()->where('status_kondisi', 'Bagus')->get()->filter(function ($item) {
            return !PeminjamanItemAset::where('item_kit_id', $item->id)->where('status', 'Dipinjam')->exists();
        })->count();
    }
}
