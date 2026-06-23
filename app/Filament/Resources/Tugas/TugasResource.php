<?php

namespace App\Filament\Resources\Tugas;

use App\Filament\Resources\Tugas\Pages\CreateTugas;
use App\Filament\Resources\Tugas\Pages\EditTugas;
use App\Filament\Resources\Tugas\Pages\ListTugas;
use App\Filament\Resources\Tugas\Pages\ViewTugas;
use App\Filament\Resources\Tugas\Schemas\TugasForm;
use App\Filament\Resources\Tugas\Schemas\TugasInfolist;
use App\Filament\Resources\Tugas\Tables\TugasTable;
use App\Models\Tugas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TugasResource extends Resource
{
    protected static ?string $model = Tugas::class;

    protected static ?string $modelLabel = 'Tugas';

    protected static ?string $pluralModelLabel = 'Tugas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'yes';

    protected static ?string $navigationLabel = 'Penugasan';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    /** Admin dan Instruktur dapat melihat tugas */
    public static function canViewAny(): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Admin Akademik', 'Instruktur']);
    }

    /** Instruktur yang bisa tambah/edit/hapus tugas */
    public static function canCreate(): bool
    {
        return Auth::user()?->role?->nama_role === 'Instruktur';
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->role?->nama_role === 'Instruktur';
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->role?->nama_role === 'Instruktur';
    }

    public static function form(Schema $schema): Schema
    {
        return TugasForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TugasInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TugasTable::configure($table);
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
            'index' => ListTugas::route('/'),
            'create' => CreateTugas::route('/create'),
            'view' => ViewTugas::route('/{record}'),
            'edit' => EditTugas::route('/{record}/edit'),
        ];
    }
}
