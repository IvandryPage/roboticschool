<?php

namespace App\Filament\Resources\SesiLives\Pages;

use App\Filament\Resources\SesiLives\SesiLiveResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSesiLive extends CreateRecord
{
    protected static string $resource = SesiLiveResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('create'); 
    }
}
