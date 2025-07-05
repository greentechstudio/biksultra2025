<?php

/**
 * Test Xendit Integration
 * Run this file to test the Xendit payment integration
 */

require_once 'vendor/autoload.php';

use App\Services\XenditService;
use App\Models\User;

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "🧪 Testing Xendit Integration...\n\n";

// Test 1: Configuration check
echo "1. Checking Xendit configuration...\n";
$requiredVars = ['XENDIT_SECRET_KEY', 'XENDIT_WEBHOOK_TOKEN', 'REGISTRATION_FEE'];
$missingVars = [];

foreach ($requiredVars as $var) {
    if (empty($_ENV[$var])) {
        $missingVars[] = $var;
    }
}

if (!empty($missingVars)) {
    echo "❌ Missing environment variables: " . implode(', ', $missingVars) . "\n";
    echo "Please add them to your .env file\n\n";
    exit(1);
} else {
    echo "✅ All required environment variables are set\n\n";
}

// Test 2: Create test user (if not exists)
echo "2. Creating test user...\n";
try {
    $testUser = User::firstOrCreate(
        ['email' => 'test.xendit@example.com'],
        [
            'name' => 'Test Xendit User',
            'phone' => '628123456789',
            'whatsapp_number' => '628123456789',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'status' => 'pending',
            'gender' => 'Laki-laki',
            'birth_place' => 'Jakarta',
            'birth_date' => '1990-01-01',
            'address' => 'Test Address',
            'jersey_size' => 'L',
            'race_category' => '10K',
            'emergency_contact_1' => 'Emergency Contact',
            'blood_type' => 'O',
            'occupation' => 'Tester',
            'event_source' => 'Test'
        ]
    );
    echo "✅ Test user created/found: ID {$testUser->id}\n\n";
} catch (Exception $e) {
    echo "❌ Failed to create test user: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 3: Create invoice
echo "3. Testing invoice creation...\n";
try {
    $xenditService = new XenditService();
    $result = $xenditService->createInvoice($testUser, 150000, 'Test Registration Fee');
    
    if ($result['success']) {
        echo "✅ Invoice created successfully!\n";
        echo "   Invoice ID: " . $testUser->fresh()->xendit_invoice_id . "\n";
        echo "   Invoice URL: " . $testUser->fresh()->xendit_invoice_url . "\n";
        echo "   External ID: " . $testUser->fresh()->xendit_external_id . "\n\n";
    } else {
        echo "❌ Invoice creation failed: " . $result['error'] . "\n\n";
        if (isset($result['details'])) {
            echo "Details: " . json_encode($result['details'], JSON_PRETTY_PRINT) . "\n\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Exception during invoice creation: " . $e->getMessage() . "\n\n";
}

// Test 4: Webhook verification
echo "4. Testing webhook verification...\n";
try {
    $testPayload = json_encode(['test' => 'data']);
    $testSignature = hash_hmac('sha256', $testPayload, $_ENV['XENDIT_WEBHOOK_TOKEN']);
    
    $xenditService = new XenditService();
    $isValid = $xenditService->verifyWebhookSignature($testPayload, $testSignature);
    
    if ($isValid) {
        echo "✅ Webhook signature verification working\n\n";
    } else {
        echo "❌ Webhook signature verification failed\n\n";
    }
} catch (Exception $e) {
    echo "❌ Exception during webhook verification: " . $e->getMessage() . "\n\n";
}

// Test 5: Database schema check
echo "5. Checking database schema...\n";
try {
    $user = User::first();
    $requiredFields = [
        'xendit_invoice_id', 'xendit_invoice_url', 'xendit_external_id', 
        'payment_status', 'payment_requested_at', 'xendit_callback_data'
    ];
    
    $schema = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    $missingFields = array_diff($requiredFields, $schema);
    
    if (empty($missingFields)) {
        echo "✅ All required database fields exist\n\n";
    } else {
        echo "❌ Missing database fields: " . implode(', ', $missingFields) . "\n";
        echo "Please run: php artisan migrate\n\n";
    }
} catch (Exception $e) {
    echo "❌ Database schema check failed: " . $e->getMessage() . "\n\n";
}

echo "🏁 Test completed!\n\n";

echo "Next steps:\n";
echo "1. Configure webhook URL in Xendit dashboard: " . $_ENV['APP_URL'] . "/api/xendit/webhook\n";
echo "2. Test the full registration flow\n";
echo "3. Monitor logs for any issues\n\n";

echo "To test the webhook endpoint:\n";
echo "curl -X POST " . $_ENV['APP_URL'] . "/api/xendit/webhook \\\n";
echo "  -H \"Content-Type: application/json\" \\\n";
echo "  -H \"x-callback-token: your_webhook_token\" \\\n";
echo "  -d '{\"external_id\":\"" . ($testUser->xendit_external_id ?? 'test-external-id') . "\",\"status\":\"PAID\"}'\n\n";
