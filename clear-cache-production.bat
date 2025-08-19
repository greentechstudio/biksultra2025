@echo off
REM Production Cache Clear Script for Amazing Sultra Run (Windows)
REM Run this script on production server to clear all Laravel caches

echo 🚀 Starting Laravel Cache Clear for Production...

REM Clear route cache
echo 📍 Clearing route cache...
php artisan route:clear

REM Clear config cache  
echo ⚙️ Clearing config cache...
php artisan config:clear

REM Clear view cache
echo 👁️ Clearing view cache...
php artisan view:clear

REM Clear application cache
echo 🗂️ Clearing application cache...
php artisan cache:clear

REM Clear compiled classes
echo 🔧 Clearing compiled classes...
php artisan clear-compiled

REM Optimize for production
echo ⚡ Optimizing for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ✅ Laravel cache cleared and optimized successfully!
echo 🎯 Routes should now be working properly.

REM Check if routes are properly loaded
echo.
echo 🔍 Checking admin.collective-import routes...
php artisan route:list | findstr collective-import

echo.
echo ✅ Cache clear completed!
