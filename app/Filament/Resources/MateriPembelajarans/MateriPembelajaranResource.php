<?php

namespace App\Filament\Resources\MateriPembelajarans;

use App\Models\MateriPembelajaran;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; // UBAH INI: Dari Form ke Schema
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon; // Tambahkan untuk ikon
use BackedEnum; // Tambahkan untuk kompatibilitas PHP 8.4

// Memanggil class form materi
use App\Filament\Resources\MateriPembelajarans\Schemas\MateriPembelajaranForm;

// Memanggil Pages
use App\Filament\Resources\MateriPembelajarans\Pages;

class MateriPembelajaranResource extends Resource
{
    protected static ?string $model = MateriPembelajaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $slug = 'materi-pembelajarans';

    // UBAH INI: Method form sekarang pakai tipe Schema
    public static function form(Schema $schema): Schema
    {
        return MateriPembelajaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('judul')
                    ->label('Judul Materi')
                    ->searchable(),
                TextColumn::make('tipe_konten')
                    ->label('Tipe')
                    ->badge(),
            ])
            ->actions([
                // Silakan isi action nanti, ini dibiarkan dulu agar tabel muncul
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMateriPembelajarans::route('/'),
            'create' => Pages\CreateMateriPembelajaran::route('/create'),
            'edit' => Pages\EditMateriPembelajaran::route('/{record}/edit'),
        ];
    }
}