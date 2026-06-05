<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemKitRobotik extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'item_kit_robotik';

    protected $fillable = ['id', 'aset_id', 'serial_number', 'status_kondisi', 'lokasi_rak'];
}
