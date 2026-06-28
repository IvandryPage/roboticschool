<?php

namespace App\Filament\Admin\Resources\Kelas\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class SesiLiveRelationManager extends RelationManager
{
    protected static string $relationship = 'sesiLive';

    protected static ?string $title = 'Jadwal Sesi Live';

    protected static ?string $recordTitleAttribute = 'judul_sesi';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('nomor_sesi')
                    ->numeric()
                    ->required(),
                TextInput::make('judul_sesi')
                    ->required(),
                DatePicker::make('tanggal')
                    ->required(),
                TimePicker::make('jam_mulai')
                    ->required(),
                TimePicker::make('jam_selesai')
                    ->required(),
                TextInput::make('platform')
                    ->required(),
                TextInput::make('link_akses')
                    ->url()
                    ->required(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('judul_sesi')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_sesi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul_sesi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->time(),
                Tables\Columns\TextColumn::make('jam_selesai')
                    ->time(),
                Tables\Columns\TextColumn::make('platform'),
                Tables\Columns\TextColumn::make('link_akses')
                    ->url(fn ($record) => $record->link_akses)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Sesi'),
            ])
            ->recordActions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
