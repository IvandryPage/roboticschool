<?php

namespace App\Filament\Resources\ForumTopiks\Pages;

use App\Filament\Resources\ForumTopiks\ForumTopikResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditForumTopik extends EditRecord
{
    protected static string $resource = ForumTopikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
