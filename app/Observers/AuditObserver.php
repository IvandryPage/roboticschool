<?php

namespace App\Observers;

use App\Models\AuditLog; // Memanggil model buatan tim database
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditObserver
{
    // Fungsi ini akan otomatis mencatat kalau ada data yang diverifikasi/diupdate
    public function updated($model)
    {
        if (! Auth::check()) return;

        AuditLog::create([
            'user_id'      => Auth::id(),
            'aksi'         => 'Update / Verifikasi',
            'entity_type'  => get_class($model),
            'entity_id'    => $model->id,
            'data_sebelum' => json_encode($model->getOriginal()), // Ubah ke format JSON
            'data_sesudah' => json_encode($model->getChanges()),  // Ubah ke format JSON
            'ip_address'   => Request::ip()
        ]);
    }

    // Fungsi ini akan otomatis mencatat kalau ada data yang dihapus
    public function deleted($model)
    {
        if (! Auth::check()) return;

        AuditLog::create([
            'user_id'      => Auth::id(),
            'aksi'         => 'Delete Data',
            'entity_type'  => get_class($model),
            'entity_id'    => $model->id,
            'data_sebelum' => json_encode($model->getOriginal()),
            'data_sesudah' => json_encode([]), // Kosong karena datanya dihapus
            'ip_address'   => Request::ip()
        ]);
    }
}