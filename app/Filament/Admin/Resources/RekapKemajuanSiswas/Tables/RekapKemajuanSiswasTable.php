<?php

namespace App\Filament\Admin\Resources\RekapKemajuanSiswas\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Kehadiran;
use App\Models\PengumpulanTugas;

class RekapKemajuanSiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // PERUBAHAN DI SINI: Kita arahkan pencarian ke siswa.user.name
                TextColumn::make('siswa.user.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->default('Data Siswa Tidak Ditemukan'),

                // Nama Kelas
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable()
                    ->default('-'),

                // Kalkulasi Persentase Kehadiran
                TextColumn::make('persentase_kehadiran')
                    ->label('Kehadiran')
                    ->state(function ($record) {
                        $siswaId = $record->siswa_id;
                        $totalHadir = Kehadiran::where('siswa_id', $siswaId)->count();
                        $hadir = Kehadiran::where('siswa_id', $siswaId)->where('status_hadir', 'hadir')->count();
                        return $totalHadir > 0 ? round(($hadir / $totalHadir) * 100, 1) . '%' : '0%';
                    })
                    ->badge()
                    ->color('info'),

                // Kalkulasi Rata-rata Nilai
                TextColumn::make('rata_rata_nilai')
                    ->label('Rata-rata Nilai')
                    ->state(function ($record) {
                        $rata = PengumpulanTugas::where('siswa_id', $record->siswa_id)->avg('nilai');
                        return $rata ? round($rata, 1) : '0';
                    })
                    ->badge()
                    ->color(fn (string $state): string => $state >= 75 ? 'success' : 'warning'),

                // Kalkulasi Jumlah Tugas Terkumpul
                TextColumn::make('jumlah_tugas')
                    ->label('Tugas Dikumpulkan')
                    ->state(function ($record) {
                        $jumlah = PengumpulanTugas::where('siswa_id', $record->siswa_id)->count();
                        return $jumlah . ' Tugas';
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama_kelas')
            ]);
    }
}