<?php

namespace App\Filament\Resources\SiswaLayakSertifikat\Pages;

use App\Filament\Resources\SiswaLayakSertifikat\SiswaLayakSertifikatResource;
use Filament\Resources\Pages\ListRecords;

/**
 * PBI-124: Halaman list siswa layak sertifikat
 */
class ListSiswaLayakSertifikat extends ListRecords
{
    protected static string $resource = SiswaLayakSertifikatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
