<?php

namespace App\Filament\Resources\MateriPembelajarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class MateriPembelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sesi_id')
                    ->label('ID Sesi')
                    ->required(),

                TextInput::make('judul')
                    ->label('Judul Materi')
                    ->required()
                    ->maxLength(255),

                TextInput::make('urutan')
                    ->label('Nomor Urut')
                    ->numeric()
                    ->required(),

                FileUpload::make('file_path_atau_url') // Pastikan nama ini sama dengan kolom di database
                    ->label('Unggah File Materi')
                    ->directory('materi_pembelajaran')
                    ->acceptedFileTypes([
                        'application/pdf', 
                        'image/jpeg', 
                        'image/png', 
                        'application/msword', 
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ])
                    ->rules(['mimes:pdf,jpg,jpeg,png,doc,docx'])
                    ->helperText('Format yang diizinkan: PDF, Gambar (JPG/PNG), dan Word (DOC/DOCX).')
                    ->required(),

                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->columnSpanFull(),
            ]);
    }
}