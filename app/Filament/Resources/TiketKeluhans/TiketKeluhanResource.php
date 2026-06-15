<?php

namespace App\Filament\Resources\TiketKeluhans;

use App\Filament\Resources\TiketKeluhans\Pages\CreateTiketKeluhan;
use App\Filament\Resources\TiketKeluhans\Pages\EditTiketKeluhan;
use App\Filament\Resources\TiketKeluhans\Pages\ListTiketKeluhans;
use App\Filament\Resources\TiketKeluhans\Schemas\TiketKeluhanForm;
use App\Filament\Resources\TiketKeluhans\Tables\TiketKeluhansTable;
use App\Models\TiketKeluhan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TiketKeluhanResource extends Resource
{
    protected static ?string $model = TiketKeluhan::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedExclamationTriangle;

    protected static ?string $navigationLabel = 'Keluhan';

    protected static ?string $modelLabel = 'Tiket Keluhan';

    protected static ?string $pluralModelLabel = 'Tiket Keluhan';

    protected static ?string $recordTitleAttribute = 'subjek';

    public static function form(Schema $schema): Schema
    {
        return TiketKeluhanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TiketKeluhansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTiketKeluhans::route('/'),
            'create' => CreateTiketKeluhan::route('/create'),
            'edit' => EditTiketKeluhan::route('/{record}/edit'),
        ];
    }
}