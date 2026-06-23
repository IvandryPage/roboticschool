<?php

namespace App\Filament\Resources\AuditLogs\Tables;

use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.nama_lengkap')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('aksi')
                    ->label('Aksi')
                    ->badge()
                    ->searchable(),

                TextColumn::make('entity_type')
                    ->label('Entitas')
                    ->searchable(),

                TextColumn::make('entity_id')
                    ->label('ID Entitas')
                    ->limit(16)
                    ->tooltip(fn ($record) => $record->entity_id),

                TextColumn::make('ip_address')
                    ->label('IP Address'),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Pengguna')
                    ->options(fn () => User::orderBy('nama_lengkap')->pluck('nama_lengkap', 'id'))
                    ->searchable()
                    ->native(false)
                    ->placeholder('Semua Pengguna'),

                SelectFilter::make('aksi')
                    ->label('Aksi')
                    ->options([
                        'Login'       => 'Login',
                        'Delete Data' => 'Delete Data',
                        'Verifikasi'  => 'Verifikasi',
                    ])
                    ->native(false)
                    ->placeholder('Semua Aksi'),
            ])
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->recordActions([])
            ->toolbarActions([]);
    }
}
