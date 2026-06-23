<?php

namespace App\Filament\Resources\SiswaLayakSertifikatResource\Pages;

use App\Filament\Resources\SiswaLayakSertifikatResource;
use Filament\Resources\Pages\ListRecords;

class ListSiswaLayakSertifikat extends ListRecords
{
    protected static string $resource = SiswaLayakSertifikatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
