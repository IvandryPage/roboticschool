<?php

namespace App\Filament\Resources\MateriPembelajarans\Tables;

// KITA KEMBALIKAN KE JALUR ASLI MILIK ANDA AGAR TIDAK ERROR
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MateriPembelajaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sesi_id')
                    ->label('ID Sesi')
                    ->sortable(),
                    
                TextColumn::make('judul')
                    ->label('Judul Materi')
                    ->searchable(),
                    
                TextColumn::make('tipe_konten')
                    ->label('Tipe')
                    ->badge() 
                    ->color(fn (string $state): string => match ($state) {
                        'pdf' => 'danger',
                        'video' => 'info',
                        'link' => 'warning',
                        default => 'gray',
                    }),
                    
                // Kolom Cerdas PBI-108
                TextColumn::make('file_path_atau_url')
                    ->label('Akses Materi')
                    ->formatStateUsing(fn ($record) => $record->tipe_konten === 'link' ? 'Buka Tautan' : 'Unduh / Lihat File')
                    ->url(fn ($record) => $record->tipe_konten === 'link' ? $record->file_path_atau_url : asset('storage/' . $record->file_path_atau_url))
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->icon('heroicon-o-arrow-top-right-on-square'),
                    
                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // Menggunakan ViewAction asli bawaan Anda
                ViewAction::make(),
                
                // PBI-108: Tombol Edit HANYA muncul jika bukan siswa
                EditAction::make()
                    ->visible(fn () => auth()->check() && auth()->user()->role !== 'siswa'), 
                
                // PBI-108: Tombol Hapus HANYA muncul jika bukan siswa
                DeleteAction::make()
                    ->visible(fn () => auth()->check() && auth()->user()->role !== 'siswa'), 
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->check() && auth()->user()->role !== 'siswa'),
                ]),
            ]);
    }
}