<?php

namespace App\Filament\Resources\MateriPembelajarans\Pages;

use App\Filament\Resources\MateriPembelajarans\MateriPembelajaranResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMateriPembelajaran extends ViewRecord
{
    protected static string $resource = MateriPembelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
