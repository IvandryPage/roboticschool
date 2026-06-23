<?php

namespace App\Filament\Admin\Resources\KemajuanBelajars\Pages;

use App\Filament\Admin\Resources\KemajuanBelajars\KemajuanBelajarResource;
use App\Filament\Admin\Resources\KemajuanBelajars\Widgets\KemajuanBelajarStatsWidget;
use Filament\Resources\Pages\ManageRecords;

class ManageKemajuanBelajars extends ManageRecords
{
    protected static string $resource = KemajuanBelajarResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            KemajuanBelajarStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}