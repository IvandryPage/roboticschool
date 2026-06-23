<?php

namespace App\Filament\Admin\Resources\Kehadirans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use App\Models\Siswa;
use App\Models\SesiLive;

class KehadiranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. Pilih Siswa (Menggunakan mapWithKeys & toArray agar Filament tidak crash)
                Select::make('siswa_id')
                    ->label('Nama Siswa')
                    ->options(Siswa::with('user')->get()->mapWithKeys(function ($siswa) {
                        return [$siswa->id => $siswa->user?->name ?? 'Tanpa Nama'];
                    })->toArray())
                    ->searchable()
                    ->disabledOn('edit')
                    ->required(),

                // 2. Pilih Sesi Pertemuan (Wajib ada agar database psql tidak menolak)
                Select::make('sesi_id')
                    ->label('ID Sesi Pertemuan / Live')
                    ->options(SesiLive::pluck('id', 'id')->toArray())
                    ->searchable()
                    ->required(),

                // 3. FITUR UTAMA PBI 8: Memperbaiki status kehadiran
                Select::make('status_hadir') 
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpa' => 'Alpa',
                    ])
                    ->required(),

                // 4. Waktu Pencatatan
                DateTimePicker::make('waktu_pencatatan')
                    ->label('Waktu Pencatatan')
                    ->default(now())
                    ->required(),
                    
                // 5. Catatan opsional
                Textarea::make('catatan')
                    ->label('Catatan / Alasan')
                    ->columnSpanFull(),

                // 6. Otomatis menangkap ID Admin yang login untuk mengisi kolom 'dicatat_oleh'
                Hidden::make('dicatat_oleh')
                    ->default(auth()->id()),
            ]);
    }
}