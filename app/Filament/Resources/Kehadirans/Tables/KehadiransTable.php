<?php

namespace App\Filament\Resources\Kehadirans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KehadiransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sesi.kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sesi.nomor_sesi')
                    ->label('Sesi')
                    ->sortable(),

                TextColumn::make('sesi.tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('siswa.user.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status_hadir')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Izin'  => 'info',
                        'Sakit' => 'warning',
                        'Alpa'  => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('pencatat.name')
                    ->label('Dicatat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sesi.tanggal', 'desc')
            ->filters([
                SelectFilter::make('status_hadir')
                    ->label('Status')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Izin'  => 'Izin',
                        'Sakit' => 'Sakit',
                        'Alpa'  => 'Alpa',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}