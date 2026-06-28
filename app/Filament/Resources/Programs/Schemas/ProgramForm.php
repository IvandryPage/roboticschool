<?php

namespace App\Filament\Resources\Programs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_program')
                    ->label('Nama Program Kursus')
                    ->required()
                    ->maxLength(255),

                Select::make('level')
                    ->label('Tingkat Kesulitan / Level')
                    ->options([
                        'Pemula'    => 'Pemula',
                        'Menengah'  => 'Menengah',
                        'Mahir'     => 'Mahir',
                    ])
                    ->placeholder('Pilih level'),

                TextInput::make('biaya')
                    ->label('Biaya Program (Rp)')
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp'),

                TextInput::make('durasi_minggu')
                    ->label('Durasi Kursus (Minggu)')
                    ->numeric()
                    ->suffix('Minggu'),

                FileUpload::make('gambar')
                    ->label('Upload Gambar/Poster Program')
                    ->image()
                    ->disk('public')                      // ← FIX: eksplisit disk public
                    ->directory('program-gambar')         // → storage/app/public/program-gambar
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('800')
                    ->imageResizeTargetHeight('450')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)
                    ->helperText('JPG/PNG/WebP, maks 2MB. Rasio ideal 16:9.'),

                Toggle::make('status_tampil')
                    ->label('Tampilkan Program Ini di Landing Page?')
                    ->default(true),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Lengkap')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
