<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ItemKitRobotik;
use App\Models\User;

class MaintenanceAset extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'maintenance_aset';
    protected $fillable = ['id','item_kit_id','dilaporkan_oleh','ditangani_oleh','tanggal_lapor','jenis_pemeliharaan','hasil_pemeriksaan','catatan'];

    public function itemKit(): BelongsTo
    {
        return $this->belongsTo(ItemKitRobotik::class, 'item_kit_id');
    }

    public function dilaporkanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh');
    }

    public function ditanganiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }
}
