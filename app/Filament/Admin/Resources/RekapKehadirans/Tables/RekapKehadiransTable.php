<?php

namespace App\Filament\Admin\Resources\RekapKehadirans\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Kehadiran;
use Illuminate\Database\Eloquent\Builder;

class RekapKehadiransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Menampilkan Nama Siswa
                TextColumn::make('user.name')
                    ->label('Nama Siswa')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('user', function ($q) use ($search) {
                            $q->where('name', 'ilike', "%{$search}%");
                        });
                    })
                    ->sortable(),

                // 2. Menampilkan Kelas Siswa
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('kelas', function ($q) use ($search) {
                            $q->where('nama_kelas', 'ilike', "%{$search}%");
                        });
                    })
                    ->default('Belum Masuk Kelas'),

                // 3. Menghitung total seluruh sesi
                TextColumn::make('total_sesi')
                    ->label('Total Sesi')
                    ->getStateUsing(function ($record) {
                        return Kehadiran::where('siswa_id', $record->id)->count();
                    }),

                // 4. Menghitung berapa kali siswa 'hadir'
                TextColumn::make('total_hadir')
                    ->label('Hadir')
                    ->badge()
                    ->color('success')
                    ->getStateUsing(function ($record) {
                        return Kehadiran::where('siswa_id', $record->id)
                            ->where('status_hadir', 'hadir')
                            ->count();
                    }),

                // 5. FITUR UTAMA PBI 115: Menghitung Persentase Kehadiran Total (Format Aman)
                TextColumn::make('persentase_kehadiran')
                    ->label('Persentase Kehadiran')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $total = Kehadiran::where('siswa_id', $record->id)->count();
                        $hadir = Kehadiran::where('siswa_id', $record->id)->where('status_hadir', 'hadir')->count();

                        if ($total === 0) {
                            return 0; // Kembalikan angka murni terlebih dahulu
                        }

                        return round(($hadir / $total) * 100, 1); // Kembalikan angka murni float
                    })
                    ->formatStateUsing(fn ($state): string => $state . '%') // Tambah tanda % di sini khusus untuk tampilan
                    ->color(fn ($state): string => match (true) {
                        (float)$state >= 80 => 'success',
                        (float)$state >= 50 => 'warning',
                        default => 'danger',
                    }),
            ])
            ->filters([]);
    }
}