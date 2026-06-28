<?php

namespace App\Filament\Resources\TiketKeluhans\Tables;

use App\Models\TiketKeluhan;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TiketKeluhansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pelapor.name')
                    ->label('Pelapor')
                    ->searchable(),

                TextColumn::make('kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pembelajaran'             => 'primary',
                        'Error Sistem'             => 'danger',
                        'Pendaftaran & Pembayaran' => 'warning',
                        'Hal Lainnya'              => 'success',
                        default                    => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('subjek')
                    ->searchable(),

                TextColumn::make('deskripsi')
                    ->label('Detail Keluhan')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->deskripsi)
                    ->searchable(),

                TextColumn::make('prioritas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Rendah' => 'success',
                        'Sedang' => 'warning',
                        'Tinggi' => 'danger',
                        default  => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Open'        => 'primary',
                        'In Progress' => 'warning',
                        'Resolved'    => 'success',
                        'Closed'      => 'gray',
                        default       => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // Inline status-update — hanya Admin yang melihat ini
                Action::make('update_status')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn () => Auth::user()?->role?->nama_role === 'Admin Akademik')
                    ->form([
                        Select::make('status')
                            ->label('Status Penanganan')
                            ->options([
                                'Open'        => 'Open',
                                'In Progress' => 'In Progress',
                                'Resolved'    => 'Resolved',
                            ])
                            ->required(),

                        Textarea::make('catatan_admin')
                            ->label('Catatan / Respons Admin')
                            ->placeholder('Tuliskan catatan penanganan atau respons untuk pelapor...')
                            ->rows(3),
                    ])
                    ->fillForm(fn (TiketKeluhan $record): array => [
                        'status'        => $record->status,
                        'catatan_admin' => $record->catatan_admin,
                    ])
                    ->action(function (TiketKeluhan $record, array $data): void {
                        $updateData = [
                            'status'         => $data['status'],
                            'catatan_admin'  => $data['catatan_admin'] ?? null,
                            'ditangani_oleh' => Auth::id(),
                        ];

                        if ($data['status'] === 'Resolved') {
                            $updateData['resolved_at'] = now();
                        }

                        $record->update($updateData);

                        Notification::make()
                            ->title('Status Tiket Diperbarui')
                            ->body("Status tiket \"{$record->subjek}\" diubah ke {$data['status']}.")
                            ->success()
                            ->send();
                    }),

                EditAction::make()
                    ->label('Edit'),
            ])
            ->toolbarActions([]);
    }
}
