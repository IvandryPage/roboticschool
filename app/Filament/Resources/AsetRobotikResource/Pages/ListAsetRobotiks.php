<?php

namespace App\Filament\Resources\AsetRobotikResource\Pages;

use App\Filament\Resources\AsetRobotikResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListAsetRobotiks extends ListRecords
{
    protected static string $resource = AsetRobotikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
