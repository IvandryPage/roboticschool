<?php

namespace App\Filament\Resources\Pendaftarans;

use App\Filament\Resources\Pendaftarans\Pages\ListPendaftarans;
use App\Filament\Resources\Pendaftarans\Pages\ViewPendaftaran;
use App\Models\DokumenPendaftaran;
use App\Models\Pendaftaran;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Pendaftaran';

    protected static ?string $modelLabel = 'Pendaftaran';

    protected static ?string $pluralModelLabel = 'Pendaftaran Masuk';

    protected static ?string $recordTitleAttribute = 'no_referensi';

    protected static string|\UnitEnum|null $navigationGroup = 'Administrasi Sistem';

    protected static ?int $navigationSort = 1;

    // ── Access control ──────────────────────────────────────────────

    public static function canViewAny(): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    // ── Table ────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_referensi')
                    ->label('No. Referensi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('calonPeserta.nama_lengkap')
                    ->label('Nama Calon Peserta')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('calonPeserta.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('program.nama_program')
                    ->label('Program')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'revisi'    => 'info',
                        'disetujui' => 'success',
                        'ditolak'   => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Menunggu',
                        'revisi'    => 'Perlu Revisi',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                        default     => $state,
                    })
                    ->sortable(),

                TextColumn::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('tanggal_daftar', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Menunggu',
                        'revisi'    => 'Perlu Revisi',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                    ]),

                SelectFilter::make('program_id')
                    ->label('Program')
                    ->relationship('program', 'nama_program'),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
            ])
            ->toolbarActions([]);
    }

    // ── Infolist (View page) ─────────────────────────────────────────
    // Filament v5: parameter harus Schema, bukan Infolist.
    // Section/Grid dari Filament\Infolists tidak ada di v5 — pakai flat TextEntry.

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ── Data Calon Peserta ──
                TextEntry::make('calonPeserta.nama_lengkap')
                    ->label('Nama Lengkap'),

                TextEntry::make('calonPeserta.email')
                    ->label('Email'),

                TextEntry::make('calonPeserta.no_hp')
                    ->label('No. HP')
                    ->default('-'),

                TextEntry::make('calonPeserta.asal_sekolah_atau_instansi')
                    ->label('Asal Sekolah / Instansi')
                    ->default('-'),

                // ── Detail Pendaftaran ──
                TextEntry::make('no_referensi')
                    ->label('No. Referensi')
                    ->weight('bold')
                    ->copyable(),

                TextEntry::make('program.nama_program')
                    ->label('Program'),

                TextEntry::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->date('d M Y'),

                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'revisi'    => 'info',
                        'disetujui' => 'success',
                        'ditolak'   => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Menunggu',
                        'revisi'    => 'Perlu Revisi',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                        default     => $state,
                    }),

                TextEntry::make('catatan_admin')
                    ->label('Catatan Admin')
                    ->default('-')
                    ->columnSpanFull(),

                // ── Dokumen ──
                TextEntry::make('dokumenPendaftaran')
                    ->label('Status Dokumen')
                    ->getStateUsing(function (Pendaftaran $record): string {
                        if ($record->dokumenPendaftaran->isEmpty()) {
                            return 'Tidak ada dokumen yang diunggah.';
                        }

                        return $record->dokumenPendaftaran
                            ->map(function (DokumenPendaftaran $dok): string {
                                $status = match ($dok->status_verifikasi) {
                                    'valid'       => '✅ Valid',
                                    'tidak_valid' => '❌ Tidak Valid',
                                    default       => '⏳ Belum Diverifikasi',
                                };
                                $catatan = $dok->catatan ? " — {$dok->catatan}" : '';
                                return "• {$dok->jenis_dokumen}: {$status}{$catatan}";
                            })
                            ->implode("\n");
                    })
                    ->columnSpanFull(),
            ]);
    }

    // ── Pages ────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index' => ListPendaftarans::route('/'),
            'view'  => ViewPendaftaran::route('/{record}'),
        ];
    }
}