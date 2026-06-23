<?php

namespace App\Filament\Resources\ProgramKursuses\Pages;

use App\Filament\Resources\ProgramKursuses\ProgramKursusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProgramKursus extends CreateRecord
{
    protected static string $resource = ProgramKursusResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }
}