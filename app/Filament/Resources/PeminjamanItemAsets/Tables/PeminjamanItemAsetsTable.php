<?php

namespace App\Filament\Resources\PeminjamanItemAsets\Tables;

use App\Models\PeminjamanItemAset;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanItemAsetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->limit(8)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('peminjam.nama_lengkap')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('itemKit.serial_number')
                    ->label('Item Kit')
                    ->description(fn (PeminjamanItemAset $record): string => $record->itemKit?->aset?->nama_kit ?? '-')
                    ->searchable(),
                TextColumn::make('tanggal_pinjam')
                    ->label('Tanggal Pinjam')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Diajukan',
                        'success' => 'Dipinjam',
                        'danger' => fn ($state) => in_array($state, ['Ditolak', 'Terlambat']),
                        'info' => 'Dikembalikan',
                        'gray' => 'Rusak',
                    ]),
                TextColumn::make('verifikator.nama_lengkap')
                    ->label('Diverifikasi Oleh')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (PeminjamanItemAset $record): bool => $record->status === 'Diajukan')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Peminjaman')
                    ->modalDescription(fn (PeminjamanItemAset $record): string => "Setujui peminjaman item {$record->itemKit?->serial_number} oleh {$record->peminjam?->nama_lengkap}?"
                    )
                    ->action(function (PeminjamanItemAset $record): void {
                        DB::transaction(function () use ($record) {
                            // Lock baris ini untuk mencegah race condition: dua admin
                            // approve item yang sama di waktu bersamaan
                            $locked = PeminjamanItemAset::where('id', $record->id)->lockForUpdate()->first();

                            $sedangDipinjam = PeminjamanItemAset::where('item_kit_id', $locked->item_kit_id)
                                ->where('status', 'Dipinjam')
                                ->exists();

                            if ($sedangDipinjam) {
                                Notification::make()
                                    ->title('Gagal menyetujui')
                                    ->body('Item ini sedang dipinjam oleh pengguna lain.')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $locked->update([
                                'status' => 'Dipinjam',
                                'diverifikasi_oleh' => Auth::id(),
                                'tanggal_pinjam' => $locked->tanggal_pinjam ?? now(),
                            ]);

                            Notification::make()
                                ->title('Peminjaman disetujui')
                                ->success()
                                ->send();
                        });
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (PeminjamanItemAset $record): bool => $record->status === 'Diajukan')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Peminjaman')
                    ->schema([
                        Textarea::make('alasan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->minLength(5),
                    ])
                    ->action(function (PeminjamanItemAset $record, array $data): void {
                        $record->update([
                            'status' => 'Ditolak',
                            'diverifikasi_oleh' => Auth::id(),
                            'kondisi_akhir' => $data['alasan'],
                        ]);

                        Notification::make()
                            ->title('Peminjaman ditolak')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}