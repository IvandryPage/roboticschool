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
use Illuminate\Support\Facades\Auth;

class SesiLiveResource extends Resource
{
    protected static ?string $model = SesiLive::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'robotic';

    protected static ?string $navigationLabel = 'Jadwal Sesi Live';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    /** Admin, Instruktur, dan Direktur dapat melihat sesi */
    public static function canViewAny(): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Admin Akademik', 'Instruktur']);
    }

    /** Admin dan Instruktur dapat mengelola sesi */
    public static function canCreate(): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Admin Akademik', 'Instruktur']);
    }

    public static function canEdit($record): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Admin Akademik', 'Instruktur']);
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

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

    /** Instruktur hanya lihat sesi live kelas miliknya */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $q = parent::getEloquentQuery();
        if (\Illuminate\Support\Facades\Auth::user()?->role?->nama_role === 'Instruktur') {
            $ids = \App\Models\Kelas::where('instruktur_id', \Illuminate\Support\Facades\Auth::id())->pluck('id');
            return $q->whereIn('kelas_id', $ids);
        }
        return $q;
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
