<?php

namespace App\Filament\Resources\ProgramKursuses\Pages;

use App\Filament\Resources\ProgramKursuses\ProgramKursusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgramKursuses extends ListRecords
{
    protected static string $resource = ProgramKursusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
