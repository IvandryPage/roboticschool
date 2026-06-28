<?php

namespace App\Filament\Resources\Sertifikats\Pages;

use App\Filament\Resources\Sertifikats\SertifikatResource;
use App\Models\EnrollmentKelas;
use App\Models\Sertifikat;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;

class ListSertifikats extends ListRecords
{
    protected static string $resource = SertifikatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ── Terbitkan Sertifikat ──────────────────────────────────
            Action::make('terbitkan_sertifikat')
                ->label('Terbitkan Sertifikat')
                ->icon('heroicon-o-academic-cap')
                ->color('success')
                ->form([
                    Select::make('enrollment_id')
                        ->label('Siswa & Kelas (Status Selesai)')
                        ->placeholder('Pilih siswa yang memenuhi syarat kelulusan...')
                        ->options(function (): array {
                            // Ambil enrollment dengan status Selesai yang belum punya sertifikat
                            return EnrollmentKelas::query()
                                ->where('status', 'Selesai')
                                ->with(['siswa.user', 'kelas'])
                                ->get()
                                ->filter(function (EnrollmentKelas $enrollment): bool {
                                    // Exclude yang sudah ada sertifikatnya
                                    return ! Sertifikat::where('siswa_id', $enrollment->siswa_id)
                                        ->where('kelas_id', $enrollment->kelas_id)
                                        ->exists();
                                })
                                ->mapWithKeys(function (EnrollmentKelas $enrollment): array {
                                    $namaSiswa  = $enrollment->siswa?->user?->nama_lengkap
                                        ?? $enrollment->siswa?->user?->name
                                        ?? 'Siswa #' . $enrollment->siswa_id;
                                    $namaKelas  = $enrollment->kelas?->nama_kelas ?? 'Kelas #' . $enrollment->kelas_id;
                                    return [$enrollment->id => "{$namaSiswa} — {$namaKelas}"];
                                })
                                ->all();
                        })
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $enrollment = EnrollmentKelas::with(['siswa', 'kelas.batch.program'])->find($data['enrollment_id']);

                    if (! $enrollment) {
                        Notification::make()->title('Data enrollment tidak ditemukan.')->danger()->send();
                        return;
                    }

                    // Guard: jangan terbitkan duplikat
                    if (Sertifikat::where('siswa_id', $enrollment->siswa_id)
                        ->where('kelas_id', $enrollment->kelas_id)
                        ->exists()) {
                        Notification::make()
                            ->title('Sertifikat sudah diterbitkan')
                            ->body('Siswa ini sudah memiliki sertifikat untuk kelas tersebut.')
                            ->warning()
                            ->send();
                        return;
                    }

                    // Generate nomor sertifikat unik: CERT-{YEAR}-{PROGRAM_KODE}-{SEQUENCE}
                    $tahun       = now()->format('Y');
                    $programKode = strtoupper(substr(
                        $enrollment->kelas?->batch?->program?->nama_program ?? 'ROB',
                        0,
                        3
                    ));

                    $sequence = str_pad(
                        Sertifikat::whereYear('created_at', $tahun)->count() + 1,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );

                    $nomorSertifikat = "CERT-{$tahun}-{$programKode}-{$sequence}";

                    // Pastikan unik (race condition guard)
                    while (Sertifikat::where('nomor_sertifikat', $nomorSertifikat)->exists()) {
                        $sequence++;
                        $nomorSertifikat = "CERT-{$tahun}-{$programKode}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
                    }

                    Sertifikat::create([
                        'id'               => (string) Str::uuid(),
                        'siswa_id'         => $enrollment->siswa_id,
                        'kelas_id'         => $enrollment->kelas_id,
                        'nomor_sertifikat' => $nomorSertifikat,
                        'tanggal_terbit'   => now(),
                        'diterbitkan_oleh' => auth()->id(),
                    ]);

                    $namaSiswa = $enrollment->siswa?->user?->nama_lengkap
                        ?? $enrollment->siswa?->user?->name
                        ?? 'Siswa';

                    Notification::make()
                        ->title('Sertifikat Diterbitkan')
                        ->body("Sertifikat {$nomorSertifikat} berhasil diterbitkan untuk {$namaSiswa}.")
                        ->success()
                        ->send();
                }),
        ];
    }
}
