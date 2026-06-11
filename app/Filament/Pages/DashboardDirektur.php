<?php
namespace App\Filament\Pages;
use App\Models\EnrollmentKelas;
use App\Models\ProgramKursus;
use App\Models\Sertifikat;
use App\Models\Siswa;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
class DashboardDirektur extends Page implements HasForms
{
    use InteractsWithForms;

    public static function getNavigationLabel(): string
    {
        return 'Dashboard Direktur';
    }

    public function getView(): string
    {
        return 'filament.pages.dashboard-direktur';
    }

    public ?string $filterProgram = null;
    public ?string $filterPeriode = null;

    public function getStats(): array
    {
        return [
            'total_siswa_aktif' => Siswa::count(),
            'total_program' => ProgramKursus::where('status_tampil', true)->count(),
            'total_sertifikat' => Sertifikat::count(),
            'total_enrollment' => EnrollmentKelas::count(),
        ];
    }

    public function getProgramOptions(): array
    {
        return ProgramKursus::pluck('nama_program', 'id')->toArray();
    }

    public function getPeriodeOptions(): array
    {
        return [
            '2024' => '2024',
            '2025' => '2025',
            '2026' => '2026',
        ];
    }

    public function getRekapProgram()
    {
        $query = ProgramKursus::query();

        if ($this->filterProgram) {
            $query->where('id', $this->filterProgram);
        }

        return $query->get()->map(function ($program) {
            $siswaQuery = EnrollmentKelas::whereHas(
                'kelas.batch',
                fn($q) => $q->where('program_id', $program->id)
            );

            $lulusQuery = Sertifikat::whereHas(
                'kelas.batch',
                fn($q) => $q->where('program_id', $program->id)
            );

            if ($this->filterPeriode) {
                $siswaQuery->whereYear('created_at', $this->filterPeriode);
                $lulusQuery->whereYear('created_at', $this->filterPeriode);
            }

            $totalSiswa = $siswaQuery->count();
            $totalLulus = $lulusQuery->count();

            return [
                'nama_program' => $program->nama_program,
                'total_siswa' => $totalSiswa,
                'total_lulus' => $totalLulus,
                'tingkat_kelulusan' => $totalSiswa > 0
                    ? round(($totalLulus / $totalSiswa) * 100, 1)
                    : 0,
            ];
        });
    }
}