<?php

namespace App\Filament\Resources\ForumKomentars;

use App\Filament\Resources\ForumKomentars\Pages\CreateForumKomentar;
use App\Filament\Resources\ForumKomentars\Pages\EditForumKomentar;
use App\Filament\Resources\ForumKomentars\Pages\ListForumKomentars;
use App\Filament\Resources\ForumKomentars\Schemas\ForumKomentarForm;
use App\Filament\Resources\ForumKomentars\Tables\ForumKomentarsTable;
use App\Models\ForumKomentar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ForumKomentarResource extends Resource
{
    protected static ?string $model = ForumKomentar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'komentar';

    public static function form(Schema $schema): Schema
    {
        return ForumKomentarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ForumKomentarsTable::configure($table);
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
            'index' => ListForumKomentars::route('/'),
            'create' => CreateForumKomentar::route('/create'),
            'edit' => EditForumKomentar::route('/{record}/edit'),
        ];
    }
}
