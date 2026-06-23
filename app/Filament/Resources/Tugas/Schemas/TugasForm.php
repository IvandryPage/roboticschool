<?php

namespace App\Filament\Resources\Tugas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class TugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // PERBAIKAN: Mengganti TextInput numeric menjadi Select dengan relationship
                Select::make('sesi_id')
    ->label('Pilih Sesi')
    ->relationship(name: 'sesi', titleAttribute: 'judul_sesi')
    // TAMBAHKAN BARIS INI: Penyelamat jika ada judul_sesi yang NULL di database
    ->getOptionLabelFromRecordUsing(fn ($record) => $record->judul_sesi ?? 'Sesi ' . $record->nomor_sesi ?? 'Sesi Tanpa Judul')
    ->required()
    ->searchable()
    ->preload(),

                TextInput::make('judul_tugas')
                    ->required()
                    ->maxLength(255),
                    
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                    
                FileUpload::make('file_soal')
                    ->label('File Soal (Opsional)')
                    ->directory('tugas_soal'),
                    
                DateTimePicker::make('batas_waktu')
                    ->label('Batas Waktu Pengumpulan')
                    ->native(false), // Menambahkan native(false) agar tampilan kalender lebih rapi
                    
                TextInput::make('nilai_maksimum')
                    ->numeric()
                    ->default(100)
                    ->required()
                    ->label('Nilai Maksimum'),
            ]);
    }
}