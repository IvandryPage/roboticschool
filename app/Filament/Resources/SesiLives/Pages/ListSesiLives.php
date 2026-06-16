<?php

namespace App\Filament\Resources\SesiLives\Pages;

use App\Filament\Resources\SesiLives\SesiLiveResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSesiLives extends ListRecords
{
    protected static string $resource = SesiLiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
