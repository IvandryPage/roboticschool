<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AuditObserver
{
    public function updated($model)
    {
        if (! Auth::check()) return;

        AuditLog::create([
            'user_id'      => Auth::id(),
            'aksi'         => 'Update / Verifikasi',
            'entity_type'  => get_class($model),
            'entity_id'    => $model->id,
            'data_sebelum' => json_encode($model->getOriginal()),
            'data_sesudah' => json_encode($model->getChanges()),
            'ip_address'   => Request::ip(),
        ]);
    }

    public function deleting($model)
    {
        $userId = $model->id;

        // 1. Audit logs
        AuditLog::where('user_id', $userId)->delete();

        // 2. Relasi sebagai instruktur — cascade hapus kelas beserta semua relasinya
        $kelasIds = DB::table('kelas')->where('instruktur_id', $userId)->pluck('id');
        if ($kelasIds->isNotEmpty()) {
            $this->deleteKelasRelations($kelasIds->toArray());
            DB::table('kelas')->whereIn('id', $kelasIds->toArray())->delete();
        }

        DB::table('evaluasi_instruktur')->where('instruktur_id', $userId)->delete();

        // 3. Relasi sebagai siswa
        $siswa = DB::table('siswa')->where('user_id', $userId)->first();
        if ($siswa) {
            $siswaId = $siswa->id;
            DB::table('enrollment_kelas')->where('siswa_id', $siswaId)->delete();
            DB::table('evaluasi_instruktur')->where('siswa_id', $siswaId)->delete();
            DB::table('kehadiran')->where('siswa_id', $siswaId)->delete();
            DB::table('progress_akademik')->where('siswa_id', $siswaId)->delete();
            DB::table('sertifikat')->where('siswa_id', $siswaId)->delete();

            // Pengumpulan tugas
            $pengumpulanIds = DB::table('pengumpulan_tugas')->where('siswa_id', $siswaId)->pluck('id');
            DB::table('pengumpulan_tugas')->where('siswa_id', $siswaId)->delete();

            DB::table('siswa')->where('id', $siswaId)->delete();
        }

        // 4. Relasi lain langsung ke users
        DB::table('tiket_keluhan')->where('pelapor_id', $userId)->delete();
        DB::table('tiket_keluhan')->where('ditangani_oleh', $userId)->delete();
        DB::table('forum_topik')->where('pembuat_id', $userId)->delete();
        DB::table('forum_komentar')->where('user_id', $userId)->delete();
        DB::table('arsip_laporan')->where('dibuat_oleh', $userId)->delete();
        DB::table('notifikasi')->where('user_id', $userId)->delete();
        DB::table('riwayat_status_pendaftaran')->where('diubah_oleh', $userId)->delete();
        DB::table('peminjaman_item_aset')->where('peminjam_id', $userId)->delete();
        DB::table('maintenance_aset')->where('dilaporkan_oleh', $userId)->delete();
        DB::table('maintenance_aset')->where('ditangani_oleh', $userId)->delete();
    }

    private function deleteKelasRelations(array $kelasIds): void
    {
        // Ambil sesi_live ids
        $sesiIds = DB::table('sesi_live')->whereIn('kelas_id', $kelasIds)->pluck('id')->toArray();

        if (!empty($sesiIds)) {
            // Hapus relasi sesi
            DB::table('kehadiran')->whereIn('sesi_id', $sesiIds)->delete();
            DB::table('materi_pembelajaran')->whereIn('sesi_id', $sesiIds)->delete();

            // Tugas dan pengumpulannya
            $tugasIds = DB::table('tugas')->whereIn('sesi_id', $sesiIds)->pluck('id')->toArray();
            if (!empty($tugasIds)) {
                DB::table('pengumpulan_tugas')->whereIn('tugas_id', $tugasIds)->delete();
                DB::table('tugas')->whereIn('id', $tugasIds)->delete();
            }

            DB::table('sesi_live')->whereIn('id', $sesiIds)->delete();
        }

        // Hapus relasi kelas lainnya
        DB::table('enrollment_kelas')->whereIn('kelas_id', $kelasIds)->delete();
        DB::table('evaluasi_instruktur')->whereIn('kelas_id', $kelasIds)->delete();
        DB::table('progress_akademik')->whereIn('kelas_id', $kelasIds)->delete();
        DB::table('sertifikat')->whereIn('kelas_id', $kelasIds)->delete();

        $topikIds = DB::table('forum_topik')->whereIn('kelas_id', $kelasIds)->pluck('id')->toArray();
        if (!empty($topikIds)) {
            DB::table('forum_komentar')->whereIn('topik_id', $topikIds)->delete();
            DB::table('forum_topik')->whereIn('id', $topikIds)->delete();
        }
    }

    public function deleted($model)
    {
        if (! Auth::check()) return;

        AuditLog::create([
            'user_id'      => Auth::id(),
            'aksi'         => 'Delete Data',
            'entity_type'  => get_class($model),
            'entity_id'    => $model->id,
            'data_sebelum' => json_encode($model->getOriginal()),
            'data_sesudah' => json_encode([]),
            'ip_address'   => Request::ip(),
        ]);
    }
}
