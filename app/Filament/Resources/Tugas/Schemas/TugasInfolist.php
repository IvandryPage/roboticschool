<?php

namespace App\Filament\Resources\Tugas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TugasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('sesi.id')
                    ->label('Sesi'),
                TextEntry::make('judul_tugas'),
                TextEntry::make('deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('file_soal')
                    ->placeholder('-'),
                TextEntry::make('batas_waktu')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('nilai_maksimum')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
