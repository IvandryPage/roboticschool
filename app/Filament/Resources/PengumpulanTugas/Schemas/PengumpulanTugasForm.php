<?php

namespace App\Filament\Resources\PengumpulanTugas\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Closure;
use Filament\Forms\Get;
use App\Models\Tugas;
use Carbon\Carbon;

class PengumpulanTugasForm
{
    public static function configure($form)
    {
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
                
                TextInput::make('nilai')
                    ->label('Nilai Akhir')
                    ->numeric()
                    ->label('Nilai')
                    ->inputMode('decimal'),
                
                Textarea::make('umpan_balik')
                    ->label('Umpan Balik')
                    ->columnSpanFull(),
                
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