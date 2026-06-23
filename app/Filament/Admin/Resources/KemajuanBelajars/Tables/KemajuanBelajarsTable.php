<?php

namespace App\Filament\Admin\Resources\KemajuanBelajars\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KemajuanBelajarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('siswa_id', Auth::user()->siswa?->id))
            ->columns([
                // Mengasumsikan ada relasi 'tugas' di model PengumpulanTugas untuk mengambil judul
                TextColumn::make('tugas.judul_tugas')
                    ->label('Nama Tugas')
                    ->searchable()
                    ->default('Tugas Tanpa Nama'),

                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    })
                    ->sortable()
                    ->default('Belum Dinilai'),

                TextColumn::make('created_at') // Biasanya menggunakan tanggal dibuatnya record pengumpulan
                    ->label('Tanggal Dikirim')
                    ->date('d M Y')
                    ->sortable(),
                    
                TextColumn::make('catatan_guru')
                    ->label('Catatan Guru')
                    ->default('-'),
            ])
            ->filters([]);
    }
}