<?php

namespace App\Filament\Resources\Instrukturs;

use App\Filament\Resources\Instrukturs\Pages\CreateInstruktur;
use App\Filament\Resources\Instrukturs\Pages\EditInstruktur;
use App\Filament\Resources\Instrukturs\Pages\ListInstrukturs;
use App\Models\Instruktur;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InstrukturResource extends Resource
{
    protected static ?string $model = Instruktur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_instruktur';

   public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('nama_lengkap')
                    ->required()
                    ->maxLength(255),
                
                \Filament\Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                    
                \Filament\Forms\Components\TextInput::make('spesialisasi')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\Instrukturs\Tables\InstruktursTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstrukturs::route('/'),
            'create' => CreateInstruktur::route('/create'),
            'edit' => EditInstruktur::route('/{record}/edit'),
        ];
    }
}