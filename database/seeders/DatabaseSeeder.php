<?php

namespace Database\Seeders;

use App\Models\AsetRobotik;
use App\Models\ArsipLaporan;
use App\Models\AuditLog;
use App\Models\Batch;
use App\Models\CalonPeserta;
use App\Models\DokumenPendaftaran;
use App\Models\EnrollmentKelas;
use App\Models\EvaluasiInstruktur;
use App\Models\ForumKomentar;
use App\Models\ForumTopik;
use App\Models\Invoice;
use App\Models\ItemKitRobotik;
use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\MaintenanceAset;
use App\Models\MateriPembelajaran;
use App\Models\MateriProgram;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\PeminjamanItemAset;
use App\Models\Pendaftaran;
use App\Models\PengumpulanTugas;
use App\Models\ProgramKursus;
use App\Models\ProgressAkademik;
use App\Models\RiwayatStatusPendaftaran;
use App\Models\Role;
use App\Models\Sertifikat;
use App\Models\SesiLive;
use App\Models\Siswa;
use App\Models\TiketKeluhan;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles
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

        // Create an admin user with known password
        $adminRole = Role::where('nama_role', 'Admin Akademik')->first();

        User::factory()->create([
            'nama_lengkap' => 'Administrator RoboNesia',
            'name' => 'admin',
            'email' => 'admin@robonesia.test',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole?->id,
        ]);

        // Create a few named users for roles
        $instrukturRole = Role::where('nama_role', 'Instruktur')->first();
        $publikasiRole = Role::where('nama_role', 'Tim Publikasi')->first();
        $siswaRole = Role::where('nama_role', 'Siswa')->first();

        User::factory()->create([
            'nama_lengkap' => 'Dina Prasetya',
            'email' => 'dina.instruktur@robonesia.test',
            'role_id' => $instrukturRole?->id,
        ]);

        User::factory()->create([
            'nama_lengkap' => 'Rudi Hartono',
            'email' => 'rudi.pub@robonesia.test',
            'role_id' => $publikasiRole?->id,
        ]);

        User::factory()->create([
            'nama_lengkap' => 'Sinta Mahesa',
            'email' => 'sinta.siswa@robonesia.test',
            'role_id' => $siswaRole?->id,
        ]);

        // Create explicit demo programs with realistic names and pricing
        $programNames = [
            ['nama' => 'Arduino Basic', 'level' => 'Pemula', 'biaya' => 450000, 'deskripsi' => 'Perkenalan Arduino, wiring, dan pemrograman C++.'],
            ['nama' => 'IoT Development', 'level' => 'Menengah', 'biaya' => 2450000, 'deskripsi' => 'Membangun proyek IoT dengan sensor dan cloud.'],
            ['nama' => 'Robotics for Kids', 'level' => 'Pemula', 'biaya' => 750000, 'deskripsi' => 'Kursus robotika ramah anak dengan kit edukasi.' ],
            ['nama' => 'Advanced Robotics', 'level' => 'Lanjutan', 'biaya' => 5000000, 'deskripsi' => 'Kendali adaptif, ROS, dan visi komputer.' ],
            ['nama' => 'Microcontroller C', 'level' => 'Menengah', 'biaya' => 1250000, 'deskripsi' => 'Pemrograman mikrokontroler dengan C/C++.'],
        ];

        $programs = collect();
        foreach ($programNames as $p) {
            $created = ProgramKursus::factory()->create([
                'nama_program' => $p['nama'],
                'deskripsi' => $p['deskripsi'],
                'level' => $p['level'],
                'biaya' => $p['biaya'],
            ]);
            // create batches for program
            Batch::factory()->count(3)->create(['program_id' => $created->id]);
            $programs->push($created);
        }

        // Seed users and students
        User::factory()->count(8)->create();
        Siswa::factory()->count(6)->create();

        // collect helper sets
        $allUsers = User::all();
        $allSiswa = Siswa::all();
        $allBatches = Batch::all();

        // Seed assets and item kits
        $asets = AsetRobotik::factory()->count(4)->create();
        foreach ($asets as $aset) {
            ItemKitRobotik::factory()->count(4)->create(['aset_id' => $aset->id]);
        }
        $itemkits = ItemKitRobotik::all();

        // Seed other resources
        // Ensure we have instructors to assign
        $instructors = $instrukturRole ? User::where('role_id', $instrukturRole->id)->get() : collect();
        if ($instructors->isEmpty()) {
            $instructors = User::factory()->count(3)->create(['role_id' => $instrukturRole?->id]);
        }

        // Create classes (Kelas) linked to random batches and instructors
        $kelasList = collect();
        for ($i = 0; $i < 5; $i++) {
            $kelasList->push(
                Kelas::factory()->create([
                    'batch_id' => $allBatches->random()->id,
                    'instruktur_id' => $instructors->random()->id,
                ])
            );
        }

        foreach($programs as $program) {
            for ($m = 1; $m <= 3; $m++) {
                // MateriProgram::create([
                //     'id' => (string) Str::uuid(),
                //     'program_id' => $program->id,
                //     'nomor_urut' => $m,
                //     'judul_materi' => $program->nama_program . ' - Materi ' . $m,
                //     'deskripsi_materi' => 'Deskripsi singkat untuk ' . $program->nama_program . ' materi ' . $m,
                // ]);
                MateriProgram::firstOrCreate(
                    [
                        'program_id' => $program->id,
                        'nomor_urut' => $m,
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'judul_materi' => $program->nama_program . ' - Materi ' . $m,
                        'deskripsi_materi' => 'Deskripsi singkat untuk ' . $program->nama_program . ' materi ' . $m,
                    ]
                );
            }
        }

        // Create sessions attached to classes
        $sesiList = collect();
        foreach ($kelasList as $kelas) {
            $sesiList = $sesiList->merge(SesiLive::factory()->count(2)->create(['kelas_id' => $kelas->id]));
        }

        if ($sesiList->isNotEmpty()) {
            foreach ($sesiList as $sesi) {
                $sesiId = is_object($sesi) ? $sesi->id : $sesi;
                MateriPembelajaran::factory()->create(['sesi_id' => $sesiId]);
            }
        }

        // Enrollment, progress, and related academic records
        // Enroll students into random classes (1-3 per student)
        foreach ($allSiswa as $s) {
            $toEnroll = $kelasList->random(rand(1, min(3, $kelasList->count())));
            if ($toEnroll instanceof \Illuminate\Support\Collection) {
                foreach ($toEnroll as $k) {
                    EnrollmentKelas::factory()->create(['siswa_id' => $s->id, 'kelas_id' => $k->id]);
                }
            } else {
                EnrollmentKelas::factory()->create(['siswa_id' => $s->id, 'kelas_id' => $toEnroll->id]);
            }
        }

        // Academic progress and attendance linked to existing siswa/program/kelas
        foreach ($allSiswa as $s) {
            ProgressAkademik::factory()->create([
                'siswa_id' => $s->id,
                'kelas_id' => $kelasList->random()->id,
            ]);
        }

        // Create attendances tied to enrollments
        // Kehadiran::factory()->count(8)->create();

        // Calon peserta and related docs
        $calons = CalonPeserta::factory()->count(5)->create();

        // Financial and administrative: create pendaftaran for each calon and related invoice/payments
        $pendaftaranList = collect();
        foreach ($calons as $calon) {
            // ensure we have at least one program available
            if ($programs->isEmpty()) {
                $programs = ProgramKursus::factory()->count(1)::create();
            }

            $randProgram = $programs->random();
            $programId = is_object($randProgram) ? $randProgram->id : $randProgram;

            $pd = Pendaftaran::factory()->create([
                'calon_peserta_id' => $calon->id,
                'program_id' => $programId,
            ]);
            $pendaftaranList->push($pd);

            // create invoice for this pendaftaran
            $totalTagihan = is_object($randProgram) ? $randProgram->biaya : ProgramKursus::find($programId)->biaya;

            $inv = Invoice::factory()->create([
                'pendaftaran_id' => $pd->id,
                'total_tagihan' => $totalTagihan,
            ]);

            // Assign payment state systematically to ensure we have all states:
            if ($index < 2) {
                // Sukses / Dibayar
                Pembayaran::factory()->create([
                    'invoice_id' => $inv->id,
                    'nominal' => $inv->total_tagihan,
                    'status' => 'Sukses',
                    'paid_at' => now(),
                    'bukti_file' => 'bukti_pembayaran/dummy_sukses_' . ($index + 1) . '.jpg',
                ]);
                $inv->update(['status_pembayaran' => 'Dibayar']);
            } elseif ($index < 4) {
                // Pending / Menunggu Verifikasi
                Pembayaran::factory()->create([
                    'invoice_id' => $inv->id,
                    'nominal' => $inv->total_tagihan,
                    'status' => 'Pending',
                    'bukti_file' => 'bukti_pembayaran/dummy_pending_' . ($index - 1) . '.jpg',
                ]);
                $inv->update(['status_pembayaran' => 'Menunggu']);
            } else {
                // Belum Bayar
                $inv->update(['status_pembayaran' => 'Menunggu']);
            }
            $index++;
        }

        // Tasks, submissions and evaluations
        // Create tasks linked to sessions
        $tugasList = collect();
        if ($sesiList->isEmpty()) {
            $sesiList = SesiLive::factory()->count(4)->create(['kelas_id' => $kelasList->random()->id]);
        }
        for ($i = 0; $i < 6; $i++) {
            $tugasList->push(Tugas::factory()->create(['sesi_id' => $sesiList->random()->id]));
        }

        // Create submissions for tasks by random students
        // foreach ($tugasList as $t) {
        //     $count = rand(1, min(4, $allSiswa->count()));
        //     $students = $allSiswa->random($count);
        //     foreach ($students as $stu) {
        //         PengumpulanTugas::factory()->create([
        //             'tugas_id' => $t->id,
        //             'siswa_id' => $stu->id,
        //         ]);
        //     }
        // }

        // EvaluasiInstruktur::factory()->count(4)->create();

        // // Forums & comments: create topics per class and comments by users
        // $forumTopics = collect();
        // foreach ($kelasList as $kelas) {
        //     $created = ForumTopik::factory()->count(2)->create([
        //         'kelas_id' => $kelas->id,
        //         'pembuat_id' => $allUsers->random()->id,
        //     ]);
        //     $forumTopics = $forumTopics->merge($created);
        // }

        // foreach ($forumTopics as $topic) {
        //     // each topic gets several comments
        //     $count = rand(1, 4);
        //     for ($i = 0; $i < $count; $i++) {
        //         ForumKomentar::factory()->create([
        //             'topik_id' => $topic->id,
        //             'user_id' => $allUsers->random()->id,
        //         ]);
        //     }
        // }

        // // Notifications, logs, maintenance, and other utilities
        // Notifikasi::factory()->count(7)->create();
        // AuditLog::factory()->count(10)->create();
        // MaintenanceAset::factory()->count(4)->create();

        // // Peminjaman, sertifikat, tiket, arsip
        // // Create peminjaman entries linked to item kits and siswa
        // for ($i = 0; $i < 5; $i++) {
        //     PeminjamanItemAset::factory()->create([
        //         'item_kit_id' => $itemkits->random()->id,
        //         'peminjam_id' => $allSiswa->random()->id,
        //     ]);
        // }
        // Sertifikat::factory()->count(4)->create();
        // TiketKeluhan::factory()->count(5)->create();
        // ArsipLaporan::factory()->count(4)->create();

        // Roles already seeded; create some role assignments via users if missing
        if ($adminRole) {
            User::factory()->count(2)->create(['role_id' => $adminRole->id]);
        }
    }
}