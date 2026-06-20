<?php

namespace App\Filament\Resources\TiketKeluhans\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TiketKeluhansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pelapor.name')
                    ->label('Pelapor')
                    ->searchable(),

                TextColumn::make('kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pembelajaran' => 'primary',
                        'Error Sistem' => 'danger',
                        'Pendaftaran & Pembayaran' => 'warning',
                        'Hal Lainnya' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('subjek')
                    ->searchable(),

                TextColumn::make('deskripsi')
                    ->label('Detail Keluhan')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->deskripsi)
                    ->searchable(),

                TextColumn::make('prioritas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Rendah' => 'success',
                        'Sedang' => 'warning',
                        'Tinggi' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Open' => 'primary',
                        'In Progress' => 'warning',
                        'Resolved' => 'success',
                        'Closed' => 'gray',
                        default => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}