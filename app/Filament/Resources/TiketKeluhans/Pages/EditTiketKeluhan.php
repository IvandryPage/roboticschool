<?php

namespace App\Filament\Resources\TiketKeluhans\Pages;

use App\Filament\Resources\TiketKeluhans\TiketKeluhanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTiketKeluhan extends EditRecord
{
    protected static string $resource = TiketKeluhanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
