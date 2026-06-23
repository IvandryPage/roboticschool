<?php

namespace Database\Seeders;

use App\Models\ArsipLaporan;
use App\Models\AsetRobotik;
use App\Models\Batch;
use App\Models\EnrollmentKelas;
use App\Models\EvaluasiInstruktur;
use App\Models\ItemKitRobotik;
use App\Models\Kelas;
use App\Models\ProgressAkademik;
use App\Models\ProgramKursus;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1) Roles
        $roles = [
            ['nama_role' => 'Admin Akademik'],
            ['nama_role' => 'Instruktur'],
            ['nama_role' => 'Siswa'],
            ['nama_role' => 'Tim Publikasi'],
            ['nama_role' => 'Direktur'],
        ];
        foreach ($roles as $r) {
            Role::firstOrCreate(['nama_role' => $r['nama_role']], $r);
        }

        $adminRole = Role::where(['nama_role' => 'Admin Akademik'])->first();
        $instrukturRole = Role::where(['nama_role' => 'Instruktur'])->first();
        $siswaRole = Role::where(['nama_role' => 'Siswa'])->first();
        $publikasiRole = Role::where(['nama_role' => 'Tim Publikasi'])->first();
        $direkturRole = Role::where(['nama_role' => 'Direktur'])->first();

        // 2) Users (admin, instructors, director, publikasi)
        $admin = User::firstOrCreate(
            ['email' => 'admin@robonesia.test'],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => 'Administrator RoboNesia',
                'name' => 'admin',
                'email' => 'admin@robonesia.test',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole?->id,
                'status_aktif' => true,
            ]
        );

        $instruktur1 = User::firstOrCreate(
            ['email' => 'instruktur1@robonesia.test'],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => 'Dina Prasetya',
                'name' => 'dina.instruktur',
                'email' => 'instruktur1@robonesia.test',
                'password' => Hash::make('password'),
                'role_id' => $instrukturRole?->id,
                'status_aktif' => true,
            ]
        );

        $instruktur2 = User::firstOrCreate(
            ['email' => 'instruktur2@robonesia.test'],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => 'Rudi Hartono',
                'name' => 'rudi.instruktur',
                'email' => 'instruktur2@robonesia.test',
                'password' => Hash::make('password'),
                'role_id' => $instrukturRole?->id,
                'status_aktif' => true,
            ]
        );

        $publikasi = User::firstOrCreate(
            ['email' => 'publikasi@robonesia.test'],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => 'Andi Publikasi',
                'name' => 'andi.pub',
                'email' => 'publikasi@robonesia.test',
                'password' => Hash::make('password'),
                'role_id' => $publikasiRole?->id,
                'status_aktif' => true,
            ]
        );

        $direktur = User::firstOrCreate(
            ['email' => 'direktur@robonesia.test'],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => 'Budi Direktur',
                'name' => 'budi.direktur',
                'email' => 'direktur@robonesia.test',
                'password' => Hash::make('password'),
                'role_id' => $direkturRole?->id,
                'status_aktif' => true,
            ]
        );

        // 3) Students (users + profiles)
        $dataSiswa = [
            ['email' => 'budi@siswa.test',    'nama' => 'Budi Setiawan',     'username' => 'budi_siswa'],
            ['email' => 'dewi@siswa.test',    'nama' => 'Dewi Ratnasari',   'username' => 'dewi_siswa'],
            ['email' => 'fajar@siswa.test',   'nama' => 'Fajar Nugroho',    'username' => 'fajar_siswa'],
            ['email' => 'gina@siswa.test',    'nama' => 'Gina Maharani',    'username' => 'gina_siswa'],
            ['email' => 'hendra@siswa.test',  'nama' => 'Hendra Kusuma',    'username' => 'hendra_siswa'],
            ['email' => 'indah@siswa.test',   'nama' => 'Indah Permatasari','username' => 'indah_siswa'],
            ['email' => 'joko@siswa.test',    'nama' => 'Joko Widodo',      'username' => 'joko_siswa'],
            ['email' => 'kartini@siswa.test', 'nama' => 'Kartini Wulandari','username' => 'kartini_siswa'],
        ];

        $usersSiswa = [];
        foreach ($dataSiswa as $d) {
            $u = User::firstOrCreate(
                ['email' => $d['email']],
                [
                    'id' => (string) Str::uuid(),
                    'nama_lengkap' => $d['nama'],
                    'name' => $d['username'],
                    'email' => $d['email'],
                    'password' => Hash::make('password'),
                    'role_id' => $siswaRole?->id,
                    'status_aktif' => true,
                ]
            );
            $usersSiswa[] = $u;

            // create Siswa profile
            Siswa::firstOrCreate(
                ['user_id' => $u->id],
                [
                    'id' => (string) Str::uuid(),
                    'user_id' => $u->id,
                    'tanggal_lahir' => now()->subYears(rand(16, 22))->format('Y-m-d'),
                    'jenis_kelamin' => (rand(0, 1) ? 'L' : 'P'),
                    'alamat' => 'Jl. Contoh No. ' . rand(1, 100) . ', Bandung',
                ]
            );
        }

        // 4) Programs & Batches
        $programRobot = ProgramKursus::firstOrCreate(
            ['nama_program' => 'Robotika Dasar'],
            [
                'id' => (string) Str::uuid(),
                'deskripsi' => 'Program pengenalan robotika untuk pemula.',
                'level' => 'Pemula',
                'biaya' => 1500000,
                'durasi_minggu' => 12,
                'status_tampil' => true,
            ]
        );

        $programIoT = ProgramKursus::firstOrCreate(
            ['nama_program' => 'Internet of Things (IoT)'],
            [
                'id' => (string) Str::uuid(),
                'deskripsi' => 'Program lanjutan menghubungkan perangkat fisik ke internet.',
                'level' => 'Menengah',
                'biaya' => 2000000,
                'durasi_minggu' => 16,
                'status_tampil' => true,
            ]
        );

        $batch1 = Batch::firstOrCreate(
            ['nama_batch' => 'Batch 1 - 2026'],
            [
                'id' => (string) Str::uuid(),
                'program_id' => $programRobot->id,
                'tanggal_mulai' => '2026-01-15',
                'tanggal_selesai' => '2026-04-15',
                'kuota_max' => 20,
                'status_aktif' => true,
            ]
        );

        $batch2 = Batch::firstOrCreate(
            ['nama_batch' => 'Batch 2 - 2026'],
            [
                'id' => (string) Str::uuid(),
                'program_id' => $programIoT->id,
                'tanggal_mulai' => '2026-02-01',
                'tanggal_selesai' => '2026-06-01',
                'kuota_max' => 15,
                'status_aktif' => true,
            ]
        );

        // 5) Kelas
        $kelas1 = Kelas::firstOrCreate(
            ['nama_kelas' => 'Robotika A - Pagi'],
            [
                'id' => (string) Str::uuid(),
                'batch_id' => $batch1->id,
                'instruktur_id' => $instruktur1->id,
                'kapasitas' => 10,
                'status' => 'Selesai',
            ]
        );

        $kelas2 = Kelas::firstOrCreate(
            ['nama_kelas' => 'Robotika B - Sore'],
            [
                'id' => (string) Str::uuid(),
                'batch_id' => $batch1->id,
                'instruktur_id' => $instruktur2->id,
                'kapasitas' => 10,
                'status' => 'Selesai',
            ]
        );

        $kelas3 = Kelas::firstOrCreate(
            ['nama_kelas' => 'IoT Intensif'],
            [
                'id' => (string) Str::uuid(),
                'batch_id' => $batch2->id,
                'instruktur_id' => $instruktur1->id,
                'kapasitas' => 15,
                'status' => 'Aktif',
            ]
        );

        // 6) Enroll students
        $profiles = Siswa::whereIn('user_id', array_map(fn($u) => $u->id, $usersSiswa))->get();
        $first4 = $profiles->slice(0, 4);
        $next4 = $profiles->slice(4, 4);

        foreach ($first4 as $p) {
            EnrollmentKelas::firstOrCreate(
                ['kelas_id' => $kelas1->id, 'siswa_id' => $p->id],
                ['tanggal_bergabung' => '2026-01-15', 'status' => 'Selesai']
            );
        }
        foreach ($next4 as $p) {
            EnrollmentKelas::firstOrCreate(
                ['kelas_id' => $kelas2->id, 'siswa_id' => $p->id],
                ['tanggal_bergabung' => '2026-01-15', 'status' => 'Selesai']
            );
        }
        foreach ($profiles as $p) {
            EnrollmentKelas::firstOrCreate(
                ['kelas_id' => $kelas3->id, 'siswa_id' => $p->id],
                ['tanggal_bergabung' => '2026-02-01', 'status' => 'Aktif']
            );
        }

        // 7) Progress Akademik (sample)
        $progressDataKelas1 = [
            [92.5, 88.0, 100.0, 'Lulus'],
            [85.0, 76.5, 100.0, 'Lulus'],
            [78.0, 72.0, 100.0, 'Lulus'],
            [60.0, 65.0, 80.0,  'Remedial'],
        ];
        foreach ($first4->values() as $idx => $p) {
            $pd = $progressDataKelas1[$idx] ?? [70,70,70,'Remedial'];
            ProgressAkademik::firstOrCreate(
                ['siswa_id' => $p->id, 'kelas_id' => $kelas1->id],
                [
                    'persentase_kehadiran' => $pd[0],
                    'rata_nilai_tugas' => $pd[1],
                    'persentase_penyelesaian' => $pd[2],
                    'status' => $pd[3],
                ]
            );
        }

        $progressDataKelas2 = [
            [95.0, 91.0, 100.0, 'Lulus'],
            [80.0, 80.0, 80.0, 'Lulus'],
            [80.0, 80.0, 80.0, 'Lulus'],
            [80.0, 80.0, 80.0, 'Lulus'],
        ];
        foreach ($next4->values() as $idx => $p) {
            $pd = $progressDataKelas2[$idx] ?? [80, 80, 80, 'Lulus'];
            ProgressAkademik::firstOrCreate(
                ['siswa_id' => $p->id, 'kelas_id' => $kelas2->id],
                [
                    'persentase_kehadiran' => $pd[0],
                    'rata_nilai_tugas' => $pd[1],
                    'persentase_penyelesaian' => $pd[2],
                    'status' => $pd[3],
                ]
            );
        }

        // 8) Evaluasi instruktur
        $evalData = [4.5, 4.0, 3.8, 4.8];
        foreach ($first4->values() as $idx => $p) {
            EvaluasiInstruktur::firstOrCreate(
                ['kelas_id' => $kelas1->id, 'siswa_id' => $p->id],
                [
                    'instruktur_id' => $kelas1->instruktur_id,
                    'skor_rata_rata' => $evalData[$idx % count($evalData)],
                    'saran_ulasan' => 'Terima kasih atas pengajarannya.',
                ]
            );
        }

        // 9) Arsip laporan
        $laporanData = [
            ['judul' => 'Laporan Kelulusan Batch 1 Robotika Dasar 2026', 'tipe_laporan' => 'laporan_kelulusan', 'periode' => '2026-04', 'catatan' => 'Rekap kelulusan.'],
            ['judul' => 'Laporan Akademik Bulanan — April 2026', 'tipe_laporan' => 'laporan_akademik', 'periode' => '2026-04', 'catatan' => 'Rekap bulanan.'],
        ];
        foreach ($laporanData as $lap) {
            ArsipLaporan::firstOrCreate(['judul' => $lap['judul']], array_merge($lap, ['dibuat_oleh' => $admin->id]));
        }

        // 10) Aset & ItemKit
        $aset = AsetRobotik::firstOrCreate(['kode_aset' => 'ARD-001'], [
            'id' => (string) Str::uuid(),
            'kode_aset' => 'ARD-001',
            'nama_kit' => 'Arduino Starter Kit',
            'deskripsi' => 'Kit pemula Arduino Uno',
            'kategori' => 'Elektronik',
            'stok_minimal' => 3,
        ]);
        ItemKitRobotik::firstOrCreate(['serial_number' => 'SN-ARD-001'], [
            'id' => (string) Str::uuid(),
            'aset_id' => $aset->id,
            'serial_number' => 'SN-ARD-001',
            'status_kondisi' => 'Bagus',
            'lokasi_rak' => 'RAK-A1',
        ]);

        $this->command->info('✅ Seeder selesai. Akun admin: admin@robonesia.test / admin123');
    }
}