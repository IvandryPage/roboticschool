<?php

namespace App\Filament\Resources\Pendaftarans\Pages;

use App\Filament\Resources\Pendaftarans\PendaftaranResource;
use App\Models\DokumenPendaftaran;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPendaftaran extends ViewRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ── Setujui ─────────────────────────────────────────────
            Action::make('setujui')
                ->label('Setujui Pendaftaran')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Setujui Pendaftaran?')
                ->modalDescription(
                    'Semua dokumen harus sudah valid. Setelah disetujui, invoice akan diterbitkan otomatis.'
                )
                ->visible(fn () => $this->record->status === 'pending' || $this->record->status === 'revisi')
                ->before(function (Action $action) {
                    $semuaDokumen  = $this->record->dokumenPendaftaran;
                    $adaDokumen    = $semuaDokumen->isNotEmpty();
                    $semuaValid    = $semuaDokumen->every(
                        fn ($d) => $d->status_verifikasi === 'valid'
                    );

                    if (! $adaDokumen || ! $semuaValid) {
                        Notification::make()
                            ->title('Tidak dapat menyetujui')
                            ->body('Semua dokumen harus berstatus Valid terlebih dahulu.')
                            ->danger()
                            ->send();

                        $action->cancel();
                    }
                })
                ->action(function () {
                    $this->record->update(['status' => 'disetujui']);

                    Notification::make()
                        ->title('Pendaftaran Disetujui')
                        ->body("No. referensi {$this->record->no_referensi} telah disetujui dan invoice diterbitkan.")
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                }),

            // ── Verifikasi Dokumen ───────────────────────────────────
            Action::make('verifikasi_dokumen')
                ->label('Verifikasi Dokumen')
                ->icon('heroicon-o-document-check')
                ->color('info')
                ->visible(fn () => in_array($this->record->status, ['pending', 'revisi']))
                ->form(function (): array {
                    $dokumenList = $this->record->dokumenPendaftaran;

                    if ($dokumenList->isEmpty()) {
                        return [
                            \Filament\Forms\Components\Placeholder::make('kosong')
                                ->content('Tidak ada dokumen yang perlu diverifikasi.'),
                        ];
                    }

                    return $dokumenList->map(function (DokumenPendaftaran $dok): \Filament\Forms\Components\Section {
                        return \Filament\Forms\Components\Section::make($dok->jenis_dokumen)
                            ->schema([
                                \Filament\Forms\Components\Select::make("dokumen_{$dok->id}_status")
                                    ->label('Status Verifikasi')
                                    ->options([
                                        'valid'       => 'Valid',
                                        'tidak_valid' => 'Tidak Valid',
                                    ])
                                    ->default($dok->status_verifikasi)
                                    ->required(),

                                Textarea::make("dokumen_{$dok->id}_catatan")
                                    ->label('Catatan (jika tidak valid)')
                                    ->default($dok->catatan)
                                    ->nullable(),
                            ]);
                    })->values()->all();
                })
                ->action(function (array $data) {
                    foreach ($this->record->dokumenPendaftaran as $dok) {
                        $statusKey  = "dokumen_{$dok->id}_status";
                        $catatanKey = "dokumen_{$dok->id}_catatan";

                        if (isset($data[$statusKey])) {
                            $dok->update([
                                'status_verifikasi' => $data[$statusKey],
                                'catatan'           => $data[$catatanKey] ?? null,
                            ]);
                        }
                    }

                    Notification::make()
                        ->title('Dokumen Diperbarui')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                }),

            // ── Kirim Revisi ─────────────────────────────────────────
            Action::make('revisi')
                ->label('Minta Revisi')
                ->icon('heroicon-o-pencil-square')
                ->color('warning')
                ->visible(fn () => $this->record->status === 'pending')
                ->form([
                    Textarea::make('catatan_admin')
                        ->label('Catatan Revisi')
                        ->placeholder('Jelaskan dokumen mana yang perlu diperbaiki...')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status'        => 'revisi',
                        'catatan_admin' => $data['catatan_admin'],
                    ]);

                    Notification::make()
                        ->title('Catatan Revisi Dikirim')
                        ->body('Calon peserta perlu mengunggah ulang dokumen.')
                        ->warning()
                        ->send();

                    $this->refreshFormData(['status', 'catatan_admin']);
                }),

            // ── Tolak ────────────────────────────────────────────────
            Action::make('tolak')
                ->label('Tolak Pendaftaran')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Tolak Pendaftaran?')
                ->visible(fn () => in_array($this->record->status, ['pending', 'revisi']))
                ->form([
                    Textarea::make('catatan_admin')
                        ->label('Alasan Penolakan')
                        ->placeholder('Jelaskan alasan penolakan pendaftaran ini...')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status'        => 'ditolak',
                        'catatan_admin' => $data['catatan_admin'],
                    ]);

                    Notification::make()
                        ->title('Pendaftaran Ditolak')
                        ->danger()
                        ->send();

                    $this->refreshFormData(['status', 'catatan_admin']);
                }),
        ];
    }
}
