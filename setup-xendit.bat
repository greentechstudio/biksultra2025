@echo off
cls
echo ===============================================
echo     XENDIT WEBHOOK SETUP ASSISTANT
echo ===============================================
echo.

echo Step 1: Checking current configuration...
echo.
echo Current APP_URL: %APP_URL%
if "%APP_URL%"=="" (
    echo WARNING: APP_URL not set in environment!
    echo Please set APP_URL in your .env file
    echo.
)

echo Current Webhook URL would be: %APP_URL%/api/xendit/webhook
echo.

echo Step 2: What you need to do in Xendit Dashboard:
echo.
echo 1. Login to https://dashboard.xendit.co
echo 2. Go to Settings ^> Webhooks
echo 3. Click "Add New Webhook"
echo 4. Set Webhook URL to: %APP_URL%/api/xendit/webhook
echo 5. Select these events:
echo    - invoice.paid
echo    - invoice.expired  
echo    - invoice.failed
echo 6. Copy the Webhook Token and add to .env file
echo.

echo Step 3: For Development (using ngrok):
echo.
echo If you're using localhost for development:
echo 1. Install ngrok: https://ngrok.com/
echo 2. Run: php artisan serve
echo 3. In another terminal: ngrok http 8000
echo 4. Copy the ngrok URL (e.g., https://abc123.ngrok.io)
echo 5. Use this URL in Xendit: https://abc123.ngrok.io/api/xendit/webhook
echo.

echo Step 4: Testing webhook:
echo.
echo After setup, you can test by:
echo 1. Creating a new registration
echo 2. Making a payment
echo 3. Checking logs: storage/logs/laravel.log
echo 4. Or use: php check-webhook-status.php
echo.

echo Step 5: Troubleshooting:
echo.
echo If webhook doesn't work:
echo 1. Check if URL is accessible from internet
echo 2. Check webhook token in .env file
echo 3. Check Laravel logs for errors
echo 4. Test webhook endpoint manually
echo.

echo ===============================================
echo     SETUP COMPLETED - FOLLOW THE STEPS ABOVE
echo ===============================================
pause
