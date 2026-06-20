<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;

    // Wajib ditambahkan karena nama tabel Anda di migration tidak pakai 's'
    protected $table = 'instruktur'; 

    protected $guarded = [];
}