<?php

namespace App\Filament\Resources\MateriPembelajarans\Pages;

use App\Filament\Resources\MateriPembelajarans\MateriPembelajaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMateriPembelajaran extends CreateRecord
{
    protected static string $resource = MateriPembelajaranResource::class;
    
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
