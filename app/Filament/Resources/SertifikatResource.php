<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SertifikatResource\Pages;
use App\Models\Sertifikat;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables;

class SertifikatResource extends Resource
{
    protected static ?string $model = Sertifikat::class;
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return 'Daftar Sertifikat';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document-check';
    }

    /**
     * PBI-129: Hanya Admin Akademik yang bisa mengakses daftar sertifikat.
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
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_sertifikat')
                    ->label('Nomor Sertifikat')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('siswa.user.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),

                // FIX ERROR 7: path relasi yang benar melewati batch -> program
                TextColumn::make('kelas.batch.program.nama_program')
                    ->label('Program')
                    ->searchable(),

                TextColumn::make('tanggal_terbit')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('penerbit.nama_lengkap')
                    ->label('Diterbitkan Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->label('Filter Kelas'),
            ])
            ->actions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Sertifikat $record) => route('sertifikat.verifikasi', $record->nomor_sertifikat))
                    ->openUrlInNewTab(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal_terbit', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSertifikat::route('/'),
        ];
    }
}
