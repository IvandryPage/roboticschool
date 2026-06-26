<?php

namespace App\Filament\Publikasi\Resources\MateriPrograms;

use App\Filament\Publikasi\Resources\MateriPrograms\Pages\ManageMateriPrograms;
use App\Models\MateriProgram;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MateriProgramResource extends Resource
{
    protected static ?string $model = MateriProgram::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Materi per Program';

    protected static ?string $modelLabel = 'Materi Program';

    protected static ?string $pluralModelLabel = 'Materi Program';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

        /** Hanya Tim Publikasi yang mengelola rincian materi program per PRD */
    public static function canViewAny(): bool
    {
        return Auth::user()?->role?->nama_role === 'Tim Publikasi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('program_id')
                ->label('Program Kursus')
                ->relationship('program', 'nama_program')
                ->required()
                ->searchable()
                ->preload(),

            TextInput::make('nomor_urut')
                ->label('Nomor Urut')
                ->numeric()
                ->required(),

            TextInput::make('judul_materi')
                ->label('Judul Materi')
                ->required()
                ->maxLength(255),

            Textarea::make('deskripsi_materi')
                ->label('Deskripsi Materi')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('program.nama_program')
                    ->label('Program')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nomor_urut')
                    ->label('No.')
                    ->sortable(),

                TextColumn::make('judul_materi')
                    ->label('Judul Materi')
                    ->searchable(),

                TextColumn::make('deskripsi_materi')
                    ->label('Deskripsi')
                    ->limit(50),
            ])
            ->defaultSort('program_id')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMateriPrograms::route('/'),
        ];
    }
}
