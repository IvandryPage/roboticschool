# ============================================
# Script Setup Fitur Sertifikat - PB-09
# Jalankan dari folder: C:\Users\Lenovo\roboticschool
# ============================================

# Buat folder jika belum ada
New-Item -ItemType Directory -Force -Path "app\Services" | Out-Null
New-Item -ItemType Directory -Force -Path "app\Filament\Resources\SertifikatResource\Pages" | Out-Null
New-Item -ItemType Directory -Force -Path "app\Filament\Resources\SiswaLayakSertifikatResource\Pages" | Out-Null
New-Item -ItemType Directory -Force -Path "resources\views\sertifikat" | Out-Null

Write-Host "Folder siap..." -ForegroundColor Cyan

# ============================================
# 1. Model Sertifikat
# ============================================
@'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertifikat extends Model
{
    use HasUuids;

    protected $table = 'sertifikat';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'nomor_sertifikat',
        'file_path',
        'qr_code',
        'verified_url',
        'tanggal_terbit',
        'diterbitkan_oleh',
    ];

    protected $casts = [
        'tanggal_terbit' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function penerbit(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterbitkan_oleh');
    }
}
'@ | Set-Content -Path "app\Models\Sertifikat.php" -Encoding UTF8
Write-Host "app/Models/Sertifikat.php" -ForegroundColor Green

# ============================================
# 2. SertifikatService
# ============================================
@'
<?php

namespace App\Services;

use App\Models\Sertifikat;
use Carbon\Carbon;

class SertifikatService
{
    /**
     * Generate nomor sertifikat otomatis
     * Format: RBN-YYYY-NNN (contoh: RBN-2025-001)
     * PBI-126
     */
    public function generateNomorSertifikat(): string
    {
        $tahun = Carbon::now()->format('Y');
        $prefix = "RBN-{$tahun}-";

        $last = Sertifikat::where('nomor_sertifikat', 'like', "{$prefix}%")
            ->orderByDesc('nomor_sertifikat')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->nomor_sertifikat, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Terbitkan sertifikat untuk siswa
     * PBI-125
     */
    public function terbitkanSertifikat(string $siswaId, string $kelasId, string $adminId): Sertifikat
    {
        $existing = Sertifikat::where('siswa_id', $siswaId)
            ->where('kelas_id', $kelasId)
            ->first();

        if ($existing) {
            throw new \Exception('Siswa ini sudah memiliki sertifikat untuk kelas tersebut.');
        }

        $nomor = $this->generateNomorSertifikat();
        $verifiedUrl = url('/sertifikat/verifikasi/' . $nomor);

        return Sertifikat::create([
            'siswa_id'         => $siswaId,
            'kelas_id'         => $kelasId,
            'nomor_sertifikat' => $nomor,
            'verified_url'     => $verifiedUrl,
            'tanggal_terbit'   => now(),
            'diterbitkan_oleh' => $adminId,
        ]);
    }
}
'@ | Set-Content -Path "app\Services\SertifikatService.php" -Encoding UTF8
Write-Host "app/Services/SertifikatService.php" -ForegroundColor Green

# ============================================
# 3. SertifikatResource
# ============================================
@'
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SertifikatResource\Pages;
use App\Models\Sertifikat;
use App\Services\SertifikatService;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SertifikatResource extends Resource
{
    protected static ?string $model = Sertifikat::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Sertifikat';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->url(fn(Sertifikat $record) => route('sertifikat.verifikasi', $record->nomor_sertifikat))
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
'@ | Set-Content -Path "app\Filament\Resources\SertifikatResource.php" -Encoding UTF8
Write-Host "app/Filament/Resources/SertifikatResource.php" -ForegroundColor Green

# ============================================
# 4. ListSertifikat Page
# ============================================
@'
<?php

namespace App\Filament\Resources\SertifikatResource\Pages;

use App\Filament\Resources\SertifikatResource;
use Filament\Resources\Pages\ListRecords;

class ListSertifikat extends ListRecords
{
    protected static string $resource = SertifikatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
'@ | Set-Content -Path "app\Filament\Resources\SertifikatResource\Pages\ListSertifikat.php" -Encoding UTF8
Write-Host "app/Filament/Resources/SertifikatResource/Pages/ListSertifikat.php" -ForegroundColor Green

# ============================================
# 5. SiswaLayakSertifikatResource
# ============================================
@'
<?php

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
    protected static ?string $model = EnrollmentKelas::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Siswa Layak Sertifikat';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'siswa-layak-sertifikat';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->formatStateUsing(fn($state) => $state . '%')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
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
                    ->modalDescription(fn(EnrollmentKelas $record) =>
                        "Terbitkan sertifikat untuk {$record->siswa->user->name} - {$record->kelas->nama_kelas}?"
                    )
                    ->action(function (EnrollmentKelas $record) {
                        try {
                            $service = new SertifikatService();
                            $service->terbitkanSertifikat(
                                $record->siswa_id,
                                $record->kelas_id,
                                Auth::id()
                            );
                            Notification::make()
                                ->title('Sertifikat berhasil diterbitkan!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal menerbitkan sertifikat')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('terbitkan_semua')
                    ->label('Terbitkan Sertifikat')
                    ->icon('heroicon-o-academic-cap')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $service = new SertifikatService();
                        $berhasil = 0;
                        foreach ($records as $record) {
                            try {
                                $service->terbitkanSertifikat(
                                    $record->siswa_id,
                                    $record->kelas_id,
                                    Auth::id()
                                );
                                $berhasil++;
                            } catch (\Exception $e) {}
                        }
                        Notification::make()
                            ->title("{$berhasil} sertifikat berhasil diterbitkan!")
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
'@ | Set-Content -Path "app\Filament\Resources\SiswaLayakSertifikatResource.php" -Encoding UTF8
Write-Host "app/Filament/Resources/SiswaLayakSertifikatResource.php" -ForegroundColor Green

# ============================================
# 6. ListSiswaLayakSertifikat Page
# ============================================
@'
<?php

namespace App\Filament\Resources\SiswaLayakSertifikatResource\Pages;

use App\Filament\Resources\SiswaLayakSertifikatResource;
use Filament\Resources\Pages\ListRecords;

class ListSiswaLayakSertifikat extends ListRecords
{
    protected static string $resource = SiswaLayakSertifikatResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
'@ | Set-Content -Path "app\Filament\Resources\SiswaLayakSertifikatResource\Pages\ListSiswaLayakSertifikat.php" -Encoding UTF8
Write-Host "app/Filament/Resources/SiswaLayakSertifikatResource/Pages/ListSiswaLayakSertifikat.php" -ForegroundColor Green

# ============================================
# 7. SertifikatController
# ============================================
@'
<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    /**
     * PBI-127: Halaman Sertifikat Siswa
     */
    public function milikSaya()
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            abort(403, 'Akses ditolak.');
        }

        $sertifikat = Sertifikat::where('siswa_id', $siswa->id)
            ->with(['kelas.programKursus', 'penerbit'])
            ->latest('tanggal_terbit')
            ->first();

        return view('sertifikat.show', compact('sertifikat', 'siswa'));
    }

    /**
     * Verifikasi sertifikat via nomor (untuk QR code)
     */
    public function verifikasi(string $nomor)
    {
        $sertifikat = Sertifikat::where('nomor_sertifikat', $nomor)
            ->with(['siswa.user', 'kelas.programKursus', 'penerbit'])
            ->firstOrFail();

        return view('sertifikat.show', compact('sertifikat'));
    }
}
'@ | Set-Content -Path "app\Http\Controllers\SertifikatController.php" -Encoding UTF8
Write-Host "app/Http/Controllers/SertifikatController.php" -ForegroundColor Green

# ============================================
# 8. Blade View Sertifikat
# ============================================
@'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Saya - RoboNesia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .cert-wrapper { box-shadow: none; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="no-print bg-white border-b px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-teal-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="font-semibold text-gray-800">RoboNesia</span>
        </div>
        <span class="text-sm text-gray-500">Sertifikat</span>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="no-print mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Sertifikat Saya</h1>
            <p class="text-gray-500 text-sm mt-1">Bukti penyelesaian program robotika</p>
        </div>

        @if($sertifikat)
        <div class="no-print flex items-center justify-between mb-4">
            <span class="text-xs font-semibold text-gray-400 tracking-widest uppercase">
                Sertifikat #{{ $sertifikat->nomor_sertifikat }}
            </span>
            <div class="flex gap-2">
                <button onclick="window.print()"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak
                </button>
                <button onclick="copyVerifyLink('{{ $sertifikat->verified_url }}')"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Salin Link Verifikasi
                </button>
            </div>
        </div>

        <div class="cert-wrapper bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-teal-400 to-cyan-500"></div>
            <div class="px-12 py-12 text-center">
                <div class="flex items-center justify-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800">RoboNesia Academy</span>
                </div>

                <p class="text-xs font-bold tracking-[0.25em] text-teal-500 uppercase mb-3">
                    Sertifikat Penyelesaian Program
                </p>
                <div class="w-16 h-0.5 bg-teal-400 mx-auto mb-8"></div>

                <p class="text-sm text-gray-500 mb-2">Diberikan kepada:</p>
                <h2 class="text-4xl font-black text-gray-900 tracking-wide uppercase mb-8">
                    {{ $sertifikat->siswa->user->name }}
                </h2>

                <p class="text-sm text-gray-500 mb-2">Telah menyelesaikan program:</p>
                <p class="text-xl font-bold text-teal-500 mb-8">
                    {{ $sertifikat->kelas->programKursus->nama_program ?? $sertifikat->kelas->nama_kelas }}
                </p>

                @php
                    $enrollment = $sertifikat->siswa->enrollments()
                        ->where('kelas_id', $sertifikat->kelas_id)->first();
                @endphp

                <div class="flex justify-center gap-16 mb-12">
                    @if($enrollment?->nilai_akhir)
                    <div class="text-center">
                        <p class="text-xs text-gray-400 mb-1">Nilai Akhir</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $enrollment->nilai_akhir }}/100</p>
                    </div>
                    @endif
                    @if($enrollment?->persentase_kehadiran)
                    <div class="text-center">
                        <p class="text-xs text-gray-400 mb-1">Kehadiran</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $enrollment->persentase_kehadiran }}%</p>
                    </div>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-8">
                    <div class="flex items-end justify-between">
                        <div class="text-left">
                            <p class="text-xs text-gray-400 mb-1">Diterbitkan</p>
                            <p class="text-sm font-semibold text-gray-700">
                                {{ \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-2">{{ $sertifikat->nomor_sertifikat }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-semibold italic text-gray-600 mb-1">
                                {{ $sertifikat->penerbit->name }}
                            </p>
                            <div class="border-t border-gray-300 pt-1">
                                <p class="text-xs text-gray-400">{{ $sertifikat->penerbit->name }} · Admin</p>
                            </div>
                        </div>
                        <div class="w-16 h-16 bg-teal-50 rounded-full flex items-center justify-center border-2 border-teal-100">
                            @if($sertifikat->qr_code)
                                <img src="{{ $sertifikat->qr_code }}" alt="QR" class="w-12 h-12">
                            @else
                                <svg class="w-8 h-8 text-teal-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else
        <div class="bg-white rounded-2xl shadow-md p-16 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada sertifikat</h3>
            <p class="text-sm text-gray-400">Selesaikan program untuk mendapatkan sertifikat.</p>
        </div>
        @endif
    </div>

    <script>
        function copyVerifyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link verifikasi berhasil disalin!');
            });
        }
    </script>
</body>
</html>
'@ | Set-Content -Path "resources\views\sertifikat\show.blade.php" -Encoding UTF8
Write-Host "resources/views/sertifikat/show.blade.php" -ForegroundColor Green

Write-Host ""
Write-Host "Semua file berhasil dibuat!" -ForegroundColor Yellow
Write-Host ""
Write-Host "Langkah selanjutnya:" -ForegroundColor Cyan
Write-Host "1. Tambahkan route ke routes/web.php" -ForegroundColor White
Write-Host "2. Jalankan: php artisan optimize:clear" -ForegroundColor White
Write-Host "3. Jalankan: php artisan serve" -ForegroundColor White
