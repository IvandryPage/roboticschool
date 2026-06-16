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
                // 1. Nama Siswa (Menggunakan relasi siswa)
                TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                // 2. Judul Tugas (Mengambil dari relasi tugas)
                TextColumn::make('tugas.judul')
                    ->label('Tugas')
                    ->searchable()
                    ->sortable(),

                // 3. File Jawaban
                TextColumn::make('file_jawaban')
                    ->label('File Jawaban')
                    ->searchable(),

                // 4. Waktu Kumpul
                TextColumn::make('waktu_kumpul')
                    ->label('Waktu Pengumpulan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                // 5. Nilai
                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->numeric()
                    ->sortable(),

                // 6. Status Penilaian
                TextColumn::make('status_penilaian')
                    ->label('Status Penilaian')
                    ->searchable(),

                // 7. Waktu Dibuat (Hidden by default)
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 8. Waktu Diperbarui (Hidden by default)
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
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
