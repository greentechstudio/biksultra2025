@echo off
REM Production Cache Clear Script for Windows
echo 🔧 Clearing Laravel caches on production...

REM Clear various caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

REM Regenerate caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo ✅ All caches cleared and regenerated!
echo 🚀 Application should now use latest routes and configs
pause
