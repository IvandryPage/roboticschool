<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;

class Invoice extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'invoice';

    protected $fillable = ['id','pendaftaran_id','no_invoice','total_tagihan','tanggal_terbit','tanggal_jatuh_tempo','status_pembayaran','payment_gateway','payment_reference','gateway_payload'];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'invoice_id');
    }
}
