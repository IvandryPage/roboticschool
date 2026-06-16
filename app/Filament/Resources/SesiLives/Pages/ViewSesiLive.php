<?php

namespace App\Filament\Resources\SesiLives\Pages;

use App\Filament\Resources\SesiLives\SesiLiveResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSesiLive extends ViewRecord
{
    protected static string $resource = SesiLiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
