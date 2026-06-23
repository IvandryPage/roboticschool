<?php

namespace App\Filament\Resources\Pembayarans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Schemas\Schema;

class PembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Invoice & Pembayaran')
                    ->columns(2)
                    ->schema([
                        Select::make('invoice_id')
                            ->relationship('invoice', 'no_invoice')
                            ->required()
                            ->label('Nomor Invoice'),
                        TextInput::make('nominal')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->label('Nominal Bayar'),
                        TextInput::make('metode_pembayaran')
                            ->placeholder('e.g. Transfer Bank, E-Wallet')
                            ->label('Metode Pembayaran'),
                        TextInput::make('status')
                            ->disabled()
                            ->label('Status'),
                        DateTimePicker::make('paid_at')
                            ->label('Waktu Bayar'),
                    ]),

                Section::make('Verifikasi Admin')
                    ->columns(2)
                    ->schema([
                        Select::make('diverifikasi_oleh')
                            ->relationship('verifikator', 'nama_lengkap')
                            ->disabled()
                            ->label('Diverifikasi Oleh'),
                        Textarea::make('catatan_penolakan')
                            ->label('Catatan Penolakan')
                            ->placeholder('Alasan jika pembayaran ditolak...')
                            ->columnSpanFull(),
                    ]),

                Section::make('Lampiran Bukti')
                    ->schema([
                        FileUpload::make('bukti_file')
                            ->label('File Bukti Pembayaran')
                            ->image()
                            ->directory('bukti_pembayaran')
                            ->columnSpanFull()
                            ->openable()
                            ->downloadable(),
                    ]),
            ]);
    }
}
