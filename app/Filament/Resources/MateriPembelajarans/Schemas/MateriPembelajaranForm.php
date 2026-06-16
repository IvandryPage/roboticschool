<?php

namespace App\Filament\Resources\MateriPembelajarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
<<<<<<< HEAD
use Filament\Forms\Components\FileUpload;
=======
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
>>>>>>> 01e1427be3bd9d2e5adbe5a70f2b7ec8f39b390d
use Filament\Schemas\Schema;

class MateriPembelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
<<<<<<< HEAD
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
=======
                Section::make('Informasi Materi')
                    ->schema([
                        TextInput::make('sesi_id')
                            ->label('ID Sesi')
                            ->required(),
                            
                        TextInput::make('judul')
                            ->label('Judul Materi')
                            ->required()
                            ->maxLength(255),

                        Select::make('tipe_konten')
                            ->label('Tipe Konten')
                            ->options([
                                'video' => 'Video',
                                'pdf' => 'PDF',
                                'link' => 'Eksternal Link',
                            ])
                            ->required(),

                        TextInput::make('file_path_atau_url')
                            ->label('File/URL')
                            ->url()
                            ->helperText('Masukkan URL atau path file materi'),

                        TextInput::make('urutan')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->default(1),

                        Textarea::make('keterangan')
                            ->label('Keterangan Singkat')
                            ->columnSpanFull(),
                    ]),
>>>>>>> 01e1427be3bd9d2e5adbe5a70f2b7ec8f39b390d
            ]);
    }
}