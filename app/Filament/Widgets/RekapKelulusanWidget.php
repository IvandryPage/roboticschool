<?php

namespace App\Filament\Widgets;

use App\Models\ProgramKursus;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

/**
 * PBI-136: Rekap Kelulusan Program
 * PBI-137: Filter Dashboard (program & periode)
 * Hanya tampil untuk role: Direktur
 *
 * Menggunakan Widget biasa (bukan TableWidget) untuk menghindari
 * konflik PostgreSQL GROUP BY vs Filament auto ORDER BY primary key.
 */
class RekapKelulusanWidget extends Widget
{
    protected string $view = 'filament.widgets.rekap-kelulusan';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    // Filter state — PBI-137
    public ?string $filterProgram = null;
    public ?string $filterTahun   = null;

    /**
     * PBI-136: Widget ini hanya untuk Direktur.
     */
    public static function canView(): bool
    {
        return auth()->user()?->role?->nama_role === 'Direktur';
    }

    /**
     * Data yang dikirim ke Blade view.
     */
    public function getViewData(): array
    {
        $query = DB::table('enrollment_kelas')
            ->selectRaw('
                kelas.batch_id,
                batch.program_id,
                program_kursus.nama_program,
                batch.nama_batch,
                COUNT(*) as total_siswa,
                SUM(CASE WHEN enrollment_kelas.status = \'Selesai\' THEN 1 ELSE 0 END) as total_lulus,
                SUM(CASE WHEN enrollment_kelas.status = \'Aktif\'   THEN 1 ELSE 0 END) as total_aktif,
                SUM(CASE WHEN enrollment_kelas.status = \'Drop\'    THEN 1 ELSE 0 END) as total_drop
            ')
            ->join('kelas',         'enrollment_kelas.kelas_id',  '=', 'kelas.id')
            ->join('batch',         'kelas.batch_id',             '=', 'batch.id')
            ->join('program_kursus','batch.program_id',           '=', 'program_kursus.id')
            ->groupBy(
                'kelas.batch_id',
                'batch.program_id',
                'program_kursus.nama_program',
                'batch.nama_batch'
            )
            ->orderBy('program_kursus.nama_program')
            ->orderBy('batch.nama_batch');

        // PBI-137: Filter Program
        if ($this->filterProgram) {
            $query->where('batch.program_id', $this->filterProgram);
        }

        // PBI-137: Filter Tahun
        if ($this->filterTahun) {
            $query->whereYear('batch.tanggal_mulai', $this->filterTahun);
        }

        $rekap    = $query->get();
        $programs = ProgramKursus::orderBy('nama_program')->pluck('nama_program', 'id');

        // Tahun tersedia dari batch yang ada
        $tahunOptions = [];
        $tahunSekarang = now()->year;
        for ($y = $tahunSekarang - 3; $y <= $tahunSekarang + 1; $y++) {
            $tahunOptions[$y] = (string) $y;
        }

        return compact('rekap', 'programs', 'tahunOptions');
    }
}
