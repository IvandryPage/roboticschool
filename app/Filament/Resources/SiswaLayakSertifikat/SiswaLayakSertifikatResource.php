<?php

namespace App\Filament\Resources\SiswaLayakSertifikat;

use App\Filament\Resources\SiswaLayakSertifikat\Pages\ListSiswaLayakSertifikat;
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

/**
 * PBI-124: Halaman daftar siswa layak sertifikat (Admin only)
 * PBI-125: Aksi terbitkan sertifikat
 */
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

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-academic-cap';
    }

    /**
     * PBI-124: Hanya Admin Akademik yang bisa melihat & mengakses.
     */
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role?->nama_role === 'Admin Akademik';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role?->nama_role === 'Admin Akademik';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                // PBI-124: Filter siswa layak sertifikat:
                // - Status enrollment = 'Selesai'
                // - Belum punya sertifikat di kelas tersebut
                // - Kehadiran >= 75% DAN nilai >= 70 (PBI-121)
                EnrollmentKelas::query()
                    ->where('status', 'Selesai')
                    ->whereDoesntHave('siswa.sertifikat', function ($q) {
                        $q->whereColumn('sertifikat.kelas_id', 'enrollment_kelas.kelas_id');
                    })
                    ->whereHas('siswa.progressAkademik', function ($q) {
                        $q->whereColumn('progress_akademik.kelas_id', 'enrollment_kelas.kelas_id')
                          ->where('persentase_kehadiran', '>=', SertifikatService::SYARAT_KEHADIRAN_MIN)
                          ->where('rata_nilai_tugas', '>=', SertifikatService::SYARAT_NILAI_MIN);
                    })
                    ->with(['siswa.user', 'kelas.batch.program', 'siswa.progressAkademik'])
            )
            ->columns([
                TextColumn::make('siswa.user.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelas.batch.program.nama_program')
                    ->label('Program')
                    ->searchable(),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),

                TextColumn::make('siswa.progressAkademik.persentase_kehadiran')
                    ->label('Kehadiran')
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format($state, 1).'%' : '-')
                    ->color(fn ($state) => $state !== null && $state >= SertifikatService::SYARAT_KEHADIRAN_MIN ? 'success' : 'danger')
                    ->badge()
                    ->sortable(),

                TextColumn::make('siswa.progressAkademik.rata_nilai_tugas')
                    ->label('Rata-rata Nilai')
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format($state, 1).'/100' : '-')
                    ->color(fn ($state) => $state !== null && $state >= SertifikatService::SYARAT_NILAI_MIN ? 'success' : 'danger')
                    ->badge()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Selesai' => 'success',
                        'Aktif'   => 'info',
                        'Drop'    => 'danger',
                        default   => 'gray',
                    }),
            ])
            ->actions([
                // PBI-125: Terbitkan sertifikat per siswa
                Action::make('terbitkan')
                    ->label('Terbitkan Sertifikat')
                    ->icon('heroicon-o-academic-cap')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Terbitkan Sertifikat')
                    ->modalDescription(
                        fn (EnrollmentKelas $record) =>
                        'Terbitkan sertifikat untuk '.($record->siswa?->user?->nama_lengkap ?? '-').' — '.($record->kelas?->nama_kelas ?? '-').'?'
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
                        ->label('Terbitkan Semua Terpilih')
                        ->icon('heroicon-o-academic-cap')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $service  = new SertifikatService();
                            $berhasil = 0;
                            $gagal    = 0;
                            foreach ($records as $record) {
                                try {
                                    $service->terbitkanSertifikat(
                                        $record->siswa_id,
                                        $record->kelas_id,
                                        Auth::id()
                                    );
                                    $berhasil++;
                                } catch (\Exception $e) {
                                    $gagal++;
                                }
                            }
                            Notification::make()
                                ->title("{$berhasil} sertifikat berhasil diterbitkan".($gagal > 0 ? ", {$gagal} gagal." : '.'))
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiswaLayakSertifikat::route('/'),
        ];
    }
}
