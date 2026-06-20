<?php

namespace App\Filament\Resources\ForumKomentars\Pages;

use App\Filament\Resources\ForumKomentars\ForumKomentarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListForumKomentars extends ListRecords
{
    protected static string $resource = ForumKomentarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
