<?php

namespace App\Filament\Admin\Resources\RekapKemajuanSiswas\Pages;

use App\Filament\Admin\Resources\RekapKemajuanSiswas\RekapKemajuanSiswaResource;
use Filament\Resources\Pages\ManageRecords;

class ManageRekapKemajuanSiswas extends ManageRecords
{
    protected static string $resource = RekapKemajuanSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return []; // Dikosongkan karena instruktur hanya melihat rekap, tidak menambah data dari sini
    }
}