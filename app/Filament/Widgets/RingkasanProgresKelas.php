<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RingkasanProgresKelas extends BaseWidget
{
    public static function canView(): bool
    {
        return in_array(auth()->user()?->role?->nama_role, ['Admin Akademik', 'Instruktur']);
    }
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Ringkasan Progres Kelas';

    public function table(Table $table): Table
    {
        return $table
            // Kita panggil Kelas biasa tanpa withCount siswa agar tidak crash
            ->query(Kelas::query()) 
            ->columns([
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Kolom Jumlah Siswa
                Tables\Columns\TextColumn::make('jumlah_siswa_aktif')
                    ->label('Jumlah Siswa Aktif')
                    ->getStateUsing(function (Kelas $record) {
                        $count = \App\Models\EnrollmentKelas::where('kelas_id', $record->id)
                            ->where('status', 'Aktif')
                            ->count();
                        return $count . ' Siswa'; 
                    })
                    ->badge()
                    ->color('success'),

                // Kolom Rata-rata Kehadiran
                Tables\Columns\TextColumn::make('rata_kehadiran')
                    ->label('Rata-rata Kehadiran')
                    ->getStateUsing(function (Kelas $record) {
                        $avg = \App\Models\ProgressAkademik::where('kelas_id', $record->id)
                            ->avg('persentase_kehadiran');
                        return $avg ? round($avg, 1) . '%' : '0%';
                    })
                    ->badge()
                    ->color('warning'),
            ])
            ->paginated([5, 10, 25]);
    }
}