<?php

namespace App\Filament\Resources\SertifikatResource\Pages;

use App\Filament\Resources\SertifikatResource;
use Filament\Resources\Pages\ListRecords;

class ListSertifikat extends ListRecords
{
    protected static string $resource = SertifikatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
