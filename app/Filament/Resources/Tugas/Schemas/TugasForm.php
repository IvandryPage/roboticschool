<?php

namespace App\Filament\Resources\Tugas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class TugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               TextInput::make('sesi_id')
                    ->numeric()
                    ->required()
                    ->label('ID Sesi'),
                TextInput::make('judul_tugas')
                    ->required()
                    ->maxLength(255),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                FileUpload::make('file_soal')
                    ->label('File Soal')
                    ->directory('tugas_soal'),
                DateTimePicker::make('batas_waktu')
                    ->label('Batas Waktu Pengumpulan'),
                TextInput::make('nilai_maksimum')
                    ->numeric()
                    ->default(100)
                    ->required(),
            ]);
    }
}
