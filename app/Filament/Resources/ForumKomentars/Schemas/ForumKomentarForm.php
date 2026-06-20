<?php

namespace App\Filament\Resources\ForumKomentars\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ForumKomentarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('topik_id')
                    ->relationship('topik', 'judul')
                    ->searchable()
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'nama_lengkap')
                    ->searchable()
                    ->required(),
                Textarea::make('komentar')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
