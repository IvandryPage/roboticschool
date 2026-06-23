<?php

namespace App\Filament\Resources\AsetRobotikResource\Pages;

use App\Filament\Resources\AsetRobotikResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use App\Models\AsetRobotik;
use App\Models\ItemKitRobotik;

class CreateAsetRobotik extends CreateRecord
{
    protected static string $resource = AsetRobotikResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $slug = strtoupper(Str::slug($data['nama_kit']));
        $slug = preg_replace('/[^A-Z0-9\-]/', '', $slug);
        $base_kode = 'KIT-' . ($slug ?: 'ROBOT');
        $kode_aset = $base_kode;
        $counter = 1;
        while (AsetRobotik::where('kode_aset', $kode_aset)->exists()) {
            $kode_aset = $base_kode . '-' . $counter;
            $counter++;
        }

        $data['kode_aset'] = $kode_aset;
        return $data;
    }

    protected function afterCreate(): void
    {
        $cleanName = preg_replace('/[^A-Za-z0-9]/', '', Str::slug($this->record->nama_kit));
        $prefix = strtoupper(substr($cleanName, 0, 3)) ?: 'KIT';

        $jumlahStok = intval($this->data['jumlah_stok'] ?? 0);
        $kondisi = $this->data['kondisi'] ?? 'Bagus';

        for ($i = 1; $i <= $jumlahStok; $i++) {
            $sn_counter = 1;
            do {
                $serial_number = 'SN-' . $prefix . '-' . str_pad($sn_counter, 3, '0', STR_PAD_LEFT);
                $sn_counter++;
            } while (ItemKitRobotik::where('serial_number', $serial_number)->exists());

            ItemKitRobotik::create([
                'id' => (string) Str::uuid(),
                'aset_id' => $this->record->id,
                'serial_number' => $serial_number,
                'status_kondisi' => $kondisi,
                'lokasi_rak' => 'RAK-' . $prefix . '1',
            ]);
        }
    }
}
