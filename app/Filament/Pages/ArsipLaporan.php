<?php
namespace App\Filament\Pages;

use App\Models\ArsipLaporan as ArsipLaporanModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ArsipLaporan extends Page implements HasForms
{
    use InteractsWithForms;

  protected static ?string $title = 'Arsip Laporan';

public static function getNavigationLabel(): string
{
    return 'Arsip Laporan';
}
    public function getView(): string
{
    return 'filament.pages.arsip-laporan';
}

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

   public function form(Schema $schema): Schema
{
    return $schema
        ->components([
                TextInput::make('judul')
                    ->label('Judul Laporan')
                    ->required(),
                Select::make('tipe_laporan')
                    ->label('Tipe Laporan')
                    ->options([
                        'operasional' => 'Operasional',
                        'keuangan' => 'Keuangan',
                        'akademik' => 'Akademik',
                    ])
                    ->required(),
                TextInput::make('periode')
                    ->label('Periode')
                    ->placeholder('cth: Januari 2026'),
                Textarea::make('catatan')
                    ->label('Catatan')
                    ->rows(4),
            ])
            ->statePath('data');
    }

    public function simpan(): void
    {
        $data = $this->form->getState();
        ArsipLaporanModel::create([
            ...$data,
            'dibuat_oleh' => Auth::id(),
        ]);

        Notification::make()
            ->title('Laporan berhasil disimpan')
            ->success()
            ->send();

        $this->form->fill();
    }

    public function getLaporan()
    {
        return ArsipLaporanModel::with('pembuat')
            ->latest()
            ->get();
    }

    public function hapus(string $id): void
    {
        ArsipLaporanModel::findOrFail($id)->delete();

        Notification::make()
            ->title('Laporan berhasil dihapus')
            ->success()
            ->send();
    }
}