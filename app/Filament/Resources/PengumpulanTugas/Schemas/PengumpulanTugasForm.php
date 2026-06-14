<?php

namespace App\Filament\Resources\PengumpulanTugas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PengumpulanTugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tugas_id')
                    ->relationship('tugas', 'id')
                    ->required(),
                Select::make('siswa_id')
                    ->relationship('siswa', 'id')
                    ->required(),
                TextInput::make('file_jawaban'),
                Textarea::make('catatan_siswa')
                    ->columnSpanFull(),
                DateTimePicker::make('waktu_kumpul'),
                TextInput::make('nilai')
                    ->numeric(),
                Textarea::make('umpan_balik')
                    ->columnSpanFull(),
                TextInput::make('status_penilaian'),
            ]);
    }
}
