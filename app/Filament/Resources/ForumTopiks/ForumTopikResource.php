<?php

namespace App\Filament\Resources\ForumTopiks;

use App\Filament\Resources\ForumTopiks\Pages\CreateForumTopik;
use App\Filament\Resources\ForumTopiks\Pages\EditForumTopik;
use App\Filament\Resources\ForumTopiks\Pages\ListForumTopiks;
use App\Filament\Resources\ForumTopiks\Pages\ViewForumTopik;
use App\Filament\Resources\ForumTopiks\Schemas\ForumTopikForm;
use App\Filament\Resources\ForumTopiks\Schemas\ForumTopikInfolist;
use App\Filament\Resources\ForumTopiks\Tables\ForumTopiksTable;
use App\Models\ForumTopik;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;

class ForumTopikResource extends Resource
{
    protected static ?string $model = ForumTopik::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return ForumTopikForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ForumTopikInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ForumTopiksTable::configure($table);
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
            'index' => ListForumTopiks::route('/'),
            'create' => CreateForumTopik::route('/create'),
            'view' => ViewForumTopik::route('/{record}'),
            'edit' => EditForumTopik::route('/{record}/edit'),
        ];
    }
}
