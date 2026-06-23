<?php

namespace App\Filament\Resources\SesiLives\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;

class SesiLiveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'id')
                    ->required(),
                    
                TextInput::make('nomor_sesi')
                    ->label('Nomor Sesi')
                    ->required()
                    ->numeric()
                    ->unique(
                        table: 'sesi_live',
                        column: 'nomor_sesi',
                        ignoreRecord: true,
                        // Perbaikan: Hapus deklarasi tipe data Get sebelum $get agar fleksibel
                        modifyRuleUsing: function (Unique $rule, $get) {
                            return $rule->where('kelas_id', $get('kelas_id'));
                        }
                    )
                    ->validationMessages([
                        'unique' => 'Nomor sesi ini sudah ada di kelas yang Anda pilih. Silakan gunakan nomor lain.',
                    ]),
                    
                TextInput::make('judul_sesi')
                    ->label('Judul Sesi'),
                    
                DatePicker::make('tanggal')
                    ->label('Tanggal'),
                    
                TimePicker::make('jam_mulai')
                    ->label('Jam Mulai'),
                    
                TimePicker::make('jam_selesai')
                    ->label('Jam Selesai'),
                    
                TextInput::make('platform')
                    ->label('Platform (Contoh: Zoom, Google Meet)'),
                    
                TextInput::make('link_akses')
                    ->label('Link Akses')
                    ->url(),
                    
                Textarea::make('keterangan')
                    ->label('Keterangan Tambahan')
                    ->columnSpanFull(),
            ]);
    }
}