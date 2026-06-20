<?php

namespace App\Filament\Resources\Programs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_program')
                    ->label('Nama Program Kursus')
                    ->required() // Wajib diisi agar tidak memicu error database
                    ->maxLength(255),

                TextInput::make('level')
                    ->label('Tingkat Kesulitan / Level')
                    ->placeholder('Contoh: Pemula, Menengah, Mahir')
                    ->maxLength(255),

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
                    ->directory('program-gambar'), // Gambar akan disimpan di folder storage/app/public/program-gambar

                Toggle::make('status_tampil')
                    ->label('Tampilkan Program Ini?')
                    ->default(true),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Lengkap')
                    ->columnSpanFull(),
            ]);
    }
}