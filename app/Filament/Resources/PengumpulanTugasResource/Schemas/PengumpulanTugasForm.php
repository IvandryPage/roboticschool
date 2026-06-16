<?php

namespace App\Filament\Resources\PengumpulanTugas\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Carbon;
use Closure;

class PengumpulanTugasForm
{
    public static function configure($form)
    {
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
                TextInput::make('nilai')
                    ->label('Nilai Akhir')
                    ->numeric()
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