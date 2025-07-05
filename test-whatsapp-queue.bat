@echo off
echo.
echo ============================================
echo    WHATSAPP QUEUE SYSTEM TEST
echo ============================================
echo.
echo Testing WhatsApp message queue system
echo with 10-second delay between messages
echo.
echo Features yang ditest:
echo 1. Multiple concurrent registrations
echo 2. Message queuing with priority
echo 3. 10-second delay between messages
echo 4. Queue monitoring and management
echo 5. Error handling and retry logic
echo.
echo Opening test pages...
echo.

REM Check if server is running
curl -s http://localhost/asr/public/test-whatsapp-queue.html > nul 2>&1
if %errorlevel% equ 0 (
    echo Server is running. Opening test pages...
    start http://localhost/asr/public/test-whatsapp-queue.html
    timeout /t 3 /nobreak > nul
    start http://localhost/asr/admin/whatsapp-queue
) else (
    echo Server not running. Please start your server first.
    echo Run: php artisan serve
    echo Then run this test again.
)

echo.
echo TEST SCENARIOS:
echo 1. Simulate 5 concurrent registrations
echo 2. Monitor queue processing
echo 3. Check 10-second delays
echo 4. Test priority handling (high vs normal)
echo 5. Test error handling
echo.
echo QUEUE BEHAVIOR:
echo - Messages sent every 10 seconds
echo - High priority messages sent first
echo - Failed messages retried up to 3 times
echo - Queue processing continues automatically
echo.
echo MONITORING:
echo - Real-time queue status
echo - Message processing progress
echo - Failed message tracking
echo - Queue management controls
echo.
echo Instructions:
echo 1. Use "Simulate Registrations" to add messages
echo 2. Monitor queue status in real-time
echo 3. Check WhatsApp Queue Management page
echo 4. Verify 10-second delays between messages
echo 5. Test priority handling
echo.
pause
