@echo off
setlocal

set "NGROK_DOMAIN=hardcover-mounted-prowler.ngrok-free.dev"
set "LOCAL_PORT=8000"

echo ==========================================
echo  RT/RW SYSTEM - NGROK ONLY
echo ==========================================
echo.
echo Public URL: https://%NGROK_DOMAIN%
echo Forwarding: http://127.0.0.1:%LOCAL_PORT%
echo.

tasklist /FI "IMAGENAME eq ngrok.exe" | find /I "ngrok.exe" >nul
if not errorlevel 1 exit /b 0

echo Menjalankan ngrok...
ngrok http --domain=%NGROK_DOMAIN% %LOCAL_PORT% >nul 2>&1

exit /b 0