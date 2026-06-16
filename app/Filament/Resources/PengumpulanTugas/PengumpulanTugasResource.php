<?php

namespace App\Filament\Resources\PengumpulanTugas;

use App\Models\PengumpulanTugas;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas; // KITA KEMBALI MENGGUNAKAN SCHEMAS
use Filament\Tables;

class PengumpulanTugasResource extends Resource
{
    // 1. Model tetap menggunakan ?string
    protected static ?string $model = PengumpulanTugas::class;

    // 2. Icon menggunakan fungsi agar terhindar dari Fatal Error BackedEnum
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document-text';
    }

    // 3. Form KEMBALI menggunakan Schemas\Schema sesuai permintaan sistem Anda
    public static function form(Schemas\Schema $schema): Schemas\Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('nilai')
                ->label('Nilai')
                ->numeric()
                ->disabled(), // PBI-107: Dashboard siswa hanya melihat nilai
                
            Forms\Components\Textarea::make('umpan_balik')
                ->label('Umpan Balik Instruktur')
                ->disabled(), // PBI-107: Siswa tidak bisa mengubah umpan balik
        ]);
    }

    // 4. Tabel tetap menggunakan Tables\Table
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nilai')
                ->label('Nilai')
                ->badge()
                ->color('success'),
                
            Tables\Columns\TextColumn::make('umpan_balik')
                ->label('Umpan Balik')
                ->wrap(),
        ]);
    }

    // 5. Fungsi getPages wajib ada agar web bisa terbuka
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengumpulanTugas::route('/'),
            'create' => Pages\CreatePengumpulanTugas::route('/create'),
            'edit' => Pages\EditPengumpulanTugas::route('/{record}/edit'),
        ];
    }
}