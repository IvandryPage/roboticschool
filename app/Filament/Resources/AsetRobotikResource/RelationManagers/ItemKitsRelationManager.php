<?php

namespace App\Filament\Resources\AsetRobotikResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\ItemKitRobotik;
use App\Models\PeminjamanItemAset;
use Illuminate\Support\Str;
use Filament\Tables;

class ItemKitsRelationManager extends RelationManager
{
    protected static string $relationship = 'itemKits';

    protected static ?string $title = 'Daftar Item Kit (Stok)';

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([
            TextInput::make('serial_number')
                ->label('Serial Number')
                ->disabled(),
            Select::make('status_kondisi')
                ->label('Status Kondisi')
                ->options([
                    'Bagus' => 'Bagus',
                    'Rusak' => 'Rusak',
                    'Perbaikan' => 'Perbaikan',
                ])
                ->required(),
            TextInput::make('lokasi_rak')
                ->label('Lokasi Rak')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('serial_number')
            ->columns([
                TextColumn::make('serial_number')
                    ->label('Serial Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_kondisi')
                    ->label('Status Kondisi')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Bagus' => 'success',
                        'Rusak' => 'danger',
                        'Perbaikan' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('lokasi_rak')
                    ->label('Lokasi Rak')
                    ->searchable(),
                TextColumn::make('availability')
                    ->label('Ketersediaan')
                    ->getStateUsing(fn (ItemKitRobotik $record) => 
                        ($record->status_kondisi === 'Bagus' && 
                         !PeminjamanItemAset::where('item_kit_id', $record->id)->where('status', 'Dipinjam')->exists()) 
                        ? 'Tersedia' 
                        : 'Tidak Tersedia'
                    )
                    ->badge()
                    ->color(fn ($state) => $state === 'Tersedia' ? 'success' : 'danger'),
            ])
            ->headerActions([
                Action::make('add_kits')
                    ->label('Tambah Item Kit')
                    ->form([
                        TextInput::make('jumlah_stok')
                            ->label('Jumlah Stok Baru')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        Select::make('status_kondisi')
                            ->label('Status Kondisi')
                            ->options([
                                'Bagus' => 'Bagus',
                                'Rusak' => 'Rusak',
                                'Perbaikan' => 'Perbaikan',
                            ])
                            ->default('Bagus')
                            ->required(),
                        TextInput::make('lokasi_rak')
                            ->label('Lokasi Rak')
                            ->placeholder('Kosongkan untuk lokasi default')
                            ->nullable(),
                    ])
                    ->action(function (array $data) {
                        $aset = $this->getOwnerRecord();
                        $cleanName = preg_replace('/[^A-Za-z0-9]/', '', Str::slug($aset->nama_kit));
                        $prefix = strtoupper(substr($cleanName, 0, 3)) ?: 'KIT';

                        $jumlahStok = intval($data['jumlah_stok'] ?? 1);
                        $statusKondisi = $data['status_kondisi'] ?? 'Bagus';
                        $lokasi = $data['lokasi_rak'] ?: ('RAK-' . $prefix . '1');

                        for ($i = 1; $i <= $jumlahStok; $i++) {
                            $sn_counter = 1;
                            do {
                                $serial_number = 'SN-' . $prefix . '-' . str_pad($sn_counter, 3, '0', STR_PAD_LEFT);
                                $sn_counter++;
                            } while (ItemKitRobotik::where('serial_number', $serial_number)->exists());

                            ItemKitRobotik::create([
                                'id' => (string) Str::uuid(),
                                'aset_id' => $aset->id,
                                'serial_number' => $serial_number,
                                'status_kondisi' => $statusKondisi,
                                'lokasi_rak' => $lokasi,
                            ]);
                        }
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (ItemKitRobotik $record) {
                        $hasActiveLoan = PeminjamanItemAset::where('item_kit_id', $record->id)
                            ->whereIn('status', ['Diajukan', 'Dipinjam'])
                            ->exists();
                        if ($hasActiveLoan) {
                            // Throwing ValidationException to show user friendly error in Filament
                            throw new \Exception('Item kit tidak dapat dihapus karena sedang dalam proses peminjaman.');
                        }
                    }),
            ]);
    }
}
