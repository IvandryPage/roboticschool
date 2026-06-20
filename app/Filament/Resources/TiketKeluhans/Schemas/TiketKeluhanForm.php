<?php

namespace App\Filament\Resources\TiketKeluhans\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class TiketKeluhanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('pelapor_id')
                    ->default(fn () => Auth::id()),

                Select::make('kategori')
                    ->label('Kategori Keluhan')
                    ->options([
                        'Pembelajaran' => 'Pembelajaran',
                        'Error Sistem' => 'Error Sistem',
                        'Pendaftaran & Pembayaran' => 'Pendaftaran & Pembayaran',
                        'Hal Lainnya' => 'Hal Lainnya',
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

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Open' => 'Open',
                        'In Progress' => 'In Progress',
                        'Resolved' => 'Resolved',
                        'Closed' => 'Closed',
                    ])
                    ->default('Open')
                    ->required(),
            ]);
    }
}