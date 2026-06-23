<?php

namespace App\Filament\Resources\TiketKeluhans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TiketKeluhanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subjek')
                    ->label('Subjek Keluhan')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('kategori')
                    ->label('Kategori')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('prioritas')
                    ->label('Prioritas')
                    ->disabled()
                    ->dehydrated(false),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Keluhan')
                    ->disabled()
                    ->dehydrated(false)
                    ->rows(4)
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Update Status')
                    ->options([
                        'Open'        => 'Open',
                        'In Progress' => 'In Progress',
                        'Resolved'    => 'Resolved',
                    ])
                    ->required(),
            ]);
    }
}
