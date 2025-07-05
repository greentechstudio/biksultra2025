@echo off
echo =====================================
echo  Dashboard App - Laravel WhatsApp
echo =====================================
echo.
echo Clearing caches...
php artisan cache:clear
php artisan config:clear
php artisan view:clear
echo.
echo Starting Laravel Development Server...
echo.
echo Open your browser and go to:
echo http://localhost:8000
echo.
echo Login credentials:
echo Admin: admin@example.com / password
echo User: john@example.com / password
echo.
echo Press Ctrl+C to stop the server
echo.
php artisan serve --port=8000
pause
