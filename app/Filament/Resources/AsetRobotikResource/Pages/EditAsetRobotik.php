<?php

namespace App\Filament\Resources\AsetRobotikResource\Pages;

use App\Filament\Resources\AsetRobotikResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditAsetRobotik extends EditRecord
{
    protected static string $resource = AsetRobotikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function () {
                    // Delete child item kits
                    $this->record->itemKits()->delete();
                }),
        ];
    }
}
