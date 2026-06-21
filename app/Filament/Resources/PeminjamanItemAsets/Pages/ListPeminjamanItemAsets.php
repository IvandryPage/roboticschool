<?php

namespace App\Filament\Resources\PeminjamanItemAsets\Pages;

use App\Filament\Resources\PeminjamanItemAsets\PeminjamanItemAsetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamanItemAsets extends ListRecords
{
    protected static string $resource = PeminjamanItemAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
