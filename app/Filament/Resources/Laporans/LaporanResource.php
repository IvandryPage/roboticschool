<?php

namespace App\Filament\Resources\Laporans;

use App\Filament\Resources\Laporans\Pages\CreateLaporan;
use App\Filament\Resources\Laporans\Pages\EditLaporan;
use App\Filament\Resources\Laporans\Pages\ListLaporans;
use App\Models\ArsipLaporan;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class LaporanResource extends Resource
{
    protected static ?string $model = ArsipLaporan::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Arsip Laporan';

    protected static ?string $modelLabel = 'Arsip Laporan';

    protected static ?string $pluralModelLabel = 'Arsip Laporan';

    protected static \UnitEnum|string|null $navigationGroup = 'Laporan';

    public static function canCreate(): bool
    {
        return auth()->user()?->role?->nama_role === 'Admin Akademik';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role?->nama_role === 'Admin Akademik';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role?->nama_role === 'Admin Akademik';
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()->role?->nama_role, ['Admin Akademik', 'Instruktur']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('judul')
                ->label('Judul Laporan')
                ->required()
                ->maxLength(255),
            Select::make('tipe_laporan')
                ->label('Tipe Laporan')
                ->options([
                    'laporan_kelulusan' => 'Kelulusan',
                    'laporan_keuangan' => 'Keuangan',
                    'laporan_akademik' => 'Akademik',
                    'laporan_instruktur' => 'Instruktur',
                    'laporan_bulanan' => 'Bulanan',
                    'laporan_tahunan' => 'Tahunan',
                ])
                ->required(),
            TextInput::make('periode')
                ->label('Periode')
                ->placeholder('Contoh: 2026-05')
                ->required()
                ->maxLength(255),
            Textarea::make('catatan')
                ->label('Catatan/Isi Laporan')
                ->rows(4)
                ->nullable()
                ->columnSpanFull(),
            Hidden::make('dibuat_oleh')
                ->default(fn () => Auth::id()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul Laporan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tipe_laporan')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'laporan_kelulusan' => 'success',
                        'laporan_keuangan' => 'warning',
                        'laporan_akademik' => 'info',
                        'laporan_instruktur' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'laporan_kelulusan' => 'Kelulusan',
                        'laporan_keuangan' => 'Keuangan',
                        'laporan_akademik' => 'Akademik',
                        'laporan_instruktur' => 'Instruktur',
                        'laporan_bulanan' => 'Bulanan',
                        'laporan_tahunan' => 'Tahunan',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('periode')
                    ->label('Periode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pembuat.name')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe_laporan')
                    ->options([
                        'laporan_kelulusan' => 'Kelulusan',
                        'laporan_keuangan' => 'Keuangan',
                        'laporan_akademik' => 'Akademik',
                        'laporan_instruktur' => 'Instruktur',
                        'laporan_bulanan' => 'Bulanan',
                        'laporan_tahunan' => 'Tahunan',
                    ]),
            ])
             ->recordActions([
                \Filament\Actions\ViewAction::make()
                    ->visible(fn () => auth()->user()?->role?->nama_role === 'Instruktur'),
                \Filament\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()?->role?->nama_role === 'Admin Akademik'),
                \Filament\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()?->role?->nama_role === 'Admin Akademik'),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLaporans::route('/'),
            'create' => CreateLaporan::route('/create'),
            'edit' => EditLaporan::route('/{record}/edit'),
        ];
    }
}
