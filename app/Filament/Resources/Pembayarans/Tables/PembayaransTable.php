<?php

namespace App\Filament\Resources\Pembayarans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PembayaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.no_invoice')
                    ->label('No. Invoice')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('invoice.pendaftaran.calonPeserta.nama_lengkap')
                    ->label('Calon Peserta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('invoice.pendaftaran.program.nama_program')
                    ->label('Program Kursus')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sukses' => 'success',
                        'Pending' => 'warning',
                        'Gagal' => 'danger',
                        'Refund' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                ImageColumn::make('bukti_file')
                    ->label('Bukti')
                    ->disk('public')
                    ->visibility('public')
                    ->square()
                    ->height(40),
                TextColumn::make('verifikator.nama_lengkap')
                    ->label('Diverifikasi Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Tgl Upload')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('paid_at')
                    ->label('Tgl Verifikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'Pending' => 'Pending',
                        'Sukses' => 'Sukses (Valid)',
                        'Gagal' => 'Gagal (Tidak Valid)',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-shield-check')
                    ->color('success')
                    ->visible(fn (Pembayaran $record): bool => $record->status === 'Pending')
                    ->form([
                        Select::make('status')
                            ->label('Status Verifikasi')
                            ->options([
                                'Sukses' => 'Valid (Setujui)',
                                'Gagal' => 'Tidak Valid (Tolak)',
                            ])
                            ->required()
                            ->live(),
                        Textarea::make('catatan_penolakan')
                            ->label('Catatan Penolakan')
                            ->visible(fn (Get $get) => $get('status') === 'Gagal')
                            ->required(fn (Get $get) => $get('status') === 'Gagal')
                            ->placeholder('Masukkan alasan penolakan pembayaran...'),
                    ])
                    ->action(function (Pembayaran $record, array $data): void {
                        $record->update([
                            'status' => $data['status'],
                            'catatan_penolakan' => $data['status'] === 'Gagal' ? $data['catatan_penolakan'] : null,
                            'diverifikasi_oleh' => auth()->id(),
                            'paid_at' => $data['status'] === 'Sukses' ? now() : null,
                        ]);

                        // Update status pembayaran di Invoice terkait
                        $invoiceStatus = $data['status'] === 'Sukses' ? 'Dibayar' : 'Gagal';
                        $record->invoice->update([
                            'status_pembayaran' => $invoiceStatus,
                        ]);

                        // PBI-149: Jika pembayaran Sukses, aktivasi akun siswa otomatis
                        if ($data['status'] === 'Sukses') {
                            static::aktifkanSiswa($record);
                        }

                        Notification::make()
                            ->title('Pembayaran Berhasil Diverifikasi')
                            ->body('Status pembayaran telah diubah menjadi ' . ($data['status'] === 'Sukses' ? 'Sukses (Valid)' : 'Gagal (Tidak Valid)'))
                            ->success()
                            ->send();
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function aktifkanSiswa(Pembayaran $pembayaran): void
    {
        $invoice = $pembayaran->invoice;
        if (! $invoice) {
            return;
        }

        $pendaftaran = $invoice->pendaftaran;
        if (! $pendaftaran) {
            return;
        }

        // Cek apakah data Siswa untuk pendaftaran ini sudah ada
        if (Siswa::where('pendaftaran_id', $pendaftaran->id)->exists()) {
            return;
        }

        $calonPeserta = $pendaftaran->calonPeserta;
        if (! $calonPeserta) {
            return;
        }

        // 1. Dapatkan role Siswa
        $siswaRole = Role::where('nama_role', 'Siswa')->first();

        // 2. Buat username/name unik
        $baseName = strtolower(str_replace(' ', '', $calonPeserta->nama_lengkap));
        $name = $baseName;
        $counter = 1;
        while (User::where('name', $name)->exists()) {
            $name = $baseName . $counter;
            $counter++;
        }

        // 3. Buat User Siswa baru
        $user = User::firstOrCreate(
            ['email' => $calonPeserta->email],
            [
                'id' => (string) Str::uuid(),
                'nama_lengkap' => $calonPeserta->nama_lengkap,
                'name' => $name,
                'password' => Hash::make('siswa123'),
                'role_id' => $siswaRole?->id,
                'status_aktif' => true,
                'no_hp' => $calonPeserta->no_hp,
            ]
        );

        // 4. Buat record Siswa
        Siswa::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'pendaftaran_id' => $pendaftaran->id,
            'alamat' => $calonPeserta->asal_sekolah_atau_instansi ?? 'Tidak ada data alamat',
            'jenis_kelamin' => 'Laki-laki', // default dummy value
            'tanggal_lahir' => now()->subYears(15)->format('Y-m-d'), // default dummy value
        ]);

        // 5. Update status pendaftaran menjadi Diterima
        $pendaftaran->update([
            'status' => 'Diterima'
        ]);
    }
}
