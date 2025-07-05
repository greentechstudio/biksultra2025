@echo off
cls
echo ===============================================
echo     DEPLOYMENT ISSUE FIXER
echo ===============================================
echo.

echo Step 1: Diagnosing current state...
echo.
php artisan deployment:diagnose
echo.

echo Step 2: Clearing all caches...
echo.
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo ✅ Caches cleared!
echo.

echo Step 3: Testing Xendit service...
echo.
php artisan xendit:test
echo.

echo Step 4: Checking file permissions...
echo.
if exist "storage\logs" (
    echo ✅ storage/logs exists
) else (
    echo ❌ storage/logs missing - creating...
    mkdir "storage\logs"
)

if exist "storage\app" (
    echo ✅ storage/app exists
) else (
    echo ❌ storage/app missing - creating...
    mkdir "storage\app"
)

if exist "storage\framework" (
    echo ✅ storage/framework exists
) else (
    echo ❌ storage/framework missing - creating...
    mkdir "storage\framework"
)

if exist "bootstrap\cache" (
    echo ✅ bootstrap/cache exists
) else (
    echo ❌ bootstrap/cache missing - creating...
    mkdir "bootstrap\cache"
)

echo.
echo Step 5: Final verification...
echo.
php artisan deployment:diagnose
echo.

echo ===============================================
echo     DEPLOYMENT FIXER COMPLETE
echo ===============================================
echo.
echo If you're still experiencing issues:
echo 1. Check your .env file configuration
echo 2. Verify Xendit API keys are correct
echo 3. Ensure database is properly configured
echo 4. Check server logs for detailed errors
echo.
echo See DEPLOYMENT-ERROR-FIX.md for detailed guide
echo.
pause
