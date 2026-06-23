<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengumpulanTugasResource\Pages;
use App\Models\PengumpulanTugas;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class PengumpulanTugasResource extends Resource
{
    protected static ?string $model = PengumpulanTugas::class;

    // Opsional: Tambahkan ikon navigasi jika kamu mau
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Tambahkan skema form kamu di sini jika ada (atau biarkan kosong jika form-nya dipisah di file Schema)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tugas.nama_tugas')
                    ->label('Nama Tugas')
                    ->searchable()
                    ->sortable(),

                // Diubah menggunakan TextColumn + badge() untuk Filament v3
                TextColumn::make('status_penilaian')
                    ->label('Status')
                    ->badge() 
                    ->colors([
                        'warning' => 'Belum Dinilai',
                        'success' => 'Dinilai',
                    ]),

                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->suffix('/100')
                    ->sortable()
                    ->color(fn ($state): string => $state >= 75 ? 'success' : 'danger'),

                TextColumn::make('umpan_balik')
                    ->label('Umpan Balik')
                    ->limit(40)
                    ->tooltip(fn ($record): ?string => $record->umpan_balik),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // PBI-107: Keamanan Data
        // Memastikan siswa hanya melihat pengumpulan tugas milik mereka sendiri
        $user = auth()->user();

        // Jika user adalah admin atau instruktur, biarkan mereka melihat semuanya.
        if ($user->hasRole('admin') || $user->hasRole('instruktur')) {
            return parent::getEloquentQuery();
        }

        // Ambil data siswa yang terhubung dengan user yang sedang login
        $siswa = $user->siswa;

        // Jika user ini adalah siswa, tampilkan tugasnya berdasarkan siswa_id
        if ($siswa) {
            return parent::getEloquentQuery()->where('siswa_id', $siswa->id);
        }

        // Fallback: Jika tidak masuk kriteria di atas, jangan tampilkan data apa pun
        return parent::getEloquentQuery()->where('id', null);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengumpulanTugas::route('/'),
            'create' => Pages\CreatePengumpulanTugas::route('/create'),
            'edit' => Pages\EditPengumpulanTugas::route('/{record}/edit'),
            // Hapus komentar di bawah ini jika kamu punya halaman View (ViewPengumpulanTugas)
            // 'view' => Pages\ViewPengumpulanTugas::route('/{record}'), 
        ];
    }
}