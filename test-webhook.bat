@echo off
cls
echo ===============================================
echo     XENDIT WEBHOOK TESTER
echo ===============================================
echo.

echo Choose an option:
echo [1] Test webhook with existing user
echo [2] Check pending payments
echo [3] Update payment status manually
echo [4] Test webhook endpoint
echo [5] Check specific user status
echo [6] Exit
echo.

set /p choice="Enter your choice (1-6): "

if "%choice%"=="1" (
    echo.
    echo Testing webhook with existing user...
    php artisan xendit:test-webhook
    echo.
    pause
) else if "%choice%"=="2" (
    echo.
    echo Checking pending payments...
    curl -s -X GET "http://localhost/api/payment/pending" | php -r "echo json_encode(json_decode(file_get_contents('php://stdin')), JSON_PRETTY_PRINT);"
    echo.
    pause
) else if "%choice%"=="3" (
    echo.
    set /p user_id="Enter User ID: "
    set /p status="Enter Status (paid/expired/failed): "
    echo.
    echo Updating payment status...
    curl -s -X POST "http://localhost/api/payment/status" -H "Content-Type: application/json" -d "{\"user_id\":\"%user_id%\",\"status\":\"%status%\"}" | php -r "echo json_encode(json_decode(file_get_contents('php://stdin')), JSON_PRETTY_PRINT);"
    echo.
    pause
) else if "%choice%"=="4" (
    echo.
    echo Testing webhook endpoint...
    curl -s -X POST "http://localhost/api/xendit/webhook" -H "Content-Type: application/json" -H "x-callback-token: your_webhook_token_here" -d "{\"external_id\":\"test-external-id\",\"status\":\"PAID\",\"amount\":150000}" | php -r "echo json_encode(json_decode(file_get_contents('php://stdin')), JSON_PRETTY_PRINT);"
    echo.
    pause
) else if "%choice%"=="5" (
    echo.
    set /p user_id="Enter User ID: "
    echo.
    echo Checking user status...
    curl -s -X GET "http://localhost/api/payment/status?user_id=%user_id%" | php -r "echo json_encode(json_decode(file_get_contents('php://stdin')), JSON_PRETTY_PRINT);"
    echo.
    pause
) else if "%choice%"=="6" (
    echo.
    echo Goodbye!
    exit
) else (
    echo.
    echo Invalid choice!
    pause
)

goto :EOF
