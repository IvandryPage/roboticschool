<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeminjamanItemAsetResource\Pages;
use App\Models\PeminjamanItemAset;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class PeminjamanItemAsetResource extends Resource
{
    protected static ?string $model = PeminjamanItemAset::class;

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return 'Persetujuan Peminjaman';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Operasional';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-check';
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
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('peminjam.nama_lengkap')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('itemKit.aset.nama_kit')
                    ->label('Aset / Kit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('itemKit.serial_number')
                    ->label('Serial Number')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Diajukan' => 'warning',
                        'Dipinjam' => 'info',
                        'Dikembalikan' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('tanggal_pinjam')
                    ->label('Tanggal Pinjam')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->dateTime('d M Y')
                    ->placeholder('-'),
                TextColumn::make('tanggal_kembali')
                    ->label('Tanggal Kembali')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('kondisi_awal')
                    ->label('Kondisi Awal')
                    ->placeholder('-'),
                TextColumn::make('kondisi_akhir')
                    ->label('Kondisi Akhir')
                    ->placeholder('-'),
                TextColumn::make('verifikator.nama_lengkap')
                    ->label('Diverifikasi Oleh')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Diajukan' => 'Diajukan',
                        'Dipinjam' => 'Dipinjam',
                        'Dikembalikan' => 'Dikembalikan',
                        'Ditolak' => 'Ditolak',
                    ])
                    ->label('Filter Status'),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (PeminjamanItemAset $record) => $record->status === 'Diajukan')
                    ->requiresConfirmation()
                    ->action(function (PeminjamanItemAset $record) {
                        $itemKit = $record->itemKit;
                        if ($itemKit->status_kondisi !== 'Bagus') {
                            Notification::make()
                                ->danger()
                                ->title('Item kit rusak atau sedang dalam perbaikan.')
                                ->send();
                            return;
                        }

                        $isBorrowed = PeminjamanItemAset::where('item_kit_id', $itemKit->id)
                            ->where('status', 'Dipinjam')
                            ->exists();
                        if ($isBorrowed) {
                            Notification::make()
                                ->danger()
                                ->title('Item kit sedang dipinjam oleh user lain.')
                                ->send();
                            return;
                        }

                        $record->status = 'Dipinjam';
                        $record->tanggal_pinjam = now();
                        $record->diverifikasi_oleh = Auth::id();
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Permohonan peminjaman berhasil disetujui.')
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (PeminjamanItemAset $record) => $record->status === 'Diajukan')
                    ->requiresConfirmation()
                    ->action(function (PeminjamanItemAset $record) {
                        $record->status = 'Ditolak';
                        $record->diverifikasi_oleh = Auth::id();
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Permohonan peminjaman berhasil ditolak.')
                            ->send();
                    }),
                Action::make('return')
                    ->label('Kembalikan')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->color('info')
                    ->visible(fn (PeminjamanItemAset $record) => $record->status === 'Dipinjam')
                    ->form([
                        Select::make('kondisi_akhir')
                            ->label('Kondisi Akhir')
                            ->options([
                                'Baik' => 'Baik',
                                'Rusak' => 'Rusak',
                                'Hilang' => 'Hilang',
                            ])
                            ->required()
                    ])
                    ->action(function (PeminjamanItemAset $record, array $data) {
                        $record->status = 'Dikembalikan';
                        $record->tanggal_kembali = now();
                        $record->kondisi_akhir = $data['kondisi_akhir'];
                        $record->save();

                        $statusKondisi = 'Bagus';
                        if ($data['kondisi_akhir'] === 'Rusak' || $data['kondisi_akhir'] === 'Hilang') {
                            $statusKondisi = 'Rusak';
                        }

                        $itemKit = $record->itemKit;
                        $itemKit->status_kondisi = $statusKondisi;
                        $itemKit->save();

                        Notification::make()
                            ->success()
                            ->title('Konfirmasi pengembalian berhasil diproses.')
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjamanItemAsets::route('/'),
        ];
    }
}
