<?php

namespace App\Filament\Resources\PengumpulanTugas\Pages;

use App\Filament\Resources\PengumpulanTugas\PengumpulanTugasResource;
use Filament\Resources\Pages\ListRecords;

class ListPengumpulanTugas extends ListRecords
{
    protected static string $resource = PengumpulanTugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->createAnother(false),
        ];
    }
}
