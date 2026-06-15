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
                Select::make('pelapor_id')
                    ->label('Pelapor')
                    ->relationship('pelapor', 'name')
                    ->required(),

                Select::make('kategori')
                    ->label('Kategori Keluhan')
                    ->options([
                        'Akademik' => 'Akademik',
                        'Teknis' => 'Teknis',
                    ])
                    ->required(),

                TextInput::make('subjek')
                    ->label('Subjek Keluhan')
                    ->required()
                    ->maxLength(255),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Keluhan')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),

                Select::make('prioritas')
                    ->label('Prioritas')
                    ->options([
                        'Rendah' => 'Rendah',
                        'Sedang' => 'Sedang',
                        'Tinggi' => 'Tinggi',
                    ])
                    ->default('Sedang')
                    ->required(),

                TextInput::make('status')
                    ->label('Status')
                    ->default('Open')
                    ->disabled()
                    ->dehydrated(),
            ]);
    }
}