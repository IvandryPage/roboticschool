<?php

namespace App\Filament\Resources\ForumTopiks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ForumTopikForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->required(),
                Select::make('pembuat_id')
                    ->relationship('pembuat', 'nama_lengkap')
                    ->searchable()
                    ->required(),
                TextInput::make('judul')
                    ->required(),
                Textarea::make('konten')
                    ->columnSpanFull(),
            ]);
    }
}
