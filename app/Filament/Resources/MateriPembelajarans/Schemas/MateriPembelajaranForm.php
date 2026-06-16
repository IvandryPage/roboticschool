<?php

namespace App\Filament\Resources\MateriPembelajarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MateriPembelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Materi Pembelajaran')
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
                ->live() // <-- Ini penting supaya form-nya dinamis real-time
                ->required(),

            // ALTERNATIF 1: Muncul kalau pilih Video atau PDF
            FileUpload::make('file_materi')
                ->label('Unggah File Materi')
                ->directory('materi_pembelajaran')
                ->statePath('file_path_atau_url')
                ->visible(fn ($get) => in_array($get('tipe_konten'), ['video', 'pdf']))
                ->required(fn ($get) => in_array($get('tipe_konten'), ['video', 'pdf'])),

            // ALTERNATIF 2: Muncul kalau pilih Eksternal Link (Sesuai PBI-097 kamu)
            TextInput::make('file_path_atau_url')
                ->label('Link Tautan (YouTube / Google Drive)')
                ->url()
                ->placeholder('https://youtube.com/... atau https://drive.google.com/...')
                ->visible(fn ($get) => $get('tipe_konten') === 'link')
                ->required(fn ($get) => $get('tipe_konten') === 'link'),
                        TextInput::make('urutan')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->default(1),

                        Textarea::make('keterangan')
                            ->label('Keterangan Singkat')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}