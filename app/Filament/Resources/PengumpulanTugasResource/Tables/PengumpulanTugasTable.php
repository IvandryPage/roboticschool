<?php

namespace App\Filament\Resources\PengumpulanTugasResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PengumpulanTugasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tugas.judul_tugas')
                    ->label('Nama Tugas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tugas.batas_waktu')
                    ->label('Batas Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('file_jawaban')
                    ->label('Status Pengumpulan')
                    ->formatStateUsing(fn ($state) =>
                        $state ? 'Sudah Dikumpulkan' : 'Belum Dikumpulkan'
                    )
                    ->badge()
                    ->color(fn ($state) =>
                        $state ? 'success' : 'danger'
                    ),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}