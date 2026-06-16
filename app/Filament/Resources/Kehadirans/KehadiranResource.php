<?php

namespace App\Filament\Resources\Kehadirans;

use App\Filament\Resources\Kehadirans\Pages\CreateKehadiran;
use App\Filament\Resources\Kehadirans\Pages\EditKehadiran;
use App\Filament\Resources\Kehadirans\Pages\ListKehadirans;
use App\Filament\Resources\Kehadirans\Tables\KehadiransTable;
use App\Models\Kehadiran;
use App\Models\Siswa; // <-- Tambahan import Siswa
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class KehadiranResource extends Resource
{
    protected static ?string $model = Kehadiran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Pilihan Sesi Kelas
                Select::make('sesi_id')
                    ->label('Sesi')
                    ->relationship('sesi', 'nomor_sesi') 
                    ->searchable()
                    ->preload()
                    ->required(),

                // Pilihan Siswa (Menggunakan options array agar bisa membaca accessor getNamaAttribute)
                Select::make('siswa_id')
                    ->label('Siswa')
                    ->options(
                        Siswa::with('user')->get()->pluck('nama', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                // Pilihan Status Kehadiran
                Select::make('status_hadir')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'alpa' => 'Tidak Hadir / Alpa',
                    ])
                    ->required(),

                // Input Catatan Tambahan
                TextInput::make('catatan')
                    ->placeholder('Tulis alasan atau catatan jika ada...')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return KehadiransTable::configure($table);
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
            'index' => ListKehadirans::route('/'),
            'create' => CreateKehadiran::route('/create'),
            'edit' => EditKehadiran::route('/{record}/edit'),
        ];
    }
}