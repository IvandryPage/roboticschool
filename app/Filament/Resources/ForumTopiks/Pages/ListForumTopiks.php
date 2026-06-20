<?php

namespace App\Filament\Resources\ForumTopiks\Pages;

use App\Filament\Resources\ForumTopiks\ForumTopikResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListForumTopiks extends ListRecords
{
    protected static string $resource = ForumTopikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
