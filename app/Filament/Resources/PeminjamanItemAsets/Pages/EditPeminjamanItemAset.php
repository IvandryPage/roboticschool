<?php

namespace App\Filament\Resources\PeminjamanItemAsets\Pages;

use App\Filament\Resources\PeminjamanItemAsets\PeminjamanItemAsetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPeminjamanItemAset extends EditRecord
{
    protected static string $resource = PeminjamanItemAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
