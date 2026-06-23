<?php

namespace App\Filament\Resources\MateriPembelajarans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MateriPembelajaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('sesi_id'),
                TextEntry::make('judul')
                    ->placeholder('-'),
                TextEntry::make('tipe_konten')
                    ->placeholder('-'),
                TextEntry::make('file_path_atau_url')
                    ->placeholder('-'),
                TextEntry::make('urutan')
                    ->numeric()
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
