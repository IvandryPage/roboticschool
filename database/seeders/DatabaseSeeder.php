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
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // =============================================
        // 1. ROLES
        // =============================================
        $roles = [
            ['nama_role' => 'Admin Akademik', 'deskripsi' => 'Mengelola data akademik dan sertifikat'],
            ['nama_role' => 'Instruktur',     'deskripsi' => 'Mengajar dan mengelola kelas'],
            ['nama_role' => 'Siswa',          'deskripsi' => 'Peserta program kursus'],
            ['nama_role' => 'Tim Publikasi',  'deskripsi' => 'Mengelola konten publik'],
            ['nama_role' => 'Direktur',       'deskripsi' => 'Melihat laporan dan statistik keseluruhan'],
        ];
        foreach ($roles as $r) {
            Role::firstOrCreate(['nama_role' => $r['nama_role']], $r);
        }

        $roleAdmin      = Role::where('nama_role', 'Admin Akademik')->first();
        $roleInstruktur = Role::where('nama_role', 'Instruktur')->first();
        $roleSiswa      = Role::where('nama_role', 'Siswa')->first();
        $roleDirektur   = Role::where('nama_role', 'Direktur')->first();

        // =============================================
        // 2. USERS (Admin, Direktur, Instruktur, Siswa)
        // =============================================
        $admin = User::firstOrCreate(['email' => 'admin@robotik.test'], [
            'nama_lengkap' => 'Admin Akademik',
            'name'         => 'admin',
            'password'     => Hash::make('password'),
            'role_id'      => $roleAdmin?->id,
            'status_aktif' => true,
        ]);

        $direktur = User::firstOrCreate(['email' => 'direktur@robotik.test'], [
            'nama_lengkap' => 'Dr. Budi Santoso',
            'name'         => 'direktur',
            'password'     => Hash::make('password'),
            'role_id'      => $roleDirektur?->id,
            'status_aktif' => true,
        ]);

        $instruktur1 = User::firstOrCreate(['email' => 'instruktur1@robotik.test'], [
            'nama_lengkap' => 'Andi Prasetyo, S.T.',
            'name'         => 'andi_instruktur',
            'password'     => Hash::make('password'),
            'role_id'      => $roleInstruktur?->id,
            'status_aktif' => true,
        ]);

        $instruktur2 = User::firstOrCreate(['email' => 'instruktur2@robotik.test'], [
            'nama_lengkap' => 'Siti Rahayu, M.Kom.',
            'name'         => 'siti_instruktur',
            'password'     => Hash::make('password'),
            'role_id'      => $roleInstruktur?->id,
            'status_aktif' => true,
        ]);

        // Data siswa dengan nama lengkap realistis
        $dataSiswa = [
            ['email' => 'budi@siswa.test',    'nama' => 'Budi Setiawan',      'username' => 'budi_siswa'],
            ['email' => 'dewi@siswa.test',     'nama' => 'Dewi Ratnasari',     'username' => 'dewi_siswa'],
            ['email' => 'fajar@siswa.test',    'nama' => 'Fajar Nugroho',      'username' => 'fajar_siswa'],
            ['email' => 'gina@siswa.test',     'nama' => 'Gina Maharani',      'username' => 'gina_siswa'],
            ['email' => 'hendra@siswa.test',   'nama' => 'Hendra Kusuma',      'username' => 'hendra_siswa'],
            ['email' => 'indah@siswa.test',    'nama' => 'Indah Permatasari',  'username' => 'indah_siswa'],
            ['email' => 'joko@siswa.test',     'nama' => 'Joko Widodo',        'username' => 'joko_siswa'],
            ['email' => 'kartini@siswa.test',  'nama' => 'Kartini Wulandari',  'username' => 'kartini_siswa'],
        ];

        $usersSiswa = [];
        foreach ($dataSiswa as $d) {
            $usersSiswa[] = User::firstOrCreate(['email' => $d['email']], [
                'nama_lengkap' => $d['nama'],
                'name'         => $d['username'],
                'password'     => Hash::make('password'),
                'role_id'      => $roleSiswa?->id,
                'status_aktif' => true,
            ]);
        }

        // =============================================
        // 3. PROGRAM KURSUS
        // =============================================
        $programRobot = ProgramKursus::firstOrCreate(['nama_program' => 'Robotika Dasar'], [
            'deskripsi'      => 'Program pengenalan robotika untuk pemula. Mencakup dasar elektronik, pemrograman Arduino, dan mekanika robot sederhana.',
            'level'          => 'Pemula',
            'biaya'          => 1500000,
            'durasi_minggu'  => 12,
            'status_tampil'  => true,
        ]);

        $programIoT = ProgramKursus::firstOrCreate(['nama_program' => 'Internet of Things (IoT)'], [
            'deskripsi'      => 'Program lanjutan menghubungkan perangkat fisik ke internet menggunakan sensor dan mikrokontroler.',
            'level'          => 'Menengah',
            'biaya'          => 2000000,
            'durasi_minggu'  => 16,
            'status_tampil'  => true,
        ]);

        $programAI = ProgramKursus::firstOrCreate(['nama_program' => 'AI & Machine Learning untuk Robot'], [
            'deskripsi'      => 'Program advanced menggabungkan kecerdasan buatan dengan sistem robotika.',
            'level'          => 'Lanjutan',
            'biaya'          => 3500000,
            'durasi_minggu'  => 20,
            'status_tampil'  => true,
        ]);

        // =============================================
        // 4. BATCH
        // =============================================
        $batch1 = Batch::firstOrCreate(['nama_batch' => 'Batch 1 - 2026'], [
            'program_id'     => $programRobot->id,
            'tanggal_mulai'  => '2026-01-15',
            'tanggal_selesai'=> '2026-04-15',
            'kuota_max'      => 20,
            'status_aktif'   => true,
        ]);

        $batch2 = Batch::firstOrCreate(['nama_batch' => 'Batch 2 - 2026'], [
            'program_id'     => $programIoT->id,
            'tanggal_mulai'  => '2026-02-01',
            'tanggal_selesai'=> '2026-06-01',
            'kuota_max'      => 15,
            'status_aktif'   => true,
        ]);

        $batch3 = Batch::firstOrCreate(['nama_batch' => 'Batch 3 - 2026'], [
            'program_id'     => $programAI->id,
            'tanggal_mulai'  => '2026-03-01',
            'tanggal_selesai'=> '2026-07-31',
            'kuota_max'      => 10,
            'status_aktif'   => true,
        ]);

        // =============================================
        // 5. KELAS
        // =============================================
        $kelas1 = Kelas::firstOrCreate(['nama_kelas' => 'Kelas Robotika A - Pagi'], [
            'batch_id'      => $batch1->id,
            'instruktur_id' => $instruktur1->id,
            'kapasitas'     => 10,
            'status'        => 'Selesai',
        ]);

        $kelas2 = Kelas::firstOrCreate(['nama_kelas' => 'Kelas Robotika B - Sore'], [
            'batch_id'      => $batch1->id,
            'instruktur_id' => $instruktur2->id,
            'kapasitas'     => 10,
            'status'        => 'Selesai',
        ]);

        $kelas3 = Kelas::firstOrCreate(['nama_kelas' => 'Kelas IoT Intensif'], [
            'batch_id'      => $batch2->id,
            'instruktur_id' => $instruktur1->id,
            'kapasitas'     => 15,
            'status'        => 'Aktif',
        ]);

        // =============================================
        // 6. SISWA (profil)
        // =============================================
        $profileSiswa = [];
        $jenisKelamin  = ['L', 'P', 'L', 'P', 'L', 'P', 'L', 'P'];
        foreach ($usersSiswa as $i => $userSiswa) {
            $profileSiswa[] = Siswa::firstOrCreate(['user_id' => $userSiswa->id], [
                'tanggal_lahir' => now()->subYears(rand(16, 22))->format('Y-m-d'),
                'jenis_kelamin' => $jenisKelamin[$i],
                'alamat'        => 'Jl. Contoh No. ' . ($i + 1) . ', Kota Bandung, Jawa Barat',
            ]);
        }

        // =============================================
        // 7. ENROLLMENT KELAS
        // Kelas 1 & 2: Selesai (untuk sertifikat)
        // Kelas 3: Aktif (untuk dashboard instruktur)
        // =============================================

        // Kelas 1 — 4 siswa, sudah Selesai
        $enrollKelas1 = [];
        foreach (array_slice($profileSiswa, 0, 4) as $siswa) {
            $enrollKelas1[] = EnrollmentKelas::firstOrCreate(
                ['kelas_id' => $kelas1->id, 'siswa_id' => $siswa->id],
                ['tanggal_bergabung' => '2026-01-15', 'status' => 'Selesai']
            );
        }

        // Kelas 2 — 4 siswa, sudah Selesai
        $enrollKelas2 = [];
        foreach (array_slice($profileSiswa, 4, 4) as $siswa) {
            $enrollKelas2[] = EnrollmentKelas::firstOrCreate(
                ['kelas_id' => $kelas2->id, 'siswa_id' => $siswa->id],
                ['tanggal_bergabung' => '2026-01-15', 'status' => 'Selesai']
            );
        }

        // Kelas 3 — semua 8 siswa, masih Aktif
        foreach ($profileSiswa as $siswa) {
            EnrollmentKelas::firstOrCreate(
                ['kelas_id' => $kelas3->id, 'siswa_id' => $siswa->id],
                ['tanggal_bergabung' => '2026-02-01', 'status' => 'Aktif']
            );
        }

        // =============================================
        // 8. PROGRESS AKADEMIK
        // PBI-121: Syarat Kelulusan:
        //   - Kehadiran >= 75%
        //   - Nilai rata-rata >= 70
        //
        // Data testing:
        //   - 3 siswa MEMENUHI syarat (layak sertifikat)
        //   - 1 siswa TIDAK memenuhi syarat (kehadiran kurang)
        // =============================================

        // Progress siswa Kelas 1 (4 siswa)
        $progressDataKelas1 = [
            // [persentase_kehadiran, rata_nilai_tugas, persentase_penyelesaian, status]
            [92.5, 88.0, 100.0, 'Lulus'],   // Budi — LULUS ✅
            [85.0, 76.5, 100.0, 'Lulus'],   // Dewi — LULUS ✅
            [78.0, 72.0, 100.0, 'Lulus'],   // Fajar — LULUS ✅ (pas batas minimal)
            [60.0, 65.0, 80.0,  'Remedial'],// Gina — TIDAK LULUS ❌ (kehadiran & nilai kurang)
        ];

        foreach ($enrollKelas1 as $idx => $enroll) {
            $pd = $progressDataKelas1[$idx];
            ProgressAkademik::firstOrCreate(
                ['siswa_id' => $enroll->siswa_id, 'kelas_id' => $kelas1->id],
                [
                    'persentase_kehadiran'    => $pd[0],
                    'rata_nilai_tugas'        => $pd[1],
                    'persentase_penyelesaian' => $pd[2],
                    'status'                  => $pd[3],
                ]
            );
        }

        // Progress siswa Kelas 2 (4 siswa)
        $progressDataKelas2 = [
            [95.0, 91.0, 100.0, 'Lulus'],   // Hendra — LULUS ✅
            [80.0, 83.5, 100.0, 'Lulus'],   // Indah — LULUS ✅
            [45.0, 55.0, 60.0,  'Remedial'],// Joko — TIDAK LULUS ❌ (banyak absen)
            [88.0, 79.0, 100.0, 'Lulus'],   // Kartini — LULUS ✅
        ];

        foreach ($enrollKelas2 as $idx => $enroll) {
            $pd = $progressDataKelas2[$idx];
            ProgressAkademik::firstOrCreate(
                ['siswa_id' => $enroll->siswa_id, 'kelas_id' => $kelas2->id],
                [
                    'persentase_kehadiran'    => $pd[0],
                    'rata_nilai_tugas'        => $pd[1],
                    'persentase_penyelesaian' => $pd[2],
                    'status'                  => $pd[3],
                ]
            );
        }

        // =============================================
        // 9. EVALUASI INSTRUKTUR (untuk Dashboard Instruktur)
        // =============================================
        $evalData = [
            ['skor' => 4.5, 'saran' => 'Penjelasan sangat jelas dan mudah dipahami. Instruktur sangat sabar.'],
            ['skor' => 4.0, 'saran' => 'Materi disampaikan dengan baik, mungkin bisa lebih banyak praktik.'],
            ['skor' => 3.8, 'saran' => 'Cukup baik, tapi kadang terlalu cepat dalam menjelaskan.'],
            ['skor' => 4.8, 'saran' => 'Sangat bagus! Instruktur aktif membantu saat kesulitan.'],
        ];

        foreach ($enrollKelas1 as $idx => $enroll) {
            $ed = $evalData[$idx];
            EvaluasiInstruktur::firstOrCreate(
                ['kelas_id' => $kelas1->id, 'siswa_id' => $enroll->siswa_id],
                [
                    'instruktur_id'     => $kelas1->instruktur_id,
                    'skor_rata_rata'    => $ed['skor'],
                    'saran_ulasan'      => $ed['saran'],
                    'jawaban_kuesioner' => json_encode([
                        'kejelasan_materi'   => rand(3, 5),
                        'interaktivitas'     => rand(3, 5),
                        'ketepatan_waktu'    => rand(3, 5),
                        'relevansi_konten'   => rand(3, 5),
                    ]),
                ]
            );
        }

        // =============================================
        // 10. ARSIP LAPORAN (PBI-139 & 140)
        // =============================================
        $laporanData = [
            [
                'judul'        => 'Laporan Kelulusan Batch 1 Robotika Dasar 2026',
                'tipe_laporan' => 'laporan_kelulusan',
                'periode'      => '2026-04',
                'catatan'      => 'Total 6 dari 8 siswa berhasil lulus dengan nilai rata-rata 82.5. Dua siswa perlu remedial.',
            ],
            [
                'judul'        => 'Laporan Akademik Bulanan — April 2026',
                'tipe_laporan' => 'laporan_akademik',
                'periode'      => '2026-04',
                'catatan'      => 'Rekap kehadiran dan nilai seluruh kelas aktif bulan April 2026.',
            ],
            [
                'judul'        => 'Laporan Instruktur — Evaluasi Kinerja Q1 2026',
                'tipe_laporan' => 'laporan_instruktur',
                'periode'      => 'Q1-2026',
                'catatan'      => 'Rata-rata skor instruktur: 4.3/5.0. Kedua instruktur mendapat feedback positif.',
            ],
            [
                'judul'        => 'Laporan Bulanan Program IoT — Mei 2026',
                'tipe_laporan' => 'laporan_bulanan',
                'periode'      => '2026-05',
                'catatan'      => 'Progress kelas IoT Intensif: 75% materi telah tersampaikan.',
            ],
        ];

        foreach ($laporanData as $laporan) {
            ArsipLaporan::firstOrCreate(
                ['judul' => $laporan['judul']],
                array_merge($laporan, ['dibuat_oleh' => $admin->id])
            );
        }

        // =============================================
        // 11. ASET ROBOTIK
        // =============================================
        if (!AsetRobotik::where('kode_aset', 'ARD-001')->exists()) {
            $aset1 = AsetRobotik::create([
                'kode_aset'    => 'ARD-001',
                'nama_kit'     => 'Arduino Starter Kit',
                'deskripsi'    => 'Kit pemula Arduino Uno dengan komponen dasar',
                'kategori'     => 'Mikrokontroler',
                'stok_minimal' => 3,
            ]);
            ItemKitRobotik::factory()->count(5)->create(['aset_id' => $aset1->id]);
        }

        if (!AsetRobotik::where('kode_aset', 'ESP-001')->exists()) {
            $aset2 = AsetRobotik::create([
                'kode_aset'    => 'ESP-001',
                'nama_kit'     => 'ESP32 IoT Kit',
                'deskripsi'    => 'Kit IoT berbasis ESP32 dengan WiFi & Bluetooth',
                'kategori'     => 'IoT',
                'stok_minimal' => 2,
            ]);
            ItemKitRobotik::factory()->count(3)->create(['aset_id' => $aset2->id]);
        }

        $this->command->info('✅ Seeder selesai! Akun testing:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin Akademik', 'admin@robotik.test',      'password'],
                ['Direktur',       'direktur@robotik.test',   'password'],
                ['Instruktur 1',   'instruktur1@robotik.test','password'],
                ['Instruktur 2',   'instruktur2@robotik.test','password'],
                ['Siswa 1',        'budi@siswa.test',         'password'],
                ['Siswa 2',        'dewi@siswa.test',         'password'],
            ]
        );
        $this->command->info('');
        $this->command->info('📋 Data Testing Syarat Kelulusan:');
        $this->command->table(
            ['Siswa', 'Kelas', 'Kehadiran', 'Nilai', 'Status', 'Layak Sertifikat?'],
            [
                ['Budi Setiawan',     'Robotika A', '92.5%', '88.0', 'Lulus',    '✅ YA'],
                ['Dewi Ratnasari',    'Robotika A', '85.0%', '76.5', 'Lulus',    '✅ YA'],
                ['Fajar Nugroho',     'Robotika A', '78.0%', '72.0', 'Lulus',    '✅ YA'],
                ['Gina Maharani',     'Robotika A', '60.0%', '65.0', 'Remedial', '❌ TIDAK (kehadiran & nilai kurang)'],
                ['Hendra Kusuma',     'Robotika B', '95.0%', '91.0', 'Lulus',    '✅ YA'],
                ['Indah Permatasari', 'Robotika B', '80.0%', '83.5', 'Lulus',    '✅ YA'],
                ['Joko Widodo',       'Robotika B', '45.0%', '55.0', 'Remedial', '❌ TIDAK (banyak absen)'],
                ['Kartini Wulandari', 'Robotika B', '88.0%', '79.0', 'Lulus',    '✅ YA'],
            ]
        );
    }
}
