<?php

namespace App\Filament\Resources\TiketKeluhans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
                    ->searchable(),

                TextColumn::make('subjek')
                    ->searchable(),

                TextColumn::make('prioritas')
                    ->badge()
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}