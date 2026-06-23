<?php

namespace App\Filament\Resources\Laporans;

use App\Filament\Resources\Laporans\Pages\CreateLaporan;
use App\Filament\Resources\Laporans\Pages\EditLaporan;
use App\Filament\Resources\Laporans\Pages\ListLaporans;
use App\Filament\Resources\Laporans\Schemas\LaporanForm;
use App\Filament\Resources\Laporans\Tables\LaporansTable;
use App\Models\ArsipLaporan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LaporanResource extends Resource
{
    protected static ?string $model = ArsipLaporan::class;

    // FIX: Gunakan method getNavigationGroup() bukan property typed yang konflik di Filament v5
    public static function getNavigationGroup(): ?string
    {
        return 'Operasional';
    }

    public static function getNavigationLabel(): string
    {
        return 'Arsip Laporan';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document-text';
    }

    /**
     * PBI-139/140: Hanya Admin Akademik yang bisa mengakses arsip laporan.
     */
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role?->nama_role === 'Admin Akademik';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role?->nama_role === 'Admin Akademik';
    }

    public static function form(Schema $schema): Schema
    {
        return LaporanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLaporans::route('/'),
            'create' => CreateLaporan::route('/create'),
            'edit'   => EditLaporan::route('/{record}/edit'),
        ];
    }
}
