@echo off
REM FORCE CLEAR ALL CACHE FOR PRODUCTION
REM Run this when routes are not being recognized

echo 🚀 FORCE CLEARING ALL LARAVEL CACHE...

REM Clear all cache types
php artisan config:clear
php artisan route:clear  
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

REM Rebuild optimized files
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo ✅ All cache cleared and rebuilt!
echo 🔄 New routes should now be available

REM Check if collective routes exist
echo.
echo 📋 Checking collective routes...
php artisan route:list | findstr collective

echo.
echo 🎯 Cache clearing completed!
pause
