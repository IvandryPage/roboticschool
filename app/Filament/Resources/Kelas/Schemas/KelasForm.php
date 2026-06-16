<?php

namespace App\Filament\Resources\Kelas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('batch_id')
                    ->relationship('batch', 'id')
                    ->required(),
                TextInput::make('nama_kelas')
                    ->required(),
                Select::make('instruktur_id')
                    ->relationship('instruktur', 'name')
                    ->required(),
                TextInput::make('kapasitas')
                    ->numeric(),
                TextInput::make('status'),
            ]);
    }
}
