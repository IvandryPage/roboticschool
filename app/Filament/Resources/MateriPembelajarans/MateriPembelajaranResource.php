<?php

namespace App\Filament\Resources\MateriPembelajarans;

use App\Models\MateriPembelajaran;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Illuminate\Support\Facades\Auth;

// Memanggil class form materi
use App\Filament\Resources\MateriPembelajarans\Schemas\MateriPembelajaranForm;

// Memanggil Pages
use App\Filament\Resources\MateriPembelajarans\Pages;

class MateriPembelajaranResource extends Resource
{
    protected static ?string $model = MateriPembelajaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $slug = 'materi-pembelajarans';

    protected static ?string $navigationLabel = 'Materi Pembelajaran';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    /** Admin dan Instruktur dapat melihat materi */
    public static function canViewAny(): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Admin Akademik', 'Instruktur']);
    }

    /** Instruktur yang bisa tambah/edit/hapus materi */
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

    // UBAH INI: Method form sekarang pakai tipe Schema
    public static function form(Schema $schema): Schema
    {
        return MateriPembelajaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('judul')
                    ->label('Judul Materi')
                    ->searchable(),
                TextColumn::make('tipe_konten')
                    ->label('Tipe')
                    ->badge(),
            ])
            ->recordActions([
                // Silakan isi action nanti, ini dibiarkan dulu agar tabel muncul
            ]);
    }

    /** Instruktur hanya lihat materi sesi kelas miliknya */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $q = parent::getEloquentQuery();
        if (\Illuminate\Support\Facades\Auth::user()?->role?->nama_role === 'Instruktur') {
            $kIds = \App\Models\Kelas::where('instruktur_id', \Illuminate\Support\Facades\Auth::id())->pluck('id');
            $sIds = \App\Models\SesiLive::whereIn('kelas_id', $kIds)->pluck('id');
            return $q->whereIn('sesi_id', $sIds);
        }
        return $q;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMateriPembelajarans::route('/'),
            'create' => Pages\CreateMateriPembelajaran::route('/create'),
            'edit' => Pages\EditMateriPembelajaran::route('/{record}/edit'),
        ];
    }
}