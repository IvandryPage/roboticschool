@echo off
setlocal

echo ===============================
echo   Review Branch: %1
echo ===============================

if "%1"=="" (
    echo Error: Nama branch belum diisi!
    echo.
    echo Cara pakai biasa:
    echo   review.bat PB-09
    echo.
    echo Cara pakai dengan seed:
    echo   review.bat PB-09 seed
    exit /b
)

echo [1/10] Membersihkan file Filament assets...
call git clean -fd public/css/filament public/fonts/filament public/js/filament

echo [2/10] Reset SEMUA perubahan lokal (cache, lock file, dll)...
call git checkout -- .
call git clean -fd bootstrap/cache

echo [3/10] Pindah ke branch %1...
call git switch %1

echo [4/10] Pull update terbaru...
call git pull

echo [5/10] Install composer packages...
call composer install

echo [6/10] Install npm packages...
call npm install

echo [7/10] Build CSS/JS assets (Vite)...
call npm run build

if /I "%2"=="seed" (
    echo [8/10] Migration FRESH + SEED ^(reset total database^)...
    call php artisan migrate:fresh --seed
) else (
    echo [8/10] Migration biasa...
    call php artisan migrate
)

echo [9/10] Build Filament assets...
call php artisan filament:assets

echo [10/10] Clear semua cache lama biar tidak ada sisa dari branch sebelumnya...
call php artisan optimize:clear

echo ===============================
echo   Menjalankan server...
echo ===============================
call php artisan serve

endlocal