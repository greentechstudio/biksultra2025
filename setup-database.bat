@echo off
echo =====================================
echo  Database Setup - Dashboard App
echo =====================================
echo.
echo Setting up database...
echo.

echo 1. Running migrations...
php artisan migrate --force

echo.
echo 2. Seeding database with sample data...
php artisan db:seed --force

echo.
echo =====================================
echo Database setup completed!
echo =====================================
echo.
echo You can now start the server with:
echo start-server.bat
echo.
pause
