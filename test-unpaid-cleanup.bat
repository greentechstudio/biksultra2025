@echo off
echo ==========================================
echo    Test Unpaid Registration Cleanup
echo ==========================================
echo.

:menu
echo Select an option:
echo 1. Show statistics (dry run)
echo 2. Send payment reminders only
echo 3. Cleanup expired registrations only
echo 4. Process all (reminders + cleanup)
echo 5. View current unpaid users
echo 6. Check logs
echo 7. Run Laravel queue worker
echo 8. Exit
echo.

set /p choice="Enter your choice (1-8): "

if "%choice%"=="1" goto stats
if "%choice%"=="2" goto reminders
if "%choice%"=="3" goto cleanup
if "%choice%"=="4" goto processall
if "%choice%"=="5" goto users
if "%choice%"=="6" goto logs
if "%choice%"=="7" goto queue
if "%choice%"=="8" goto exit

echo Invalid choice. Please try again.
echo.
goto menu

:stats
echo.
echo === Current Statistics (Dry Run) ===
php artisan registrations:process-unpaid --dry-run
echo.
pause
goto menu

:reminders
echo.
echo === Sending Payment Reminders ===
php artisan registrations:process-unpaid --reminders-only
echo.
pause
goto menu

:cleanup
echo.
echo === Cleaning Up Expired Registrations ===
echo WARNING: This will DELETE users who have not paid for more than 6 hours!
set /p confirm="Are you sure? (y/N): "
if /i "%confirm%"=="y" (
    php artisan registrations:process-unpaid --cleanup-only
) else (
    echo Operation cancelled.
)
echo.
pause
goto menu

:processall
echo.
echo === Processing All Unpaid Registrations ===
echo This will send reminders AND cleanup expired registrations.
set /p confirm="Are you sure? (y/N): "
if /i "%confirm%"=="y" (
    php artisan registrations:process-unpaid
) else (
    echo Operation cancelled.
)
echo.
pause
goto menu

:users
echo.
echo === Current Unpaid Users ===
php artisan tinker --execute="
use App\Models\User;
use Carbon\Carbon;
$users = User::where('payment_status', '!=', 'paid')->whereNull('payment_date')->get();
foreach($users as $user) {
    $hoursAgo = $user->created_at->diffInHours(now());
    $status = $hoursAgo > 6 ? 'EXPIRED' : 'ACTIVE';
    echo \"- {$user->name} ({$user->phone}) - {$hoursAgo}h ago [{$status}]\n\";
}
echo \"Total: \" . $users->count() . \" users\n\";
"
echo.
pause
goto menu

:logs
echo.
echo === Recent Logs ===
echo.
echo Payment Reminders Log:
if exist "storage\logs\payment-reminders.log" (
    powershell "Get-Content storage\logs\payment-reminders.log | Select-Object -Last 10"
) else (
    echo No payment reminders log found.
)
echo.
echo Cleanup Log:
if exist "storage\logs\cleanup-unpaid.log" (
    powershell "Get-Content storage\logs\cleanup-unpaid.log | Select-Object -Last 10"
) else (
    echo No cleanup log found.
)
echo.
echo Laravel Log:
if exist "storage\logs\laravel.log" (
    powershell "Get-Content storage\logs\laravel.log | Select-String 'unpaid|payment|cleanup' | Select-Object -Last 10"
) else (
    echo No Laravel log found.
)
echo.
pause
goto menu

:queue
echo.
echo === Starting Laravel Queue Worker ===
echo This will process background jobs including payment reminders and cleanup.
echo Press Ctrl+C to stop the queue worker.
echo.
php artisan queue:work --verbose
echo.
pause
goto menu

:exit
echo.
echo Goodbye!
exit /b 0
