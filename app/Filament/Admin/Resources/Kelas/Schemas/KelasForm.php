<?php

namespace App\Filament\Admin\Resources\Kelas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('batch_id')
                    ->relationship('batch', 'nama_batch')
                    ->label('Batch')
                    ->required(),
                TextInput::make('nama_kelas')
                    ->required(),
                Select::make('instruktur_id')
                    ->relationship('instruktur', 'nama_lengkap', fn ($query) => $query->whereHas('role', fn ($q) => $q->where('nama_role', 'Instruktur')))
                    ->label('Instruktur')
                    ->required(),
                TextInput::make('kapasitas')
                    ->numeric()
                    ->required(),
                Select::make('status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Nonaktif' => 'Nonaktif',
                        'Selesai' => 'Selesai',
                    ])
                    ->required(),
            ]);
    }
}
