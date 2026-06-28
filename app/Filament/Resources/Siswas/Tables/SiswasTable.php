<?php

namespace App\Filament\Resources\Siswas\Tables;

use App\Models\Siswa;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('user.no_hp')
                    ->label('No. HP')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('user.status_aktif')
                    ->label('Aktif')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('persentase_kehadiran')
                    ->label('Kehadiran')
                    ->getStateUsing(function (Siswa $record): string {
                        $total = $record->kehadiran()->count();
                        if ($total === 0) {
                            return '-';
                        }
                        $hadir = $record->kehadiran()->where('status_hadir', 'Hadir')->count();
                        return round(($hadir / $total) * 100, 1) . '%';
                    })
                    ->badge()
                    ->color(function (Siswa $record): string {
                        $total = $record->kehadiran()->count();
                        if ($total === 0) {
                            return 'gray';
                        }
                        $hadir    = $record->kehadiran()->where('status_hadir', 'Hadir')->count();
                        $persen   = ($hadir / $total) * 100;
                        return $persen < 75 ? 'danger' : 'success';
                    }),

                TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('status_aktif')
                    ->label('Status Akun')
                    ->attribute('user.status_aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([]);
    }
}
