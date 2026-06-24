<?php

namespace App\Filament\Publikasi\Resources\MateriPrograms\Pages;

use App\Filament\Publikasi\Resources\MateriPrograms\MateriProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMateriPrograms extends ManageRecords
{
    protected static string $resource = MateriProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
