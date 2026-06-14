<?php

namespace App\Filament\Resources\MateriPembelajarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MateriPembelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sesi_id')
                    ->required(),
                TextInput::make('judul'),
                TextInput::make('tipe_konten'),
                TextInput::make('file_path_atau_url')
                    ->url(),
                TextInput::make('urutan')
                    ->numeric(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
