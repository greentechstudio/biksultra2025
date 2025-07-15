@echo off
echo =========================================
echo  Amazing Sultra Run - Cleanup Unpaid Users
echo =========================================
echo.

REM Check if dry-run flag is passed
if "%1"=="--dry-run" (
    echo Running dry-run mode - showing what would be deleted...
    echo.
    php artisan cleanup:unpaid-users --dry-run
) else (
    echo Running cleanup of unpaid users after 24 hours...
    echo.
    php artisan cleanup:unpaid-users
)

echo.
echo =========================================
echo  Cleanup completed
echo =========================================
echo.
echo Additional commands:
echo   cleanup-unpaid-24h.bat --dry-run    : See what would be deleted
echo   cleanup-unpaid-24h.bat              : Actually delete unpaid users
echo.
pause
