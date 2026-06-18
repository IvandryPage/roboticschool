<?php

namespace App\Filament\Resources\SesiLives\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SesiLiveInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('kelas.id')
                    ->label('Kelas'),
                TextEntry::make('nomor_sesi')
                    ->numeric(),
                TextEntry::make('judul_sesi')
                    ->placeholder('-'),
                TextEntry::make('tanggal')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('jam_mulai')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('jam_selesai')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('platform')
                    ->placeholder('-'),
                TextEntry::make('link_akses')
                    ->placeholder('-'),
                TextEntry::make('keterangan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
