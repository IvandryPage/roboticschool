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

    // PERBAIKAN: Ubah menjadi 'nama_lengkap' sesuai kolom di database
    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Instrukturs\Schemas\InstrukturForm::configure($schema);
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