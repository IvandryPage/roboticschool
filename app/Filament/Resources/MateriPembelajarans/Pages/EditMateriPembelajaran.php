<?php

namespace App\Filament\Resources\MateriPembelajarans\Pages;

use App\Filament\Resources\MateriPembelajarans\MateriPembelajaranResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMateriPembelajaran extends EditRecord
{
    protected static string $resource = MateriPembelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
