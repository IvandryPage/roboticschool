<?php

namespace App\Filament\Resources\ForumKomentars\Pages;

use App\Filament\Resources\ForumKomentars\ForumKomentarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditForumKomentar extends EditRecord
{
    protected static string $resource = ForumKomentarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
