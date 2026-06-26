<?php

namespace App\Filament\Resources\Kehadirans;

use App\Filament\Resources\Kehadirans\Pages\CreateKehadiran;
use App\Filament\Resources\Kehadirans\Pages\EditKehadiran;
use App\Filament\Resources\Kehadirans\Pages\ListKehadirans;
use App\Filament\Resources\Kehadirans\Tables\KehadiransTable;
use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\SesiLive;
use App\Models\Siswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class KehadiranResource extends Resource
{
    protected static ?string $model = Kehadiran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Absensi';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    public static function canViewAny(): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Admin Akademik', 'Instruktur']);
    }

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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()?->role?->nama_role === 'Instruktur') {
            $kelasIds = \App\Models\Kelas::where('instruktur_id', Auth::id())->pluck('id');
            $sesiIds  = \App\Models\SesiLive::whereIn('kelas_id', $kelasIds)->pluck('id');
            return $query->whereIn('sesi_id', $sesiIds);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sesi_id')
                    ->label('Sesi')
                    ->relationship('sesi', 'nomor_sesi')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('siswa_id')
                    ->label('Siswa')
                    ->options(
                        Siswa::with('user')->get()->pluck('nama', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('status_hadir')
                    ->label('Status Kehadiran')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Izin'  => 'Izin',
                        'Sakit' => 'Sakit',
                        'Alpa'  => 'Tidak Hadir / Alpa',
                    ])
                    ->default('Hadir')
                    ->required(),

                TextInput::make('catatan')
                    ->label('Catatan')
                    ->placeholder('Alasan izin/sakit atau catatan tambahan...')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return KehadiransTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListKehadirans::route('/'),
            'create' => CreateKehadiran::route('/create'),
            'edit'   => EditKehadiran::route('/{record}/edit'),
        ];
    }
}