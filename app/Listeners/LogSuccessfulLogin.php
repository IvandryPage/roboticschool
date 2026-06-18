<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class LogSuccessfulLogin
{
    // Fungsi ini otomatis terpicu begitu ada user yang berhasil menekan tombol "Masuk"
    public function handle(Login $event)
    {
        AuditLog::create([
            'user_id'      => $event->user->id,
            'aksi'         => 'Login',
            'entity_type'  => get_class($event->user),
            'entity_id'    => $event->user->id,
            'data_sebelum' => json_encode([]),
            'data_sesudah' => json_encode([]),
            'ip_address'   => Request::ip()
        ]);
    }
}