<?php

namespace App\Filament\Resources\ForumTopiks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ForumTopikInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('judul')
                    ->size('lg'),

                TextEntry::make('pembuat.nama_lengkap')
                    ->label('Pembuat'),

                TextEntry::make('kelas.nama_kelas')
                    ->label('Kelas'),

                TextEntry::make('created_at')
                    ->label('Diposting')
                    ->since(),

                TextEntry::make('konten')
                    ->label('Isi Diskusi')
                    ->columnSpanFull(),
            ]);
    }
}