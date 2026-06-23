<?php

namespace App\Filament\Admin\Resources\Kelas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('batch.nama_batch')
                    ->label('Batch')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_kelas')
                    ->searchable(),
                TextColumn::make('instruktur.nama_lengkap')
                    ->label('Instruktur')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kapasitas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable()
                    ->badge(),
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
                \Filament\Tables\Filters\SelectFilter::make('batch_id')
                    ->relationship('batch', 'nama_batch')
                    ->label('Batch'),
                \Filament\Tables\Filters\SelectFilter::make('instruktur_id')
                    ->relationship('instruktur', 'nama_lengkap', fn ($query) => $query->whereHas('role', fn ($q) => $q->where('nama_role', 'Instruktur')))
                    ->label('Instruktur'),
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
