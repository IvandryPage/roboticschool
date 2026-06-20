<?php

namespace App\Filament\Resources\PengumpulanTugas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;

class PengumpulanTugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            
            // Asumsi ada relasi ke tabel Tugas dan Siswa. 
            // Ubah 'judul' dan 'nama' sesuai nama kolom di database kamu jika berbeda.
            Select::make('tugas_id')
                ->label('Tugas yang Dikerjakan')
                ->relationship('tugas', 'judul') 
                ->searchable()
                ->required(),

            Select::make('siswa_id')
                ->label('Nama Siswa')
                ->relationship('siswa', 'nama')
                ->searchable()
                ->required(),

            // 1. Fitur Unggah File Jawaban
            FileUpload::make('file_jawaban') // Sesuaikan dengan nama kolom database-mu
                ->label('Unggah File Jawaban')
                ->directory('pengumpulan_tugas')
                ->acceptedFileTypes([
                    'application/pdf', 
                    'application/msword', 
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/zip',
                    'application/x-rar-compressed'
                ])
                ->helperText('Format yang diizinkan: PDF, Word (DOC/DOCX), ZIP, atau RAR.')
                ->required(),

            // 2. Fitur Catatan Opsional
            Textarea::make('catatan')
                ->label('Catatan Tambahan (Opsional)')
                ->placeholder('Tulis pesan untuk instruktur jika ada...')
                ->nullable() // Membuatnya tidak wajib diisi
                ->columnSpanFull(),

            // 3. Fitur Konfirmasi Pengumpulan
            Checkbox::make('konfirmasi')
                ->label('Konfirmasi: Saya yakin file jawaban ini sudah benar dan siap dikumpulkan.')
                ->accepted() // Memaksa sistem agar siswa WAJIB mencentang ini sebelum bisa submit
                ->dehydrated(false) // Trik ajaib: Mencegah error database karena kolom 'konfirmasi' ini hanya untuk tampilan, tidak disimpan ke tabel
                ->columnSpanFull(),
        ]);
    }
}