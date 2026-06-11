# Fix BOM character dari semua file PHP sertifikat

$files = @(
    "app\Models\Sertifikat.php",
    "app\Services\SertifikatService.php",
    "app\Filament\Resources\SertifikatResource.php",
    "app\Filament\Resources\SertifikatResource\Pages\ListSertifikat.php",
    "app\Filament\Resources\SiswaLayakSertifikatResource.php",
    "app\Filament\Resources\SiswaLayakSertifikatResource\Pages\ListSiswaLayakSertifikat.php",
    "app\Http\Controllers\SertifikatController.php"
)

foreach ($file in $files) {
    if (Test-Path $file) {
        $content = [System.IO.File]::ReadAllText($file)
        # Hapus BOM jika ada
        if ($content.StartsWith([char]0xFEFF)) {
            $content = $content.Substring(1)
        }
        [System.IO.File]::WriteAllText((Resolve-Path $file).Path, $content, [System.Text.UTF8Encoding]::new($false))
        Write-Host "Fixed: $file" -ForegroundColor Green
    } else {
        Write-Host "Not found: $file" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "Selesai! Sekarang jalankan: php artisan optimize:clear" -ForegroundColor Yellow
