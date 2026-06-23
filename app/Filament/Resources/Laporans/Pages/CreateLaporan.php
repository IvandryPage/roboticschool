<?php

namespace App\Filament\Resources\Laporans\Pages;

use App\Filament\Resources\Laporans\LaporanResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLaporan extends CreateRecord
{
    protected static string $resource = LaporanResource::class;

    /**
     * PBI-139: Auto-fill 'dibuat_oleh' dari user yang sedang login.
     * Tanpa ini, kolom NOT NULL 'dibuat_oleh' akan error saat create laporan.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['dibuat_oleh'] = Auth::id();
        return $data;
    }
}
