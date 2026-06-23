<?php

namespace App\Filament\Resources\MateriPembelajarans\Schemas;

use Filament\Schemas\Schema; 
use Filament\Forms\Components\TextInput;
// ... (hapus kalau ada "use Filament\Forms\Form;")
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use App\Models\SesiLive;


class MateriPembelajaranForm
{
    public static function configure(Schema $schema): Schema // HARUS SCHEMA
    {
        return $schema->components([
            Select::make('sesi_id')
                ->label('Pilih Sesi Live')
                ->options(
                    // Mengambil data dengan aman dan mengabaikan judul yang kosong (null)
                    SesiLive::whereNotNull('judul_sesi')->get()->mapWithKeys(function ($sesi) {
                        return [$sesi->id => "Sesi {$sesi->nomor_sesi} - {$sesi->judul_sesi}"];
                    })
                )
                ->searchable()
                ->required(),

            TextInput::make('judul')
                ->label('Judul Materi')
                ->required()
                ->maxLength(255),

            Select::make('tipe_konten')
                ->label('Tipe Konten')
                ->options([
                    'pdf' => 'PDF',
                    'video' => 'Video',
                    'link' => 'Eksternal Link',
                ])
                ->required(),

            TextInput::make('file_path_atau_url')
                ->label('File atau URL')
                ->required()
                ->columnSpanFull(),

            TextInput::make('urutan')
                ->label('Urutan Materi')
                ->numeric()
                ->default(1),

            Textarea::make('keterangan')
                ->label('Keterangan')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }
}