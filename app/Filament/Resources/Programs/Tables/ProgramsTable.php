<?php

namespace App\Filament\Resources\Programs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

class ProgramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_program')
                    ->label('Nama Program')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('level')
                    ->label('Level')
                    ->searchable(),

                TextColumn::make('biaya')
                    ->label('Biaya (Rp)')
                    ->numeric() // Otomatis menambahkan pemisah ribuan
                    ->sortable(),

                TextColumn::make('durasi_minggu')
                    ->label('Durasi')
                    ->suffix(' Minggu') // Menambahkan teks "Minggu" di belakang angka
                    ->sortable(),

                ImageColumn::make('gambar')
                    ->label('Poster')
                    ->square(), // Menampilkan gambar dalam bentuk kotak

                IconColumn::make('status_tampil')
                    ->label('Ditampilkan?')
                    ->boolean(), // Otomatis menampilkan ikon centang hijau atau silang merah
            ])
            ->filters([
                // Tempat untuk menambahkan filter nanti
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