<?php

namespace App\Filament\Resources\ProgramKursuses\Pages;

use App\Filament\Resources\ProgramKursuses\ProgramKursusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProgramKursus extends EditRecord
{
    protected static string $resource = ProgramKursusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
