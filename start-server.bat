@echo off
echo ==========================================
echo  RT/RW SYSTEM - SERVER STARTER
echo ==========================================
echo.
echo Menjalankan server di http://127.0.0.1:8000
echo.
echo Jangan tutup jendela ini!
echo.

set PHPRC=C:\Users\ASUS\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.NTS.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.ini
cd /d "C:\Users\ASUS\rt-rw"

echo Membuka Microsoft Edge...
start msedge "http://127.0.0.1:8000"

php artisan serve --host=127.0.0.1 --port=8000
pause
