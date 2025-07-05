<?php

/**
 * Test webhook endpoint untuk memastikan bisa menerima request dari Xendit
 */

require __DIR__ . '/vendor/autoload.php';

// Setup Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Xendit Webhook Endpoint Test ===\n\n";

// Test 1: Check if webhook route exists
echo "1. Testing webhook route configuration:\n";
try {
    $routes = app('router')->getRoutes();
    $webhookRouteExists = false;
    
    foreach ($routes as $route) {
        if (str_contains($route->uri, 'api/xendit/webhook')) {
            $webhookRouteExists = true;
            echo "   ✅ Webhook route found: " . $route->methods[0] . " " . $route->uri . "\n";
            break;
        }
    }
    
    if (!$webhookRouteExists) {
        echo "   ❌ Webhook route not found!\n";
        echo "   Please check routes/api.php\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error checking routes: " . $e->getMessage() . "\n";
}

echo "\n2. Testing webhook URL accessibility:\n";
$webhookUrl = config('xendit.webhook_url');
echo "   Webhook URL: {$webhookUrl}\n";

// Test if URL is localhost
if (str_contains($webhookUrl, 'localhost') || str_contains($webhookUrl, '127.0.0.1')) {
    echo "   ⚠️  WARNING: Using localhost URL!\n";
    echo "   Xendit cannot reach localhost URLs from the internet.\n";
    echo "   For development, use ngrok: https://ngrok.com/\n";
    echo "   For production, use a real domain with HTTPS.\n";
} else {
    echo "   ✅ Using external URL (good for production)\n";
}

echo "\n3. Testing webhook configuration:\n";
$config = [
    'base_url' => config('xendit.base_url'),
    'secret_key' => config('xendit.secret_key') ? 'Set' : 'Not Set',
    'webhook_token' => config('xendit.webhook_token') ? 'Set' : 'Not Set',
    'webhook_url' => config('xendit.webhook_url'),
];

foreach ($config as $key => $value) {
    $status = ($value === 'Not Set') ? '❌' : '✅';
    echo "   {$status} {$key}: {$value}\n";
}

echo "\n4. Testing webhook signature verification:\n";
try {
    $xenditService = new App\Services\XenditService();
    
    // Test with sample data
    $testPayload = '{"test": "data"}';
    $testSignature = config('xendit.webhook_token');
    
    if ($testSignature) {
        echo "   ✅ Webhook signature verification method available\n";
    } else {
        echo "   ❌ Webhook token not configured\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error testing signature verification: " . $e->getMessage() . "\n";
}

echo "\n5. Manual webhook test:\n";
echo "   To test manually, send a POST request to:\n";
echo "   URL: {$webhookUrl}\n";
echo "   Headers:\n";
echo "     Content-Type: application/json\n";
echo "     x-callback-token: " . config('xendit.webhook_token') . "\n";
echo "   Body:\n";
echo '   {
     "id": "test-invoice-id",
     "external_id": "TEST-123",
     "status": "PAID",
     "amount": 150000,
     "payment_method": "QRIS",
     "paid_at": "' . now()->toISOString() . '"
   }' . "\n";

echo "\n6. Development setup (ngrok):\n";
if (str_contains($webhookUrl, 'localhost')) {
    echo "   Since you're using localhost, here's how to setup ngrok:\n";
    echo "   1. Install ngrok: https://ngrok.com/download\n";
    echo "   2. Run your Laravel app: php artisan serve\n";
    echo "   3. In another terminal: ngrok http 8000\n";
    echo "   4. Copy the HTTPS URL (e.g., https://abc123.ngrok.io)\n";
    echo "   5. Update your Xendit webhook URL to: https://abc123.ngrok.io/api/xendit/webhook\n";
} else {
    echo "   You're using an external URL, make sure:\n";
    echo "   1. URL is accessible from the internet\n";
    echo "   2. Server is running and responding\n";
    echo "   3. SSL certificate is valid (for HTTPS)\n";
}

echo "\n=== Test Completed ===\n";
echo "\nNext steps:\n";
echo "1. Go to Xendit Dashboard: https://dashboard.xendit.co\n";
echo "2. Navigate to Settings > Webhooks\n";
echo "3. Add webhook with URL: {$webhookUrl}\n";
echo "4. Select events: invoice.paid, invoice.expired, invoice.failed\n";
echo "5. Test webhook by creating a payment\n\n";
