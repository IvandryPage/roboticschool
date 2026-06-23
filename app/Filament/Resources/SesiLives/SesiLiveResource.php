<?php

namespace App\Filament\Resources\SesiLives;

use App\Filament\Resources\SesiLives\Pages\CreateSesiLive;
use App\Filament\Resources\SesiLives\Pages\EditSesiLive;
use App\Filament\Resources\SesiLives\Pages\ListSesiLives;
use App\Filament\Resources\SesiLives\Pages\ViewSesiLive;
use App\Filament\Resources\SesiLives\Schemas\SesiLiveForm;
use App\Filament\Resources\SesiLives\Schemas\SesiLiveInfolist;
use App\Filament\Resources\SesiLives\Tables\SesiLivesTable;
use App\Models\SesiLive;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SesiLiveResource extends Resource
{
    protected static ?string $model = SesiLive::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'robotic';

    public static function form(Schema $schema): Schema
    {
        return SesiLiveForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SesiLiveInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SesiLivesTable::configure($table);
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
            'index' => ListSesiLives::route('/'),
            'create' => CreateSesiLive::route('/create'),
            'view' => ViewSesiLive::route('/{record}'),
            'edit' => EditSesiLive::route('/{record}/edit'),
        ];
    }
}
