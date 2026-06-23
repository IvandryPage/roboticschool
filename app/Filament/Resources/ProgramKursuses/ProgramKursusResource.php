<?php

namespace App\Filament\Resources\ProgramKursuses;

use App\Filament\Resources\ProgramKursuses\Pages\CreateProgramKursus;
use App\Filament\Resources\ProgramKursuses\Pages\EditProgramKursus;
use App\Filament\Resources\ProgramKursuses\Pages\ListProgramKursuses;
use App\Filament\Resources\ProgramKursuses\Schemas\ProgramKursusForm;
use App\Filament\Resources\ProgramKursuses\Tables\ProgramKursusesTable;
use App\Models\ProgramKursus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProgramKursusResource extends Resource
{
    protected static ?string $model = ProgramKursus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_program';

    public static function form(Schema $schema): Schema
    {
        return ProgramKursusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramKursusesTable::configure($table);
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
            'index' => ListProgramKursuses::route('/'),
            'create' => CreateProgramKursus::route('/create'),
            'edit' => EditProgramKursus::route('/{record}/edit'),
        ];
    }
}
