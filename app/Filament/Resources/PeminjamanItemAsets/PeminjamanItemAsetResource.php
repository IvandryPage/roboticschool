<?php

namespace App\Filament\Resources\PeminjamanItemAsets;

use App\Filament\Resources\PeminjamanItemAsets\Pages\CreatePeminjamanItemAset;
use App\Filament\Resources\PeminjamanItemAsets\Pages\EditPeminjamanItemAset;
use App\Filament\Resources\PeminjamanItemAsets\Pages\ListPeminjamanItemAsets;
use App\Filament\Resources\PeminjamanItemAsets\Schemas\PeminjamanItemAsetForm;
use App\Filament\Resources\PeminjamanItemAsets\Tables\PeminjamanItemAsetsTable;
use App\Models\PeminjamanItemAset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PeminjamanItemAsetResource extends Resource
{
    protected static ?string $model = PeminjamanItemAset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'status';

    public static function form(Schema $schema): Schema
    {
        return PeminjamanItemAsetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PeminjamanItemAsetsTable::configure($table);
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
            'index' => ListPeminjamanItemAsets::route('/'),
            'create' => CreatePeminjamanItemAset::route('/create'),
            'edit' => EditPeminjamanItemAset::route('/{record}/edit'),
        ];
    }
}
