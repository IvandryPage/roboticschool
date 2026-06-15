<?php

namespace App\Filament\Resources\PengumpulanTugas\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select; 
use Illuminate\Support\Carbon;
use Closure;

class PengumpulanTugasForm
{
    public static function configure($form)
    {
        return $form
            ->schema([
                
                // 1. Kotak Pilihan Tugas
                Select::make('tugas_id')
                    ->label('Pilih Tugas')
                    ->relationship('tugas', 'id') // Kita gunakan 'id' dulu
                    ->required(),

                // 2. Kotak Pilihan Siswa
                Select::make('siswa_id')
                    ->label('Nama Siswa')
                    ->relationship('siswa', 'id') // Kita gunakan 'id' dulu
                    ->required(),

                // 3. KODE PBI-104 (VALIDASI FILE & WAKTU)
                // --- KITA UBAH MENJADI file_jawaban ---
                FileUpload::make('file_jawaban') 
                    ->label('Unggah File Jawaban')
                    ->required()
                    ->maxSize(5120) 
                    
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ])
                    ->validationMessages([
                        'accepted_file_types' => 'Gagal! Anda hanya boleh mengunggah file .pdf, .doc, atau .docx.',
                    ])
                    
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            
                            // Sengaja diatur ke tanggal masa depan agar tes penyimpanannya berhasil
                            $batasWaktu = Carbon::parse('2026-06-20 23:59:00'); 

                            if (now()->greaterThan($batasWaktu)) {
                                $fail('Maaf, waktu pengumpulan tugas ini sudah ditutup.');
                            }
                        },
                    ]),

            ]);
    }
}