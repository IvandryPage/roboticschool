<?php

namespace App\Filament\Admin\Resources\RekapKehadirans\Pages;

use App\Filament\Admin\Resources\RekapKehadiranResource;
use Filament\Resources\Pages\ManageRecords;

class ManageRekapKehadirans extends ManageRecords
{
    protected static string $resource = RekapKehadiranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Sengaja dikosongkan agar tidak ada tombol tambah data di halaman rekap
        ];
    }
}