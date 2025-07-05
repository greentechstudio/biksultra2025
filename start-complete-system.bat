@echo off
echo ==========================================
echo    Start Complete System
echo ==========================================
echo.

echo Starting Laravel services...
echo.

echo 1. Starting Laravel development server...
start "Laravel Server" cmd /c "php artisan serve --host=0.0.0.0 --port=8000"

echo 2. Starting Queue Worker...
start "Queue Worker" cmd /c "php artisan queue:work --verbose --tries=3"

echo 3. Starting Scheduler Worker...
start "Scheduler Worker" cmd /c "php artisan schedule:work --sleep=60"

echo.
echo ✓ All services started!
echo.
echo Services running:
echo - Laravel Server: http://localhost:8000
echo - Queue Worker: Processing background jobs
echo - Scheduler Worker: Running scheduled tasks every minute
echo.
echo Dashboard URLs:
echo - Admin Dashboard: http://localhost:8000/admin/unpaid-registrations
echo - Test Interface: http://localhost:8000/test-unpaid-cleanup.html
echo.
echo To stop all services, close the terminal windows or press Ctrl+C in each.
echo.
pause
