<?php

namespace App\Filament\Resources\TiketKeluhans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class TiketKeluhanForm
{
    public static function configure(Schema $schema): Schema
    {
        $role      = Auth::user()?->role?->nama_role;
        $isAdmin   = $role === 'Admin Akademik';
        $isCreator = in_array($role, ['Siswa', 'Instruktur']);

        return $schema
            ->components([
                TextInput::make('subjek')
                    ->label('Subjek Keluhan')
                    ->placeholder('Contoh: Tidak bisa mengakses materi modul 3')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->disabled($isAdmin)
                    ->dehydrated(! $isAdmin),

                Select::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'Akademik' => 'Akademik',
                        'Teknis'   => 'Teknis (Sistem/Akses)',
                        'Lainnya'  => 'Lainnya',
                    ])
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->disabled($isAdmin)
                    ->dehydrated(! $isAdmin),

                Select::make('prioritas')
                    ->label('Prioritas')
                    ->options([
                        'Low'    => 'Rendah',
                        'Medium' => 'Sedang',
                        'High'   => 'Tinggi',
                    ])
                    ->default('Medium')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->disabled($isAdmin)
                    ->dehydrated(! $isAdmin),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Keluhan')
                    ->placeholder('Jelaskan masalah yang dihadapi secara detail...')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->disabled($isAdmin)
                    ->dehydrated(! $isAdmin)
                    ->rows(4)
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Status Penanganan')
                    ->options([
                        'Open'        => 'Open',
                        'In Progress' => 'In Progress',
                        'Resolved'    => 'Resolved',
                    ])
                    ->default('Open')
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'create' && $isCreator)
                    ->dehydrated(fn (string $operation): bool => ! ($operation === 'create' && $isCreator))
                    ->visible(fn (string $operation): bool => $isAdmin || $operation === 'create'),
            ]);
    }
}