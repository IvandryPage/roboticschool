<?php

namespace App\Filament\Resources\AuditLogs\Tables;

use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // Direktur hanya lihat log bisnis — Admin lihat semua
                if (Auth::user()?->role?->nama_role === 'Direktur') {
                    $query->where('tipe', 'bisnis');
                }
                return $query;
            })
            ->columns([
                TextColumn::make('user.nama_lengkap')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('aksi')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Login'              => 'primary',
                        'Delete Data'        => 'danger',
                        'Update / Verifikasi'=> 'warning',
                        default              => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bisnis' => 'success',
                        'teknis' => 'gray',
                        default  => 'gray',
                    })
                    // Direktur tidak perlu lihat kolom tipe karena sudah difilter
                    ->visible(fn () => Auth::user()?->role?->nama_role === 'Admin Akademik'),

                TextColumn::make('entity_type')
                    ->label('Entitas')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->searchable(),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->toggleable(isToggledHiddenByDefault: true),

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
                        'Login'               => 'Login',
                        'Delete Data'         => 'Delete Data',
                        'Update / Verifikasi' => 'Update / Verifikasi',
                    ])
                    ->native(false)
                    ->placeholder('Semua Aksi'),

                // Filter tipe hanya tampil untuk Admin
                SelectFilter::make('tipe')
                    ->label('Tipe Log')
                    ->options([
                        'bisnis' => 'Bisnis',
                        'teknis' => 'Teknis',
                    ])
                    ->native(false)
                    ->placeholder('Semua Tipe')
                    ->visible(fn () => Auth::user()?->role?->nama_role === 'Admin Akademik'),
            ])
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->recordActions([])
            ->toolbarActions([]);
    }
}
