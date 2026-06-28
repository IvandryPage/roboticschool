<?php

namespace App\Filament\Resources\Sertifikats;

use App\Filament\Resources\Sertifikats\Pages\ListSertifikats;
use App\Models\Sertifikat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SertifikatResource extends Resource
{
    protected static ?string $model = Sertifikat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Sertifikat';

    protected static ?string $modelLabel = 'Sertifikat';

    protected static ?string $pluralModelLabel = 'Sertifikat';

    protected static ?string $recordTitleAttribute = 'nomor_sertifikat';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 10;

    // ── Access control ─────────────────────────────────────────────

    public static function canViewAny(): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

    /** Penerbitan dilakukan via custom Action di ListPage, bukan form Create standar */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

    // ── Table ───────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_sertifikat')
                    ->label('No. Sertifikat')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('siswa.user.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelas.batch.program.nama_program')
                    ->label('Program')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('tanggal_terbit')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->default('-'),

                TextColumn::make('penerbit.nama_lengkap')
                    ->label('Diterbitkan Oleh')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal_terbit', 'desc')
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama_kelas'),
            ])
            ->recordActions([
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([]);
    }

    // ── Pages ───────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index' => ListSertifikats::route('/'),
        ];
    }
}
