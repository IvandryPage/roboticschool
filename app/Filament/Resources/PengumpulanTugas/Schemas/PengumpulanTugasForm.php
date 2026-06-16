<?php

namespace App\Filament\Resources\PengumpulanTugas\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
<<<<<<< HEAD
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Closure;
use Filament\Forms\Get;
use App\Models\Tugas;
use Carbon\Carbon;
=======
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Carbon;
use Closure;
>>>>>>> 01e1427be3bd9d2e5adbe5a70f2b7ec8f39b390d

class PengumpulanTugasForm
{
    public static function configure($form)
    {
<<<<<<< HEAD
        return $schema
            ->components([
                TextInput::make('tugas_id')
                    ->required()
                    ->label('ID Tugas'),
                
                TextInput::make('siswa_id')
                    ->required()
                    ->label('ID Siswa'),
                
                // --- INI ADALAH BAGIAN NOMOR 3 YANG SUDAH DIPERBARUI ---
                FileUpload::make('file_jawaban')
                    ->label('File Jawaban')
                    ->directory('jawaban_tugas')
                    ->required()
                    ->rules([
                        // Validasi 1: Format file (hanya menerima pdf, docx, zip)
                        'mimes:pdf,docx,zip',
                        
                        // Validasi 2: Cek batas waktu tugas
                        static function (Get $get) {
                            return static function (string $attribute, $value, Closure $fail) use ($get) {
                                $tugasId = $get('tugas_id');
                                
                                if ($tugasId) {
                                    $tugas = Tugas::find($tugasId);
                                    
                                    // Jika tugas ada dan waktu sekarang melewati batas waktu
                                    if ($tugas && $tugas->batas_waktu && now()->greaterThan($tugas->batas_waktu)) {
                                        $waktuBatas = Carbon::parse($tugas->batas_waktu)->format('d M Y, H:i');
                                        $fail("Gagal! Batas waktu pengumpulan sudah habis pada {$waktuBatas} WIB.");
                                    }
                                }
                            };
                        }
                    ]),
                // --------------------------------------------------------

                Textarea::make('catatan_siswa')
                    ->label('Catatan Siswa')
                    ->columnSpanFull(),
                
                DateTimePicker::make('waktu_kumpul')
                    ->label('Waktu Kumpul'),
                
=======
        return $form
            ->schema([
                // 1. Informasi Dasar (Tanpa Section untuk menghindari error class)
                Select::make('tugas_id')
                    ->label('Pilih Tugas')
                    ->relationship('tugas', 'id')
                    ->required(),

                Select::make('siswa_id')
                    ->label('Nama Siswa')
                    ->relationship('siswa', 'id')
                    ->required(),

                // 2. PBI-104: Validasi File
                FileUpload::make('file_jawaban')
                    ->label('Unggah File Jawaban')
                    ->required()
                    ->maxSize(5120)
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            $batasWaktu = Carbon::parse('2026-06-20 23:59:00');
                            if (now()->greaterThan($batasWaktu)) {
                                $fail('Maaf, waktu pengumpulan tugas sudah ditutup.');
                            }
                        },
                    ]),

                // 3. PB-106: Penilaian Instruktur
>>>>>>> 01e1427be3bd9d2e5adbe5a70f2b7ec8f39b390d
                TextInput::make('nilai')
                    ->label('Nilai Akhir')
                    ->numeric()
<<<<<<< HEAD
                    ->label('Nilai')
                    ->inputMode('decimal'),
                
                Textarea::make('umpan_balik')
                    ->label('Umpan Balik')
                    ->columnSpanFull(),
                
=======
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('/100')
                    // Logic otomatis: Update status jika nilai diisi
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        if (!empty($state)) {
                            $set('status_penilaian', 'Dinilai');
                        } else {
                            $set('status_penilaian', 'Belum Dinilai');
                        }
                    }),

                Textarea::make('umpan_balik')
                    ->label('Umpan Balik')
                    ->rows(3),

>>>>>>> 01e1427be3bd9d2e5adbe5a70f2b7ec8f39b390d
                Select::make('status_penilaian')
                    ->label('Status Penilaian')
                    ->options([
                        'Belum Dinilai' => 'Belum Dinilai',
                        'Dinilai' => 'Dinilai',
                    ])
                    ->default('Belum Dinilai')
                    ->required(),
            ]);
    }
}