@echo off
echo =====================================
echo  System Check - Dashboard App
echo =====================================
echo.

echo Checking PHP version...
php --version
echo.

echo Checking Composer...
composer --version
echo.

echo Checking Laravel version...
php artisan --version
echo.

echo Checking database connection...
php artisan tinker --execute="echo 'Database connection: ' . (DB::connection()->getPdo() ? 'OK' : 'FAILED');"
echo.

echo =====================================
echo System check completed!
echo =====================================
echo.
pause
