@echo off
setlocal

set "NGROK_DOMAIN=hardcover-mounted-prowler.ngrok-free.dev"
set "LOCAL_PORT=8000"

cd /d "%~dp0"
php artisan optimize:clear

netstat -ano | findstr /R /C:":%LOCAL_PORT% .*LISTENING" >nul
if errorlevel 1 (
	echo Server Laravel belum aktif, menjalankan server lokal...
	start "RT-RW Laravel" /min cmd /c "php artisan serve --host=127.0.0.1 --port=%LOCAL_PORT%"
	timeout /t 2 /nobreak >nul
)

echo ==========================================
echo  RT/RW SYSTEM - NGROK ONLY
echo ==========================================
echo.
echo Public URL: https://%NGROK_DOMAIN%
echo Forwarding: http://127.0.0.1:%LOCAL_PORT%
echo.

tasklist /FI "IMAGENAME eq ngrok.exe" | find /I "ngrok.exe" >nul
if not errorlevel 1 (
	echo Ngrok sudah berjalan. Buka https://%NGROK_DOMAIN%
	start "" "https://%NGROK_DOMAIN%/login"
	exit /b 0
)

echo Menjalankan ngrok...
ngrok http --domain=%NGROK_DOMAIN% %LOCAL_PORT% >nul 2>&1

exit /b 0