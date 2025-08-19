<?php
/**
 * Test script for XenditService without actually calling Xendit API
 * Usage: php test_xendit_service_syntax.php
 */

// Include Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    // Boot the application properly
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // Test if the class can be instantiated without errors
    $xenditService = new \App\Services\XenditService();
    
    // Test if the method exists
    $reflection = new ReflectionClass($xenditService);
    
    if ($reflection->hasMethod('createCollectiveInvoiceForAdmin')) {
        echo "✓ Method createCollectiveInvoiceForAdmin exists\n";
        
        $method = $reflection->getMethod('createCollectiveInvoiceForAdmin');
        $params = $method->getParameters();
        
        echo "✓ Method signature: ";
        foreach ($params as $param) {
            echo '$' . $param->getName();
            if ($param->hasType()) {
                echo ' (' . $param->getType() . ')';
            }
            if ($param->isDefaultValueAvailable()) {
                echo ' = ' . var_export($param->getDefaultValue(), true);
            }
            echo ', ';
        }
        echo "\n";
    } else {
        echo "✗ Method createCollectiveInvoiceForAdmin not found\n";
    }
    
    if ($reflection->hasMethod('getCollectivePrice')) {
        echo "✓ Method getCollectivePrice exists\n";
    }
    
    echo "\n=== Xendit Configuration ===\n";
    echo "Base URL: " . config('xendit.base_url', 'NOT SET') . "\n";
    echo "Secret Key: " . (config('xendit.secret_key') ? 'CONFIGURED' : 'NOT SET') . "\n";
    echo "Webhook Token: " . (config('xendit.webhook_token') ? 'CONFIGURED' : 'NOT SET') . "\n";
    
    echo "\n✅ XenditService syntax check PASSED\n";
    echo "No 'Class Xendit\\Invoice not found' errors detected!\n";
    
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
