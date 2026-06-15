<?php

namespace App\Filament\Resources\MateriPembelajarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Schemas\Schema;

class MateriPembelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            ]);
    }
}