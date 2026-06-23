<?php

namespace App\Filament\Resources\Laporans\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LaporanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('judul')
                    ->label('Judul Laporan')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Laporan Kelulusan Batch 1 2026')
                    ->columnSpanFull(),

                Select::make('tipe_laporan')
                    ->label('Tipe Laporan')
                    ->options([
                        'laporan_kelulusan'  => 'Laporan Kelulusan',
                        'laporan_keuangan'   => 'Laporan Keuangan',
                        'laporan_akademik'   => 'Laporan Akademik',
                        'laporan_instruktur' => 'Laporan Instruktur',
                        'laporan_bulanan'    => 'Laporan Bulanan',
                        'laporan_tahunan'    => 'Laporan Tahunan',
                    ])
                    ->required()
                    ->searchable(),

                TextInput::make('periode')
                    ->label('Periode')
                    ->placeholder('Contoh: 2026-01 atau Q1-2026')
                    ->maxLength(50),

                FileUpload::make('file_path')
                    ->label('File Laporan (opsional)')
                    ->directory('arsip-laporan')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->maxSize(10240)
                    ->nullable()
                    ->columnSpanFull(),

                Textarea::make('catatan')
                    ->label('Catatan')
                    ->rows(3)
                    ->placeholder('Catatan tambahan mengenai laporan ini...')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
