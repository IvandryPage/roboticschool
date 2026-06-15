<?php

namespace App\Filament\Resources\PengumpulanTugas\Tables;

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
                TextColumn::make('id')
                    ->label('ID Tugas')
                    ->label('ID'),
                TextColumn::make('tugas.id')
                    ->label('ID Siswa')
                    ->searchable(),
                TextColumn::make('siswa.id')
                    ->searchable(),
                TextColumn::make('file_jawaban')
                    ->searchable(),
                TextColumn::make('waktu_kumpul')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('nilai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status_penilaian')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
