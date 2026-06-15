<?php

namespace App\Filament\Resources\PengumpulanTugas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class PengumpulanTugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tugas_id')
                    ->required()
                    ->label('ID Tugas'),
                TextInput::make('siswa_id')
                    ->required()
                    ->label('ID Siswa'),
                FileUpload::make('file_jawaban')
                    ->label('File Jawaban')
                    ->directory('jawaban_tugas'),
                Textarea::make('catatan_siswa')
                    ->label('Catatan Siswa')
                    ->columnSpanFull(),
                DateTimePicker::make('waktu_kumpul')
                    ->label('Waktu Kumpul'),
                TextInput::make('nilai')
                    ->numeric()
                    ->label('Nilai')
                    ->inputMode('decimal'),
                Textarea::make('umpan_balik')
                    ->label('Umpan Balik')
                    ->columnSpanFull(),
                Select::make('status_penilaian')
                    ->options([
                        'Belum Dinilai' => 'Belum Dinilai',
                        'Sudah Dinilai' => 'Sudah Dinilai',
                    ])
                    ->label('Status Penilaian'),
            ]);
    }
}
