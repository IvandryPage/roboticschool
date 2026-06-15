<?php

namespace App\Filament\Resources\PengumpulanTugas;

use App\Filament\Resources\PengumpulanTugas\Pages\CreatePengumpulanTugas;
use App\Filament\Resources\PengumpulanTugas\Pages\EditPengumpulanTugas;
use App\Filament\Resources\PengumpulanTugas\Pages\ListPengumpulanTugas;
use App\Filament\Resources\PengumpulanTugas\Pages\ViewPengumpulanTugas;
use App\Filament\Resources\PengumpulanTugas\Schemas\PengumpulanTugasForm;
use App\Filament\Resources\PengumpulanTugas\Schemas\PengumpulanTugasInfolist;
use App\Filament\Resources\PengumpulanTugas\Tables\PengumpulanTugasTable;
use App\Models\PengumpulanTugas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PengumpulanTugasResource extends Resource
{
    protected static ?string $model = PengumpulanTugas::class;

    protected static ?string $modelLabel = 'Pengumpulan Tugas';

    protected static ?string $pluralModelLabel = 'Pengumpulan Tugas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'yes';

    public static function form(Schema $schema): Schema
    {
        return PengumpulanTugasForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PengumpulanTugasInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengumpulanTugasTable::configure($table);
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
            'index' => ListPengumpulanTugas::route('/'),
            'create' => CreatePengumpulanTugas::route('/create'),
            'view' => ViewPengumpulanTugas::route('/{record}'),
            'edit' => EditPengumpulanTugas::route('/{record}/edit'),
        ];
    }
}
