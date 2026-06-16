<?php

namespace App\Filament\Resources\PengumpulanTugas\Pages;

use App\Filament\Resources\PengumpulanTugas\PengumpulanTugasResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPengumpulanTugas extends EditRecord
{
    protected static string $resource = PengumpulanTugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
