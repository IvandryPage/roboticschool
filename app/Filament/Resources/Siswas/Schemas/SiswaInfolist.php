<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SiswaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.nama_lengkap')
                    ->label('Nama Lengkap'),

                TextEntry::make('user.email')
                    ->label('Email'),

                TextEntry::make('user.no_hp')
                    ->label('No. HP')
                    ->placeholder('-'),

                TextEntry::make('user.role.nama_role')
                    ->label('Role')
                    ->badge()
                    ->color('primary'),

                TextEntry::make('user.status_aktif')
                    ->label('Status Akun')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Nonaktif')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),

                TextEntry::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d M Y')
                    ->placeholder('-'),

                TextEntry::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->placeholder('-'),

                TextEntry::make('alamat')
                    ->label('Alamat')
                    ->placeholder('-'),

                TextEntry::make('pendaftaran.no_referensi')
                    ->label('No. Referensi Pendaftaran')
                    ->placeholder('-')
                    ->copyable(),

                TextEntry::make('created_at')
                    ->label('Bergabung Sejak')
                    ->dateTime('d M Y'),
            ]);
    }
}
