<?php

namespace App\Filament\Resources\PengumpulanTugas\Pages;

use App\Filament\Resources\PengumpulanTugas\PengumpulanTugasResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPengumpulanTugas extends ViewRecord
{
    protected static string $resource = PengumpulanTugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
