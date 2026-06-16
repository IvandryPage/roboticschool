<?php

namespace App\Filament\Resources\Instrukturs\Pages;

use App\Filament\Resources\Instrukturs\InstrukturResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInstrukturs extends ListRecords
{
    protected static string $resource = InstrukturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
