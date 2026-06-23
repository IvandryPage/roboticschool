<?php

namespace App\Filament\Resources\Batches\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Kita gunakan relationship agar data program otomatis muncul di dropdown
                Select::make('program_id')
                    ->label('Program')
                    ->relationship('program', 'nama_program') // Menarik nama dari program_kursus
                    ->required() // Karena database lu wajib diisi (NOT NULL)
                    ->searchable() // Biar bisa dicari kalau nanti datanya banyak
                    ->preload(), // Biar list langsung muncul saat diklik

                TextInput::make('nama_batch')
                    ->label('Nama Batch')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai'),

                DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai'),

                TextInput::make('kuota_max')
                    ->label('Kuota Max')
                    ->numeric(),

                Toggle::make('status_aktif')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }
}