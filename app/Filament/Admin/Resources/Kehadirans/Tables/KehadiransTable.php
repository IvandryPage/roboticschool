<?php

namespace App\Filament\Admin\Resources\Kehadirans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KehadiransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Mengubah siswa.id menjadi nama asli siswa lewat relasi user
                TextColumn::make('siswa.user.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                // 2. Mengubah tampilan status menjadi badge warna-warni agar scannable
                TextColumn::make('status_hadir')
                    ->label('Status Kehadiran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success', // Hijau
                        'izin' => 'info',     // Biru
                        'sakit' => 'warning',  // Kuning
                        'alpa' => 'danger',    // Merah
                        default => 'secondary',
                    })
                    ->searchable(),

                // 3. Menampilkan waktu pencatatan absensi
                TextColumn::make('waktu_pencatatan')
                    ->label('Waktu Absensi')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                // Kolom opsional penanda sistem (disembunyikan secara default)
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
                EditAction::make(), // Tombol utama pemicu Edit PBI 8 Anda!
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}