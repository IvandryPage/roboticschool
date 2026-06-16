<?php

namespace App\Filament\Admin\Resources\RekapKehadirans\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Kehadiran;

class RekapKehadiransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Menampilkan Nama Siswa (Melalui relasi ke tabel user)
                TextColumn::make('user.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                // 2. Menampilkan Kelas Siswa (Melalui relasi ke tabel kelas)
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->default('Belum Masuk Kelas'),

                // 3. Menghitung total seluruh sesi yang pernah dicatat untuk siswa ini
                TextColumn::make('total_sesi')
                    ->label('Total Sesi')
                    ->getStateUsing(function ($record) {
                        return Kehadiran::where('siswa_id', $record->id)->count();
                    }),

                // 4. Menghitung berapa kali siswa berstatus 'hadir'
                TextColumn::make('total_hadir')
                    ->label('Hadir')
                    ->badge()
                    ->color('success')
                    ->getStateUsing(function ($record) {
                        return Kehadiran::where('siswa_id', $record->id)
                            ->where('status_hadir', 'hadir')
                            ->count();
                    }),

                // 5. FITUR UTAMA PBI 115: Menghitung Persentase Kehadiran Total
                TextColumn::make('persentase_kehadiran')
                    ->label('Persentase Kehadiran')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (float)$state >= 80 => 'success', // Hijau jika rajin (>= 80%)
                        (float)$state >= 50 => 'warning', // Kuning jika mulai jarang masuk
                        default => 'danger',              // Merah jika kritis
                    })
                    ->getStateUsing(function ($record) {
                        $total = Kehadiran::where('siswa_id', $record->id)->count();
                        $hadir = Kehadiran::where('siswa_id', $record->id)->where('status_hadir', 'hadir')->count();

                        if ($total === 0) {
                            return '0%';
                        }

                        // Rumus matematika persentase total kehadiran
                        $persentase = round(($hadir / $total) * 100, 1);
                        return $persentase . '%';
                    }),
            ])
            ->filters([]);
    }
}