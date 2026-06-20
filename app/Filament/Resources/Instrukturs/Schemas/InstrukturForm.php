<?php

namespace App\Filament\Resources\Instrukturs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class InstrukturForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required() // Wajib diisi agar tidak memicu error Not Null di database
                    ->maxLength(255),
                    
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->unique(ignoreRecord: true) // Mencegah email kembar
                    ->maxLength(255),
                    
                TextInput::make('spesialisasi')
                    ->label('Spesialisasi')
                    ->placeholder('Contoh: Robotik Dasar, Pemrograman Python')
                    ->maxLength(255),
            ]);
    }
}