<?php

namespace App\Filament\Admin\Resources\RekapKehadirans;

use App\Filament\Admin\Resources\RekapKehadirans\Pages\ManageRekapKehadirans;
use App\Filament\Admin\Resources\RekapKehadirans\Tables\RekapKehadiransTable;
use App\Models\Siswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class RekapKehadiranResource extends Resource
{
    protected static ?string $model = Siswa::class; 

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    protected static ?string $navigationLabel = 'Rekap Kehadiran';
    protected static ?string $pluralLabel = 'Rekap Kehadiran Siswa';
    protected static ?string $slug = 'rekap-kehadiran';

    public static function table(Table $table): Table
    {
        
        return RekapKehadiransTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRekapKehadirans::route('/'),
        ];
    }
}