<?php

namespace App\Filament\Resources\Pembayarans\Pages;

use App\Filament\Resources\Pembayarans\PembayaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListPembayarans extends ListRecords
{
    protected static string $resource = PembayaranResource::class;

    public ?string $activeTab = 'menunggu_verifikasi';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'menunggu_verifikasi' => Tab::make('Menunggu Verifikasi')
                ->query(fn ($query) => $query->where('status', 'Pending')),
            'sukses' => Tab::make('Sukses')
                ->query(fn ($query) => $query->where('status', 'Sukses')),
            'gagal' => Tab::make('Gagal')
                ->query(fn ($query) => $query->where('status', 'Gagal')),
            'semua' => Tab::make('Semua'),
        ];
    }
}
