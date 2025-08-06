<?php

/**
 * PHP Path Detection for Cronjob Setup
 * File: detect_php_path.php
 * Purpose: Auto-detect the correct PHP path for cPanel cronjob
 */

echo "=== ASR PHP PATH DETECTION ===\n\n";

// Common PHP paths in shared hosting
$commonPaths = [
    '/usr/local/bin/php',
    '/usr/bin/php',
    '/opt/cpanel/ea-php81/root/usr/bin/php',
    '/opt/cpanel/ea-php80/root/usr/bin/php',
    '/opt/cpanel/ea-php82/root/usr/bin/php',
    '/opt/cpanel/ea-php74/root/usr/bin/php',
    '/usr/local/php81/bin/php',
    '/usr/local/php80/bin/php',
    '/usr/local/lsws/lsphp81/bin/php',
    'php' // Default PATH
];

$workingPaths = [];

echo "🔍 Testing PHP paths...\n\n";

foreach ($commonPaths as $path) {
    // Test if path exists and is executable
    $output = [];
    $returnCode = 0;
    
    exec("$path --version 2>/dev/null", $output, $returnCode);
    
    if ($returnCode === 0 && !empty($output)) {
        $version = $output[0];
        $workingPaths[] = [
            'path' => $path,
            'version' => $version
        ];
        
        echo "✅ $path\n";
        echo "   Version: $version\n\n";
    } else {
        echo "❌ $path (not found or not executable)\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";

if (empty($workingPaths)) {
    echo "⚠️  NO WORKING PHP PATHS FOUND!\n\n";
    echo "Troubleshooting:\n";
    echo "1. Contact your hosting provider for correct PHP path\n";
    echo "2. Check cPanel PHP version settings\n";
    echo "3. Try using just 'php' in cronjob command\n\n";
} else {
    echo "🎉 WORKING PHP PATHS FOUND:\n\n";
    
    foreach ($workingPaths as $index => $phpInfo) {
        echo ($index + 1) . ". {$phpInfo['path']}\n";
        echo "   {$phpInfo['version']}\n\n";
    }
    
    // Recommend the best path
    $recommendedPath = $workingPaths[0]['path'];
    echo "🏆 RECOMMENDED PATH: $recommendedPath\n\n";
    
    // Generate ready-to-use cronjob commands
    echo "📋 READY-TO-USE CRONJOB COMMANDS:\n\n";
    
    $username = '[USERNAME]'; // Placeholder
    
    echo "1. Basic Cleanup (Every 30 minutes):\n";
    echo "*/30 * * * * cd /home/$username/public_html && $recommendedPath artisan registrations:process-unpaid >/dev/null 2>&1\n\n";
    
    echo "2. With Logging:\n";
    echo "*/30 * * * * cd /home/$username/public_html && $recommendedPath artisan registrations:process-unpaid >> /home/$username/asr_cleanup.log 2>&1\n\n";
    
    echo "3. Business Hours Only:\n";
    echo "*/30 8-18 * * 1-5 cd /home/$username/public_html && $recommendedPath artisan registrations:process-unpaid >/dev/null 2>&1\n\n";
    
    echo "⚠️  Remember to replace [USERNAME] with your actual cPanel username!\n\n";
}

// Test Laravel artisan if we're in the right directory
if (file_exists('./artisan')) {
    echo str_repeat("=", 50) . "\n";
    echo "🧪 TESTING LARAVEL ARTISAN...\n\n";
    
    foreach ($workingPaths as $phpInfo) {
        $path = $phpInfo['path'];
        echo "Testing: $path artisan --version\n";
        
        $output = [];
        $returnCode = 0;
        exec("$path artisan --version 2>/dev/null", $output, $returnCode);
        
        if ($returnCode === 0 && !empty($output)) {
            echo "✅ SUCCESS: " . implode(' ', $output) . "\n\n";
            
            // Test our specific command
            echo "Testing cleanup command (dry-run):\n";
            exec("$path artisan registrations:process-unpaid --dry-run 2>&1", $cleanupOutput, $cleanupReturn);
            
            if ($cleanupReturn === 0) {
                echo "✅ Cleanup command works!\n";
                echo "Sample output:\n" . implode("\n", array_slice($cleanupOutput, 0, 5)) . "\n\n";
            } else {
                echo "❌ Cleanup command failed\n";
                echo "Error: " . implode("\n", $cleanupOutput) . "\n\n";
            }
            
            break; // Use first working PHP for testing
        } else {
            echo "❌ FAILED\n\n";
        }
    }
} else {
    echo "\n⚠️  artisan file not found in current directory\n";
    echo "Make sure to run this script from your Laravel project root\n\n";
}

// Environment check
echo str_repeat("=", 50) . "\n";
echo "🔧 ENVIRONMENT CHECK:\n\n";

echo "Current Directory: " . getcwd() . "\n";
echo "User: " . get_current_user() . "\n";
echo "PHP Version (current): " . PHP_VERSION . "\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . "\n\n";

// Check Laravel files
$laravelFiles = ['.env', 'artisan', 'composer.json', 'app/Http/Controllers/AuthController.php'];
echo "Laravel Files Check:\n";
foreach ($laravelFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file\n";
    } else {
        echo "❌ $file (missing)\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "📝 NEXT STEPS:\n\n";
echo "1. Copy the recommended cronjob command above\n";
echo "2. Replace [USERNAME] with your cPanel username\n";
echo "3. Login to cPanel → Cron Jobs\n";
echo "4. Add new cronjob with the command\n";
echo "5. Test and monitor the logs\n\n";
echo "🎯 Setup Complete!\n";

?>
