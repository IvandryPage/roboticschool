<?php

namespace App\Filament\Resources\ProgramKursuses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProgramKursusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_program')
                    ->label('Nama Program')
                    ->required()
                    ->maxLength(255),

                Select::make('level')
                    ->options([
                        'Dasar' => 'Dasar',
                        'Menengah' => 'Menengah',
                        'Lanjutan' => 'Lanjutan',
                    ])
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(4)
                    ->columnSpanFull(),

                TextInput::make('biaya')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('durasi_minggu')
                    ->label('Durasi (Minggu)')
                    ->numeric()
                    ->required(),

                FileUpload::make('gambar')
                    ->image()
                    ->directory('program-kursus'),
                Repeater::make('materiProgram')
                    ->label('Daftar Materi')
                    ->relationship()
                    ->schema([
                        TextInput::make('nomor_urut')
                            ->label('Nomor Urut')
                            ->numeric()
                            ->required(),
                        TextInput::make('judul_materi')
                            ->required(),
                        Textarea::make('deskripsi_materi'),
                    ])
                    ->label('Daftar Materi')
                    ->defaultItems(1)
                    ->columnSpanFull(),

                Toggle::make('status_tampil')
                    ->label('Tampilkan di Landing Page')
                    ->default(true),
            ]);
    }
}
