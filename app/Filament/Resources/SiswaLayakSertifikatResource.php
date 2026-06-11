<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaLayakSertifikatResource\Pages;
use App\Models\EnrollmentKelas;
use App\Services\SertifikatService;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SiswaLayakSertifikatResource extends Resource
{
    protected static ?string $model = EnrollmentKelas::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'siswa-layak-sertifikat';

    public static function getNavigationLabel(): string
    {
        return 'Siswa Layak Sertifikat';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                EnrollmentKelas::query()
                    ->where('status', 'lulus')
                    ->whereDoesntHave('sertifikat')
                    ->with(['siswa.user', 'kelas.batch'])
            )
            ->columns([
                TextColumn::make('siswa.user.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelas.batch.nama_batch')
                    ->label('Batch/Program')
                    ->searchable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->suffix('/100')
                    ->sortable(),
                TextColumn::make('persentase_kehadiran')
                    ->label('Kehadiran')
                    ->formatStateUsing(fn($state) => $state . '%')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'lulus' => 'success',
                        default => 'gray',
                    }),
            ])
            ->actions([
                // Ganti Actions::make jadi Action::make
                Action::make('terbitkan')
                    ->label('Terbitkan Sertifikat')
                    ->icon('heroicon-o-academic-cap')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Terbitkan Sertifikat')
                    ->modalDescription(
                        fn(EnrollmentKelas $record) =>
                        "Terbitkan sertifikat untuk {$record->siswa->user->name} - {$record->kelas->nama_kelas}?"
                    )
                    ->action(function (EnrollmentKelas $record) {
                        try {
                            $service = new SertifikatService();
                            $service->terbitkanSertifikat(
                                $record->siswa_id,
                                $record->kelas_id,
                                Auth::id()
                            );
                            Notification::make()
                                ->title('Sertifikat berhasil diterbitkan!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal menerbitkan sertifikat')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('terbitkan_semua')
                        ->label('Terbitkan Semua')
                        ->icon('heroicon-o-academic-cap')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $service = new SertifikatService();
                            $berhasil = 0;
                            foreach ($records as $record) {
                                try {
                                    $service->terbitkanSertifikat(
                                        $record->siswa_id,
                                        $record->kelas_id,
                                        Auth::id()
                                    );
                                    $berhasil++;
                                } catch (\Exception $e) {
                                    // Error diabaikan agar proses tetap berjalan
                                }
                            }
                            Notification::make()
                                ->title("{$berhasil} sertifikat berhasil diterbitkan!")
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswaLayakSertifikat::route('/'),
        ];
    }
}
