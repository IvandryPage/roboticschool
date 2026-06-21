<?php

namespace App\Filament\Resources\PeminjamanItemAsets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PeminjamanItemAsetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required(),
                Select::make('item_kit_id')
                    ->relationship('itemKit', 'id')
                    ->required(),
                DateTimePicker::make('tanggal_pinjam'),
                DateTimePicker::make('tanggal_jatuh_tempo'),
                DateTimePicker::make('tanggal_kembali'),
                TextInput::make('status'),
                TextInput::make('kondisi_awal'),
                TextInput::make('kondisi_akhir'),
                TextInput::make('diverifikasi_oleh'),
            ]);
    }
}
