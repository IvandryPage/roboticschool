<?php

namespace App\Filament\Resources\ForumTopiks\Pages;

use App\Filament\Resources\ForumTopiks\ForumTopikResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewForumTopik extends ViewRecord
{
    protected static string $resource = ForumTopikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
