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
        return 'Sertifikat';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
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
                TextColumn::make('siswa.user.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('kelas.programKursus.nama_program')
                    ->label('Program')
                    ->searchable(),
                TextColumn::make('tanggal_terbit')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y')
                    ->sortable(),
                TextColumn::make('penerbit.name')
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
                    ->url(fn(Sertifikat $record) => route('sertifikat.verifikasi', $record->nomor_sertifikat))
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
