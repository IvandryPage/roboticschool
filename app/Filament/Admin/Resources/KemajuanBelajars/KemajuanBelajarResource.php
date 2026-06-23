<?php

namespace App\Filament\Admin\Resources\KemajuanBelajars;

use App\Filament\Admin\Resources\KemajuanBelajars\Pages\ManageKemajuanBelajars;
use App\Filament\Admin\Resources\KemajuanBelajars\Tables\KemajuanBelajarsTable;
use App\Models\PengumpulanTugas; // Menggunakan model pengumpulan tugas asli proyek Anda
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class KemajuanBelajarResource extends Resource
{
    protected static ?string $model = PengumpulanTugas::class; 

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Kemajuan Belajar';
    protected static ?string $pluralLabel = 'Kemajuan Belajar Anda';
    protected static ?string $slug = 'kemajuan-belajar';

    public static function table(Table $table): Table
    {
        return KemajuanBelajarsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageKemajuanBelajars::route('/'),
        ];
    }
}