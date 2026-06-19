<?php

namespace App\Filament\Resources\PengumpulanTugas;

use App\Models\PengumpulanTugas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas; 
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class PengumpulanTugasResource extends Resource
{
    protected static ?string $model = PengumpulanTugas::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document-text';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('siswa_id', auth()->id()); 
    }

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
                    $state === 'Sudah Dikumpulkan' ? 'success' : 'danger'
                ),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengumpulanTugas::route('/'),
            'create' => Pages\CreatePengumpulanTugas::route('/create'),
            'edit' => Pages\EditPengumpulanTugas::route('/{record}/edit'),
        ];
    }
}