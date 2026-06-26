<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class MateriProgramRelationManager extends RelationManager
{
    protected static string $relationship = 'materiProgram';

    protected static ?string $title = 'Rincian Materi Program';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nomor_urut')
                ->label('Nomor Urut')
                ->numeric()
                ->required(),

            TextInput::make('judul_materi')
                ->label('Judul Materi')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Textarea::make('deskripsi_materi')
                ->label('Deskripsi Materi')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_urut')
                    ->label('No.')
                    ->sortable(),
                TextColumn::make('judul_materi')
                    ->label('Judul Materi')
                    ->searchable(),
                TextColumn::make('deskripsi_materi')
                    ->label('Deskripsi')
                    ->limit(60),
            ])
            ->defaultSort('nomor_urut')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
            ]);
    }
}
