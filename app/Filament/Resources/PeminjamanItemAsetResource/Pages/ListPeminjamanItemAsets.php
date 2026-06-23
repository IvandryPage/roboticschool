<?php

namespace App\Filament\Resources\PeminjamanItemAsetResource\Pages;

use App\Filament\Resources\PeminjamanItemAsetResource;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamanItemAsets extends ListRecords
{
    protected static string $resource = PeminjamanItemAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
