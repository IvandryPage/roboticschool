<?php

namespace App\Filament\Resources\MateriPembelajarans;

use App\Filament\Resources\MateriPembelajarans\Pages\CreateMateriPembelajaran;
use App\Filament\Resources\MateriPembelajarans\Pages\EditMateriPembelajaran;
use App\Filament\Resources\MateriPembelajarans\Pages\ListMateriPembelajarans;
use App\Filament\Resources\MateriPembelajarans\Pages\ViewMateriPembelajaran;
use App\Filament\Resources\MateriPembelajarans\Schemas\MateriPembelajaranForm;
use App\Filament\Resources\MateriPembelajarans\Schemas\MateriPembelajaranInfolist;
use App\Filament\Resources\MateriPembelajarans\Tables\MateriPembelajaransTable;
use App\Models\MateriPembelajaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MateriPembelajaranResource extends Resource
{
    protected static ?string $model = MateriPembelajaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static ?string $modelLabel = 'Materi Pembelajaran';

    protected static ?string $pluralModelLabel = 'Materi Pembelajaran';

    protected static ?string $recordTitleAttribute = 'yes';

    public static function form(Schema $schema): Schema
    {
        return MateriPembelajaranForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MateriPembelajaranInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MateriPembelajaransTable::configure($table);
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
            'index' => ListMateriPembelajarans::route('/'),
            'create' => CreateMateriPembelajaran::route('/create'),
            'view' => ViewMateriPembelajaran::route('/{record}'),
            'edit' => EditMateriPembelajaran::route('/{record}/edit'),
        ];
    }
}
