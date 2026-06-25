<?php

namespace App\Filament\Resources\TiketKeluhans\Pages;

use App\Filament\Resources\TiketKeluhans\TiketKeluhanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTiketKeluhan extends CreateRecord
{
    protected static string $resource = TiketKeluhanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pelapor_id'] = auth()->id();
        $data['status']     = 'Open';

        return $data;
    }
}