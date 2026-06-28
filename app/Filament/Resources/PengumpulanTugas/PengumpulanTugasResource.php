<?php

namespace App\Filament\Resources\PengumpulanTugas;

use App\Filament\Resources\PengumpulanTugas\Pages;
use App\Models\PengumpulanTugas;
use App\Models\Siswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PengumpulanTugasResource extends Resource
{
    protected static ?string $model = PengumpulanTugas::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationLabel = 'Pengumpulan Tugas';

    protected static ?string $pluralModelLabel = 'Pengumpulan Tugas';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 5;

    public static function canViewAny(): bool
    {
        // Direktur tidak ada akses pengumpulan tugas per PRD
        return in_array(Auth::user()?->role?->nama_role, ['Admin Akademik', 'Instruktur', 'Siswa']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->role?->nama_role === 'Siswa';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('tugas_id')
                    ->label('Pilih Tugas')
                    ->relationship('tugas', 'judul_tugas')
                    ->required()
                    ->searchable()
                    ->preload(),

                // Fix: pluck('nama') tidak bisa karena 'nama' adalah accessor — pakai mapWithKeys
                \Filament\Forms\Components\Select::make('siswa_id')
                    ->label('Nama Siswa')
                    ->options(
                        Siswa::with('user')->get()->mapWithKeys(
                            fn (Siswa $s): array => [
                                $s->id => $s->user?->nama_lengkap ?? $s->user?->name ?? 'Siswa #' . $s->id,
                            ]
                        )
                    )
                    ->required()
                    ->searchable()
                    ->preload(),

                \Filament\Forms\Components\FileUpload::make('file_jawaban')
                    ->label('File Jawaban')
                    ->directory('file-jawaban')
                    ->nullable(),

                \Filament\Forms\Components\Textarea::make('catatan_siswa')
                    ->label('Catatan dari Siswa')
                    ->nullable()
                    ->columnSpanFull(),

                \Filament\Forms\Components\DateTimePicker::make('waktu_kumpul')
                    ->label('Waktu Dikumpulkan')
                    ->default(now())
                    ->nullable(),

                \Filament\Forms\Components\TextInput::make('nilai')
                    ->label('Nilai (0-100)')
                    ->numeric()
                    ->nullable(),

                \Filament\Forms\Components\Select::make('status_penilaian')
                    ->label('Status Penilaian')
                    ->options([
                        'Menunggu'  => 'Menunggu Penilaian',
                        'Dinilai'   => 'Sudah Dinilai',
                        'Terlambat' => 'Terlambat',
                    ])
                    ->default('Menunggu')
                    ->nullable(),

                \Filament\Forms\Components\Textarea::make('umpan_balik')
                    ->label('Umpan Balik (Feedback)')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('tugas.judul_tugas')
                    ->label('Tugas')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('siswa.user.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('waktu_kumpul')
                    ->label('Waktu Kumpul')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('status_penilaian')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Dinilai'   => 'success',
                        'Menunggu'  => 'warning',
                        'Terlambat' => 'danger',
                        default     => 'gray',
                    })
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /** Instruktur: pengumpulan di kelasnya. Siswa: milik sendiri. */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $q    = parent::getEloquentQuery();
        $role = Auth::user()?->role?->nama_role;

        if ($role === 'Instruktur') {
            $kIds = \App\Models\Kelas::where('instruktur_id', Auth::id())->pluck('id');
            $sIds = \App\Models\SesiLive::whereIn('kelas_id', $kIds)->pluck('id');
            return $q->whereHas('tugas', fn ($sq) => $sq->whereIn('sesi_id', $sIds));
        }

        if ($role === 'Siswa') {
            $siswa = \App\Models\Siswa::where('user_id', Auth::id())->first();
            return $siswa ? $q->where('siswa_id', $siswa->id) : $q->whereRaw('1=0');
        }

        return $q;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengumpulanTugas::route('/'),
        ];
    }
}
