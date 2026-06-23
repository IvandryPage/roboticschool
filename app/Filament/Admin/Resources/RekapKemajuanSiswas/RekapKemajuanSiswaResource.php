<?php

namespace App\Filament\Admin\Resources\RekapKemajuanSiswas;

use App\Filament\Admin\Resources\RekapKemajuanSiswas\Pages\ManageRekapKemajuanSiswas;
use App\Filament\Admin\Resources\RekapKemajuanSiswas\Tables\RekapKemajuanSiswasTable;
use App\Models\EnrollmentKelas; 
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class RekapKemajuanSiswaResource extends Resource
{
    // Menggunakan model EnrollmentKelas agar bisa menampilkan siswa per kelas
    protected static ?string $model = EnrollmentKelas::class; 

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static ?string $navigationLabel = 'Rekap Kemajuan Siswa';
    protected static ?string $pluralLabel = 'Rekap Kemajuan Seluruh Siswa';
    protected static ?string $slug = 'rekap-kemajuan-siswa';

    public static function table(Table $table): Table
    {
        return RekapKemajuanSiswasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRekapKemajuanSiswas::route('/'),
        ];
    }
}