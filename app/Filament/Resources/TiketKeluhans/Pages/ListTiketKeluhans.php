<?php

namespace App\Filament\Resources\TiketKeluhans\Pages;

use App\Filament\Resources\TiketKeluhans\TiketKeluhanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTiketKeluhans extends ListRecords
{
    protected static string $resource = TiketKeluhanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
