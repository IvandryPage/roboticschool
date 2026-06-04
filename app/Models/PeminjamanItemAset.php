<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ItemKitRobotik;
use App\Models\User;

class PeminjamanItemAset extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'peminjaman_item_aset';
    protected $fillable = ['id','item_kit_id','peminjam_id','tanggal_pinjam','tanggal_kembali','status','catatan'];

    public function itemKit(): BelongsTo
    {
        return $this->belongsTo(ItemKitRobotik::class, 'item_kit_id');
    }

    public function peminjam(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }
}
