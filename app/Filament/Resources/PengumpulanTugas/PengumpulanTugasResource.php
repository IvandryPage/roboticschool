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
        TextInput::make('nilai')
            ->label('Nilai')
            ->numeric()
            ->disabled(),

        Textarea::make('umpan_balik')
            ->label('Umpan Balik Instruktur')
            ->disabled(),
    ]);
}

    // 4. Tabel tetap menggunakan Tables\Table
    public static function table(Tables\Table $table): Tables\Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('tugas.judul_tugas')
            ->label('Nama Tugas')
            ->searchable(),

        Tables\Columns\TextColumn::make('tugas.batas_waktu')
            ->label('Batas Waktu')
            ->dateTime('d M Y H:i')
            ->sortable(),

        Tables\Columns\TextColumn::make('file_jawaban')
            ->label('Status Pengumpulan')
            ->formatStateUsing(fn ($state) =>
                $state ? 'Sudah Dikumpulkan' : 'Belum Dikumpulkan'
            )
            ->badge()
            ->color(fn ($state) =>
                $state ? 'success' : 'danger'
            ),
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