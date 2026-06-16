<?php

namespace App\Filament\Resources\SesiLives\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class SesiLiveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kelas_id')
                    ->relationship('kelas', 'id')
                    ->required(),
                TextInput::make('nomor_sesi')
                    ->required()
                    ->numeric(),
                TextInput::make('judul_sesi'),
                DatePicker::make('tanggal'),
                TimePicker::make('jam_mulai'),
                TimePicker::make('jam_selesai'),
                TextInput::make('platform'),
                TextInput::make('link_akses'),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
