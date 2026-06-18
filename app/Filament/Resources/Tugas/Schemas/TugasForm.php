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
                // 1. Mengubah TextInput jadi Select agar Instruktur tinggal klik dan pilih Sesi
                TextInput::make('sesi_id')
    ->numeric()
    ->required()
    ->label('ID Sesi Pembelajaran'),

                TextInput::make('judul_tugas')
                    ->required()
                    ->maxLength(255)
                    ->label('Judul Tugas'),

                // 2. Menambahkan required agar instruktur tidak lupa menulis detail tugas
                Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull()
                    ->label('Deskripsi Tugas'),

                // 3. File upload secara default sudah opsional di Filament jika tidak diberi ->required()
                FileUpload::make('file_soal')
                    ->label('File Soal (Opsional)')
                    ->directory('tugas_soal'),

                // 4. Menambahkan required agar batas waktu pengumpulan wajib ditentukan
                DateTimePicker::make('batas_waktu')
                    ->required()
                    ->label('Batas Waktu Pengumpulan'),

                TextInput::make('nilai_maksimum')
                    ->numeric()
                    ->default(100)
                    ->required()
                    ->label('Nilai Maksimum'),
            ]);
    }
}