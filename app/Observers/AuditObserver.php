<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;

class AuditObserver
{
    /**
     * Entitas bisnis yang penting untuk dilaporkan ke Direktur.
     * Direktur tidak perlu lihat perubahan teknis/internal seperti cache atau session.
     */
    private array $entitasBisnis = [
        'App\\Models\\Pembayaran',
        'App\\Models\\Pendaftaran',
        'App\\Models\\Siswa',
        'App\\Models\\Kelas',
        'App\\Models\\EnrollmentKelas',
        'App\\Models\\Sertifikat',
        'App\\Models\\TiketKeluhan',
        'App\\Models\\User',
    ];

    private function tipe(string $entityType): string
    {
        return in_array($entityType, $this->entitasBisnis) ? 'bisnis' : 'teknis';
    }

    public function updated($model): void
    {
        if (! Auth::check()) return;

        $entityType = get_class($model);

        AuditLog::create([
            'user_id'      => Auth::id(),
            'aksi'         => 'Update / Verifikasi',
            'tipe'         => $this->tipe($entityType),
            'entity_type'  => $entityType,
            'entity_id'    => $model->id,
            'data_sebelum' => json_encode($model->getOriginal()),
            'data_sesudah' => json_encode($model->getChanges()),
            'ip_address'   => Request::ip(),
        ]);
    }

    public function deleted($model): void
    {
        if (! Auth::check()) return;

        $entityType = get_class($model);

        AuditLog::create([
            'user_id'      => Auth::id(),
            'aksi'         => 'Delete Data',
            'tipe'         => $this->tipe($entityType),
            'entity_type'  => $entityType,
            'entity_id'    => $model->id,
            'data_sebelum' => json_encode($model->getOriginal()),
            'data_sesudah' => json_encode([]),
            'ip_address'   => Request::ip(),
        ]);
    }

    public function deleting($model): void
    {
        $userId = $model->id;

        AuditLog::where('user_id', $userId)->delete();

        $kelasIds = DB::table('kelas')->where('instruktur_id', $userId)->pluck('id');
        if ($kelasIds->isNotEmpty()) {
            $this->deleteKelasRelations($kelasIds->toArray());
            DB::table('kelas')->whereIn('id', $kelasIds->toArray())->delete();
        }

        DB::table('evaluasi_instruktur')->where('instruktur_id', $userId)->delete();

        $siswa = DB::table('siswa')->where('user_id', $userId)->first();
        if ($siswa) {
            $siswaId = $siswa->id;
            DB::table('enrollment_kelas')->where('siswa_id', $siswaId)->delete();
            DB::table('evaluasi_instruktur')->where('siswa_id', $siswaId)->delete();
            DB::table('kehadiran')->where('siswa_id', $siswaId)->delete();
            DB::table('progress_akademik')->where('siswa_id', $siswaId)->delete();
            DB::table('sertifikat')->where('siswa_id', $siswaId)->delete();
            $pengumpulanIds = DB::table('pengumpulan_tugas')->where('siswa_id', $siswaId)->pluck('id');
            DB::table('pengumpulan_tugas')->where('siswa_id', $siswaId)->delete();
            DB::table('siswa')->where('id', $siswaId)->delete();
        }

        DB::table('tiket_keluhan')->where('pelapor_id', $userId)->delete();
        DB::table('tiket_keluhan')->where('ditangani_oleh', $userId)->delete();
        DB::table('forum_topik')->where('pembuat_id', $userId)->delete();
        DB::table('forum_komentar')->where('user_id', $userId)->delete();
        DB::table('arsip_laporan')->where('dibuat_oleh', $userId)->delete();
        DB::table('notifikasi')->where('user_id', $userId)->delete();
        DB::table('riwayat_status_pendaftaran')->where('diubah_oleh', $userId)->delete();
        
        // --- FIX: Fitur Hapus Pintar & Adaptif untuk Data Peminjaman Aset ---
        if (Schema::hasTable('peminjaman_item_aset')) {
            if (Schema::hasColumn('peminjaman_item_aset', 'peminjam_id')) {
                DB::table('peminjaman_item_aset')->where('peminjam_id', $userId)->delete();
            } elseif (Schema::hasColumn('peminjaman_item_aset', 'user_id')) {
                DB::table('peminjaman_item_aset')->where('user_id', $userId)->delete();
            } else {
                // Jika kolom user tidak ada di tabel detail, cari via tabel induk (peminjaman / peminjaman_aset)
                $tabelInduk = Schema::hasTable('peminjaman') ? 'peminjaman' : (Schema::hasTable('peminjaman_aset') ? 'peminjaman_aset' : null);
                if ($tabelInduk) {
                    $kolomUserInduk = Schema::hasColumn($tabelInduk, 'peminjam_id') ? 'peminjam_id' : 'user_id';
                    
                    // Hapus item detail terlebih dahulu menggunakan subquery
                    DB::table('peminjaman_item_aset')->whereIn('peminjaman_id', function ($query) use ($tabelInduk, $kolomUserInduk, $userId) {
                        $query->select('id')->from($tabelInduk)->where($kolomUserInduk, $userId);
                    })->delete();

                    // Bersihkan juga data di tabel induknya
                    DB::table($tabelInduk)->where($kolomUserInduk, $userId)->delete();
                }
            }
        }

        DB::table('maintenance_aset')->where('dilaporkan_oleh', $userId)->delete();
        DB::table('maintenance_aset')->where('ditangani_oleh', $userId)->delete();
    }

    private function deleteKelasRelations(array $kelasIds): void
    {
        $sesiIds = DB::table('sesi_live')->whereIn('kelas_id', $kelasIds)->pluck('id')->toArray();

        if (! empty($sesiIds)) {
            DB::table('kehadiran')->whereIn('sesi_id', $sesiIds)->delete();
            DB::table('materi_pembelajaran')->whereIn('sesi_id', $sesiIds)->delete();

            $tugasIds = DB::table('tugas')->whereIn('sesi_id', $sesiIds)->pluck('id')->toArray();
            if (! empty($tugasIds)) {
                DB::table('pengumpulan_tugas')->whereIn('tugas_id', $tugasIds)->delete();
                DB::table('tugas')->whereIn('id', $tugasIds)->delete();
            }

            DB::table('sesi_live')->whereIn('id', $sesiIds)->delete();
        }

        DB::table('enrollment_kelas')->whereIn('kelas_id', $kelasIds)->delete();
        DB::table('evaluasi_instruktur')->whereIn('kelas_id', $kelasIds)->delete();
        DB::table('progress_akademik')->whereIn('kelas_id', $kelasIds)->delete();
        DB::table('sertifikat')->whereIn('kelas_id', $kelasIds)->delete();

        $topikIds = DB::table('forum_topik')->whereIn('kelas_id', $kelasIds)->pluck('id')->toArray();
        if (! empty($topikIds)) {
            DB::table('forum_komentar')->whereIn('topik_id', $topikIds)->delete();
            DB::table('forum_topik')->whereIn('id', $topikIds)->delete();
        }
    }
}