<?php

namespace App\Filament\Resources\Laporans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LaporansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul Laporan')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(60),

                TextColumn::make('tipe_laporan')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'laporan_kelulusan'  => 'success',
                        'laporan_keuangan'   => 'warning',
                        'laporan_akademik'   => 'info',
                        'laporan_instruktur' => 'primary',
                        'laporan_bulanan'    => 'gray',
                        'laporan_tahunan'    => 'danger',
                        default              => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'laporan_kelulusan'  => 'Kelulusan',
                        'laporan_keuangan'   => 'Keuangan',
                        'laporan_akademik'   => 'Akademik',
                        'laporan_instruktur' => 'Instruktur',
                        'laporan_bulanan'    => 'Bulanan',
                        'laporan_tahunan'    => 'Tahunan',
                        default              => $state,
                    }),

                TextColumn::make('periode')
                    ->label('Periode')
                    ->sortable(),

                TextColumn::make('pembuat.nama_lengkap')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->default('-'),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tipe_laporan')
                    ->label('Filter Tipe')
                    ->options([
                        'laporan_kelulusan'  => 'Kelulusan',
                        'laporan_keuangan'   => 'Keuangan',
                        'laporan_akademik'   => 'Akademik',
                        'laporan_instruktur' => 'Instruktur',
                        'laporan_bulanan'    => 'Bulanan',
                        'laporan_tahunan'    => 'Tahunan',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
