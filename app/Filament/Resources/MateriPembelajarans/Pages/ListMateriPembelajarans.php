<?php

namespace App\Filament\Resources\MateriPembelajarans\Pages;

use App\Filament\Resources\MateriPembelajarans\MateriPembelajaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMateriPembelajarans extends ListRecords
{
    protected static string $resource = MateriPembelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
