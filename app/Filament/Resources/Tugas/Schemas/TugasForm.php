<?php

namespace App\Filament\Resources\Tugas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sesi_id')
                    ->relationship('sesi', 'id')
                    ->required(),
                TextInput::make('judul_tugas')
                    ->required(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                TextInput::make('file_soal'),
                DateTimePicker::make('batas_waktu'),
                TextInput::make('nilai_maksimum')
                    ->numeric(),
            ]);
    }
}
