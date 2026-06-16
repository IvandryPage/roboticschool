<?php

namespace App\Filament\Resources\SesiLives\Pages;

use App\Filament\Resources\SesiLives\SesiLiveResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSesiLive extends EditRecord
{
    protected static string $resource = SesiLiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
