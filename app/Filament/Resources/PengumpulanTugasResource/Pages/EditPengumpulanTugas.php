<?php

namespace App\Filament\Resources\PengumpulanTugasResource\Pages;

use App\Filament\Resources\PengumpulanTugasResource;
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
