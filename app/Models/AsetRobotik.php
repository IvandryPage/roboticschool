<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetRobotik extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'aset_robotik';

    protected $fillable = ['id','kode_aset','nama_kit','deskripsi','kategori','stok_minimal'];
}
