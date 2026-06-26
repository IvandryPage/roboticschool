<?php

namespace App\Filament\Resources\Programs;

use App\Filament\Resources\Programs\Pages\CreateProgram;
use App\Filament\Resources\Programs\Pages\EditProgram;
use App\Filament\Resources\Programs\Pages\ListPrograms;
use App\Filament\Resources\Programs\RelationManagers\MateriProgramRelationManager;

use App\Models\ProgramKursus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProgramResource extends Resource
{
    protected static ?string $model = ProgramKursus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'nama_program';

    protected static ?string $navigationLabel = 'Program Kursus';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    /** Semua role dalam panel bisa melihat program kursus */
    public static function canViewAny(): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Admin Akademik', 'Tim Publikasi']);
    }

    /** Hanya Tim Publikasi dan Admin yang bisa tambah program */
    public static function canCreate(): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Tim Publikasi', 'Admin Akademik']);
    }

    public static function canEdit($record): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Tim Publikasi', 'Admin Akademik']);
    }

    public static function canDelete($record): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Tim Publikasi', 'Admin Akademik']);
    }

    public static function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Programs\Schemas\ProgramForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\Programs\Tables\ProgramsTable::configure($table);
    }

    public static function getRelationManagers(): array
    {
        return [
            MateriProgramRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPrograms::route('/'),
            'create' => CreateProgram::route('/create'),
            'edit'   => EditProgram::route('/{record}/edit'),
        ];
    }
}