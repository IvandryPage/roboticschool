# Script tulis ulang semua file PHP sertifikat - tanpa BOM
# Jalankan dari folder: C:\Users\Lenovo\roboticschool

function Write-PhpFile($path, $content) {
    $utf8NoBom = New-Object System.Text.UTF8Encoding $false
    [System.IO.File]::WriteAllText((Join-Path (Get-Location) $path), $content, $utf8NoBom)
    Write-Host "OK: $path" -ForegroundColor Green
}

# Buat folder
New-Item -ItemType Directory -Force -Path "app\Services" | Out-Null
New-Item -ItemType Directory -Force -Path "app\Filament\Resources\SertifikatResource\Pages" | Out-Null
New-Item -ItemType Directory -Force -Path "app\Filament\Resources\SiswaLayakSertifikatResource\Pages" | Out-Null
New-Item -ItemType Directory -Force -Path "resources\views\sertifikat" | Out-Null

# 1. Model Sertifikat
Write-PhpFile "app\Models\Sertifikat.php" "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertifikat extends Model
{
    use HasUuids;

    protected `$table = 'sertifikat';

    protected `$fillable = [
        'siswa_id',
        'kelas_id',
        'nomor_sertifikat',
        'file_path',
        'qr_code',
        'verified_url',
        'tanggal_terbit',
        'diterbitkan_oleh',
    ];

    protected `$casts = [
        'tanggal_terbit' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return `$this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas(): BelongsTo
    {
        return `$this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function penerbit(): BelongsTo
    {
        return `$this->belongsTo(User::class, 'diterbitkan_oleh');
    }
}
"

# 2. SertifikatService
Write-PhpFile "app\Services\SertifikatService.php" "<?php

namespace App\Services;

use App\Models\Sertifikat;
use Carbon\Carbon;

class SertifikatService
{
    public function generateNomorSertifikat(): string
    {
        `$tahun = Carbon::now()->format('Y');
        `$prefix = ""RBN-{`$tahun}-"";

        `$last = Sertifikat::where('nomor_sertifikat', 'like', ""{`$prefix}%"")
            ->orderByDesc('nomor_sertifikat')
            ->first();

        if (`$last) {
            `$lastNumber = (int) substr(`$last->nomor_sertifikat, -3);
            `$newNumber = `$lastNumber + 1;
        } else {
            `$newNumber = 1;
        }

        return `$prefix . str_pad(`$newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function terbitkanSertifikat(string `$siswaId, string `$kelasId, string `$adminId): Sertifikat
    {
        `$existing = Sertifikat::where('siswa_id', `$siswaId)
            ->where('kelas_id', `$kelasId)
            ->first();

        if (`$existing) {
            throw new \Exception('Siswa ini sudah memiliki sertifikat untuk kelas tersebut.');
        }

        `$nomor = `$this->generateNomorSertifikat();
        `$verifiedUrl = url('/sertifikat/verifikasi/' . `$nomor);

        return Sertifikat::create([
            'siswa_id'         => `$siswaId,
            'kelas_id'         => `$kelasId,
            'nomor_sertifikat' => `$nomor,
            'verified_url'     => `$verifiedUrl,
            'tanggal_terbit'   => now(),
            'diterbitkan_oleh' => `$adminId,
        ]);
    }
}
"

# 3. SertifikatResource
Write-PhpFile "app\Filament\Resources\SertifikatResource.php" "<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SertifikatResource\Pages;
use App\Models\Sertifikat;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class SertifikatResource extends Resource
{
    protected static ?string `$model = Sertifikat::class;
    protected static ?string `$navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string `$navigationLabel = 'Sertifikat';
    protected static ?string `$navigationGroup = 'Akademik';
    protected static ?int `$navigationSort = 3;

    public static function form(Form `$form): Form
    {
        return `$form->schema([]);
    }

    public static function table(Table `$table): Table
    {
        return `$table
            ->columns([
                TextColumn::make('nomor_sertifikat')
                    ->label('Nomor Sertifikat')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('siswa.user.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('kelas.programKursus.nama_program')
                    ->label('Program')
                    ->searchable(),
                TextColumn::make('tanggal_terbit')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y')
                    ->sortable(),
                TextColumn::make('penerbit.name')
                    ->label('Diterbitkan Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->label('Filter Kelas'),
            ])
            ->actions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Sertifikat `$record) => route('sertifikat.verifikasi', `$record->nomor_sertifikat))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal_terbit', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSertifikat::route('/'),
        ];
    }
}
"

# 4. ListSertifikat
Write-PhpFile "app\Filament\Resources\SertifikatResource\Pages\ListSertifikat.php" "<?php

namespace App\Filament\Resources\SertifikatResource\Pages;

use App\Filament\Resources\SertifikatResource;
use Filament\Resources\Pages\ListRecords;

class ListSertifikat extends ListRecords
{
    protected static string `$resource = SertifikatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
"

# 5. SiswaLayakSertifikatResource
Write-PhpFile "app\Filament\Resources\SiswaLayakSertifikatResource.php" "<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaLayakSertifikatResource\Pages;
use App\Models\EnrollmentKelas;
use App\Services\SertifikatService;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SiswaLayakSertifikatResource extends Resource
{
    protected static ?string `$model = EnrollmentKelas::class;
    protected static ?string `$navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string `$navigationLabel = 'Siswa Layak Sertifikat';
    protected static ?string `$navigationGroup = 'Akademik';
    protected static ?int `$navigationSort = 2;
    protected static ?string `$slug = 'siswa-layak-sertifikat';

    public static function form(Form `$form): Form
    {
        return `$form->schema([]);
    }

    public static function table(Table `$table): Table
    {
        return `$table
            ->query(
                EnrollmentKelas::query()
                    ->where('status', 'lulus')
                    ->whereDoesntHave('sertifikat')
                    ->with(['siswa.user', 'kelas.programKursus'])
            )
            ->columns([
                TextColumn::make('siswa.user.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelas.programKursus.nama_program')
                    ->label('Program')
                    ->searchable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->suffix('/100')
                    ->sortable(),
                TextColumn::make('persentase_kehadiran')
                    ->label('Kehadiran')
                    ->formatStateUsing(fn(`$state) => `$state . '%')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string `$state): string => match (`$state) {
                        'lulus' => 'success',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Action::make('terbitkan')
                    ->label('Terbitkan Sertifikat')
                    ->icon('heroicon-o-academic-cap')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Terbitkan Sertifikat')
                    ->modalDescription(fn(EnrollmentKelas `$record) =>
                        ""Terbitkan sertifikat untuk {`$record->siswa->user->name} - {`$record->kelas->nama_kelas}?""
                    )
                    ->action(function (EnrollmentKelas `$record) {
                        try {
                            `$service = new SertifikatService();
                            `$service->terbitkanSertifikat(
                                `$record->siswa_id,
                                `$record->kelas_id,
                                Auth::id()
                            );
                            Notification::make()
                                ->title('Sertifikat berhasil diterbitkan!')
                                ->success()
                                ->send();
                        } catch (\Exception `$e) {
                            Notification::make()
                                ->title('Gagal menerbitkan sertifikat')
                                ->body(`$e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('terbitkan_semua')
                    ->label('Terbitkan Semua')
                    ->icon('heroicon-o-academic-cap')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (`$records) {
                        `$service = new SertifikatService();
                        `$berhasil = 0;
                        foreach (`$records as `$record) {
                            try {
                                `$service->terbitkanSertifikat(
                                    `$record->siswa_id,
                                    `$record->kelas_id,
                                    Auth::id()
                                );
                                `$berhasil++;
                            } catch (\Exception `$e) {}
                        }
                        Notification::make()
                            ->title(""{`$berhasil} sertifikat berhasil diterbitkan!"")
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswaLayakSertifikat::route('/'),
        ];
    }
}
"

# 6. ListSiswaLayakSertifikat
Write-PhpFile "app\Filament\Resources\SiswaLayakSertifikatResource\Pages\ListSiswaLayakSertifikat.php" "<?php

namespace App\Filament\Resources\SiswaLayakSertifikatResource\Pages;

use App\Filament\Resources\SiswaLayakSertifikatResource;
use Filament\Resources\Pages\ListRecords;

class ListSiswaLayakSertifikat extends ListRecords
{
    protected static string `$resource = SiswaLayakSertifikatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
"

# 7. SertifikatController
Write-PhpFile "app\Http\Controllers\SertifikatController.php" "<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;

class SertifikatController extends Controller
{
    public function milikSaya()
    {
        `$siswa = auth()->user()->siswa;
        if (!`$siswa) abort(403, 'Akses ditolak.');

        `$sertifikat = Sertifikat::where('siswa_id', `$siswa->id)
            ->with(['kelas.programKursus', 'penerbit'])
            ->latest('tanggal_terbit')
            ->first();

        return view('sertifikat.show', compact('sertifikat', 'siswa'));
    }

    public function verifikasi(string `$nomor)
    {
        `$sertifikat = Sertifikat::where('nomor_sertifikat', `$nomor)
            ->with(['siswa.user', 'kelas.programKursus', 'penerbit'])
            ->firstOrFail();

        return view('sertifikat.show', compact('sertifikat'));
    }
}
"

Write-Host ""
Write-Host "Semua file berhasil ditulis ulang!" -ForegroundColor Yellow
Write-Host "Sekarang jalankan: php artisan optimize:clear" -ForegroundColor Cyan
