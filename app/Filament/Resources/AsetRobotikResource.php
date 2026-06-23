<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsetRobotikResource\Pages;
use App\Models\AsetRobotik;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class AsetRobotikResource extends Resource
{
    protected static ?string $model = AsetRobotik::class;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Kelola Aset';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Operasional';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-wrench';
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->role?->nama_role === 'Admin Akademik';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama_kit')
                ->label('Nama Kit')
                ->required()
                ->maxLength(255),
            TextInput::make('kategori')
                ->label('Kategori')
                ->default('Lainnya')
                ->required(),
            TextInput::make('stok_minimal')
                ->label('Stok Minimal')
                ->numeric()
                ->default(1)
                ->required(),
            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->nullable()
                ->columnSpanFull(),

            // Temporary fields for initial stock generation (only on Create)
            TextInput::make('jumlah_stok')
                ->label('Jumlah Stok Awal')
                ->numeric()
                ->default(0)
                ->dehydrated(false)
                ->visible(fn ($livewire) => $livewire instanceof Pages\CreateAsetRobotik),
            Select::make('kondisi')
                ->label('Kondisi Stok Awal')
                ->options([
                    'Bagus' => 'Bagus',
                    'Rusak' => 'Rusak',
                    'Perbaikan' => 'Perbaikan',
                ])
                ->default('Bagus')
                ->dehydrated(false)
                ->visible(fn ($livewire) => $livewire instanceof Pages\CreateAsetRobotik),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_aset')
                    ->label('Kode Aset')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_kit')
                    ->label('Nama Kit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->searchable(),
                TextColumn::make('itemKits_count')
                    ->counts('itemKits')
                    ->label('Total Stok')
                    ->sortable(),
                TextColumn::make('available_stock')
                    ->label('Stok Tersedia')
                    ->getStateUsing(fn (AsetRobotik $record) => $record->available_stock)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (AsetRobotik $record) {
                        // Delete child item kits
                        $record->itemKits()->delete();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AsetRobotikResource\RelationManagers\ItemKitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsetRobotiks::route('/'),
            'create' => Pages\CreateAsetRobotik::route('/create'),
            'edit' => Pages\EditAsetRobotik::route('/{record}/edit'),
        ];
    }
}
