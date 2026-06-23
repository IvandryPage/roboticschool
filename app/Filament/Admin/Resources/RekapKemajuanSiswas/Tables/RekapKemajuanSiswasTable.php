<?php

namespace App\Filament\Admin\Resources\RekapKemajuanSiswas\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Kehadiran;
use Illuminate\Database\Eloquent\Builder;

class RekapKemajuanSiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Siswa')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('user', function ($q) use ($search) {
                            $q->where('name', 'ilike', "%{$search}%");
                        });
                    })
                    ->sortable(),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->default('Belum Masuk Kelas'),

                TextColumn::make('kehadiran')
                    ->label('Kehadiran')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $total = Kehadiran::where('siswa_id', $record->id)->count();
                        if ($total === 0) return 0;
                        
                        $hadir = Kehadiran::where('siswa_id', $record->id)->where('status_hadir', 'hadir')->count();
                        return round(($hadir / $total) * 100, 1);
                    })
                    ->formatStateUsing(fn ($state): string => $state . '%')
                    ->color(function ($state): string {
                        // Menggunakan floatval() agar sangat aman dari error konversi tipe data
                        $val = floatval($state); 
                        if ($val >= 80) return 'success';
                        if ($val >= 50) return 'warning';
                        return 'danger';
                    }),

                TextColumn::make('rata_rata_nilai')
                    ->label('Rata-rata Nilai')
                    ->default('-'),

                TextColumn::make('tugas_dikumpulkan')
                    ->label('Tugas Dikumpulkan')
                    ->default(0),
            ])
            ->filters([
                // PERBAIKAN: Mengubah 'kelas_id' menjadi 'kelas' agar Filament tidak bingung membaca relasi
                SelectFilter::make('kelas')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama_kelas')
            ]);
    }
}