<?php

namespace App\Filament\Resources\PengumpulanTugasResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PengumpulanTugasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tugas.id')
                    ->label('Tugas'),
                TextEntry::make('siswa.id')
                    ->label('Siswa'),
                TextEntry::make('file_jawaban')
                    ->placeholder('-'),
                TextEntry::make('catatan_siswa')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('waktu_kumpul')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('nilai')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('umpan_balik')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status_penilaian')
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
