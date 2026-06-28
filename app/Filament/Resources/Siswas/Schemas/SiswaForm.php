<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Akun Pengguna')
                    ->description('Data ini tersimpan di tabel users dan akan diperbarui otomatis.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->dehydrated(false)
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record?->user) {
                                    $component->state($record->user->nama_lengkap ?? $record->user->name);
                                }
                            }),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique('users', 'email', ignoreRecord: false)
                            ->dehydrated(false)
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record?->user) {
                                    $component->state($record->user->email);
                                }
                            }),

                        TextInput::make('no_hp')
                            ->label('No. HP')
                            ->tel()
                            ->maxLength(20)
                            ->dehydrated(false)
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record?->user) {
                                    $component->state($record->user->no_hp);
                                }
                            }),

                        Toggle::make('status_aktif')
                            ->label('Akun Aktif')
                            ->default(true)
                            ->dehydrated(false)
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record?->user) {
                                    $component->state($record->user->status_aktif);
                                }
                            }),
                    ]),

                Section::make('Data Pribadi Siswa')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->nullable(),

                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-laki'  => 'Laki-laki',
                                'Perempuan'  => 'Perempuan',
                            ])
                            ->nullable(),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull()
                            ->nullable(),
                    ]),
            ]);
    }
}
