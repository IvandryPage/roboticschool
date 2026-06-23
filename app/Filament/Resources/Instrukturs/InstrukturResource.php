<?php

namespace App\Filament\Resources\Instrukturs;

use App\Filament\Resources\Instrukturs\Pages\CreateInstruktur;
use App\Filament\Resources\Instrukturs\Pages\EditInstruktur;
use App\Filament\Resources\Instrukturs\Pages\ListInstrukturs;
use App\Models\Instruktur;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class InstrukturResource extends Resource
{
    protected static ?string $model = Instruktur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    protected static string|\UnitEnum|null $navigationGroup = 'Administrasi Sistem';

    /** Admin & Direktur dapat melihat data instruktur */
    public static function canViewAny(): bool
    {
        return in_array(Auth::user()?->role?->nama_role, ['Admin Akademik', 'Direktur']);
    }

    /** Hanya Admin yang bisa tambah/edit/hapus instruktur */
    public static function canCreate(): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

   public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('nama_lengkap')
                    ->required()
                    ->maxLength(255),

                \Filament\Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),

                \Filament\Forms\Components\TextInput::make('spesialisasi')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\Instrukturs\Tables\InstruktursTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInstrukturs::route('/'),
            'create' => CreateInstruktur::route('/create'),
            'edit'   => EditInstruktur::route('/{record}/edit'),
        ];
    }
}