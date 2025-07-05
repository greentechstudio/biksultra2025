@echo off
echo ==============================================
echo    ASR DASHBOARD - SYSTEM CHECK
echo ==============================================
echo.

echo Step 1: Checking database data...
echo.
php check-data.php
echo.

echo Step 2: Checking register controller data...
echo.
php test-register-data.php
echo.

echo Step 3: URL Access Information
echo ==============================================
echo Main Application:
echo - Home: http://localhost/asr/
echo - Register: http://localhost/asr/register
echo - Login: http://localhost/asr/login
echo - Dashboard: http://localhost/asr/dashboard
echo.
echo Testing URLs:
echo - Register Test: http://localhost/asr/test-register-form.html
echo - WhatsApp Test: http://localhost/asr/test-whatsapp-frontend.html
echo - Debug WhatsApp: http://localhost/asr/debug-whatsapp.php?number=628114000805
echo.

echo Step 4: Quick Tests
echo ==============================================
echo [1] Open register form
echo [2] Test WhatsApp validation  
echo [3] Test register data
echo [0] Exit
echo.

set /p choice="Choose test (0-3): "

if "%choice%"=="1" (
    echo Opening register form...
    start http://localhost/asr/register
) else if "%choice%"=="2" (
    echo Opening WhatsApp test...
    start http://localhost/asr/test-whatsapp-frontend.html
) else if "%choice%"=="3" (
    echo Opening register data test...
    start http://localhost/asr/test-register-form.html
) else (
    echo Exiting...
)

echo.
pause
