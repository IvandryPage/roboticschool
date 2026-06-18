<?php

namespace App\Filament\Resources\PengumpulanTugas;

use App\Models\PengumpulanTugas;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema; // PASTI GUNAKAN INI
use App\Filament\Resources\PengumpulanTugas\Schemas\PengumpulanTugasForm;
use App\Filament\Resources\PengumpulanTugas\Pages\ListPengumpulanTugas;
use App\Filament\Resources\PengumpulanTugas\Pages\CreatePengumpulanTugas;
use App\Filament\Resources\PengumpulanTugas\Pages\EditPengumpulanTugas;

class PengumpulanTugasResource extends Resource
{
    protected static ?string $model = PengumpulanTugas::class;

    // Pastikan ini menggunakan Schema, bukan Form
   public static function form(Schema $schema): Schema
    {
        // Ubah bagian ini agar menarik skema dari file PengumpulanTugasForm yang sudah kamu buat
        return PengumpulanTugasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            // Tambahkan kolom di sini
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPengumpulanTugas::route('/'),
            'create' => CreatePengumpulanTugas::route('/create'),
            'edit' => EditPengumpulanTugas::route('/{record}/edit'),
        ];
    }
}