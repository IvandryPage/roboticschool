<?php

namespace App\Filament\Resources\Siswas\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Nama Siswa'),
                
                TextColumn::make('persentase_kehadiran')
                    ->label('Tingkat Kehadiran')
                    ->getStateUsing(function ($record) {
                        $total = $record->kehadiran()->count();
                        if ($total === 0) return 0;
                        $hadir = $record->kehadiran()->where('status_hadir', 'hadir')->count();
                        return round(($hadir / $total) * 100, 2);
                    })
                    ->suffix('%')
                    ->color(fn ($state): string => (float)$state < 75 ? 'danger' : 'success')
                    ->weight('bold'),
            ]);
    }
}