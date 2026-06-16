<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengumpulanTugasResource\Pages;
use App\Models\PengumpulanTugas;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class PengumpulanTugasResource extends Resource
{
    protected static ?string $model = PengumpulanTugas::class;

    // ... (kode lainnya)

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tugas.nama_tugas')
                    ->label('Nama Tugas')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status_penilaian')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Belum Dinilai',
                        'success' => 'Dinilai',
                    ]),

                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->suffix('/100')
                    ->sortable()
                    ->color(fn ($state): string => $state >= 75 ? 'success' : 'danger'),

                TextColumn::make('umpan_balik')
                    ->label('Umpan Balik')
                    ->limit(40)
                    ->tooltip(fn ($record): ?string => $record->umpan_balik),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // PBI-107: Keamanan Data
        // Memastikan siswa hanya melihat pengumpulan tugas milik mereka sendiri
        // Jika user adalah admin, biarkan mereka melihat semuanya.
        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('instruktur')) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()->where('siswa_id', auth()->id());
    }
}