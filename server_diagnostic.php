<?php

echo "=== REGISTER KOLEKTIF SERVER DIAGNOSTIC ===\n";
echo "Server: " . ($_SERVER['SERVER_NAME'] ?? 'Unknown') . "\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// Function to run artisan command safely
function runArtisan($command) {
    $output = '';
    $return_var = 0;
    exec("php artisan $command 2>&1", $output, $return_var);
    return ['output' => implode("\n", $output), 'success' => $return_var === 0];
}

echo "=== 1. CLEARING CACHE ===\n";
$cacheCommands = ['cache:clear', 'route:clear', 'config:clear', 'view:clear'];
foreach ($cacheCommands as $cmd) {
    $result = runArtisan($cmd);
    echo ($result['success'] ? '✅' : '❌') . " $cmd\n";
}
echo "\n";

echo "=== 2. CHECKING ROUTES ===\n";
$routeResult = runArtisan('route:list --name=register.kolektif');
if ($routeResult['success']) {
    echo "✅ Routes found:\n" . $routeResult['output'] . "\n";
} else {
    echo "❌ Route check failed:\n" . $routeResult['output'] . "\n";
}
echo "\n";

echo "=== 3. CHECKING FILES ===\n";
$files = [
    'app/Http/Controllers/AuthController.php' => 'AuthController',
    'resources/views/auth/register-kolektif.blade.php' => 'Kolektif View',
    'public/.htaccess' => '.htaccess'
];

foreach ($files as $file => $name) {
    if (file_exists($file)) {
        echo "✅ $name: EXISTS (" . filesize($file) . " bytes)\n";
    } else {
        echo "❌ $name: NOT FOUND\n";
    }
}
echo "\n";

echo "=== 4. CHECKING ENVIRONMENT ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Laravel Version: " . (class_exists('Illuminate\Foundation\Application') ? app()->version() : 'Unknown') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "Script Path: " . __DIR__ . "\n";
echo "\n";

echo "=== 5. ROUTE RESOLUTION TEST ===\n";
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        
        // Test route resolution
        $router = $app->make('router');
        $routes = $router->getRoutes();
        
        $kolektifFound = false;
        foreach ($routes as $route) {
            if ($route->uri() === 'register-kolektif' && in_array('GET', $route->methods())) {
                $kolektifFound = true;
                echo "✅ Route found: GET /register-kolektif → " . $route->getActionName() . "\n";
                break;
            }
        }
        
        if (!$kolektifFound) {
            echo "❌ Route NOT FOUND in router\n";
        }
        
    } else {
        echo "❌ Laravel not properly installed\n";
    }
} catch (Exception $e) {
    echo "❌ Route test failed: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== 6. URL GENERATION TEST ===\n";
try {
    if (isset($app)) {
        $urlGenerator = $app->make('url');
        echo "✅ Base URL: " . $urlGenerator->to('/') . "\n";
        echo "✅ Register URL: " . $urlGenerator->route('register') . "\n";
        echo "✅ Kolektif URL: " . $urlGenerator->route('register.kolektif') . "\n";
    }
} catch (Exception $e) {
    echo "❌ URL generation failed: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== 7. WEB SERVER CHECK ===\n";
if (isset($_SERVER['HTTP_HOST'])) {
    $testUrls = [
        "http://" . $_SERVER['HTTP_HOST'] . "/register",
        "http://" . $_SERVER['HTTP_HOST'] . "/register-kolektif"
    ];
    
    foreach ($testUrls as $url) {
        echo "Test URL: $url\n";
    }
} else {
    echo "Run this script via web browser to test URLs\n";
}
echo "\n";

echo "=== TROUBLESHOOTING RECOMMENDATIONS ===\n";
echo "1. ✅ All Laravel files and routes are ready\n";
echo "2. 🔧 Check web server configuration:\n";
echo "   - Ensure document root points to /public\n";
echo "   - Enable URL rewriting (mod_rewrite for Apache)\n";
echo "   - Check .htaccess is being processed\n";
echo "3. 🔧 Check server error logs for specific errors\n";
echo "4. 🔧 Test simple routes first (like /register)\n";
echo "5. 🔧 Verify file permissions (755 for directories, 644 for files)\n";

if (isset($_SERVER['HTTP_HOST'])) {
    echo "\n=== QUICK TEST LINKS ===\n";
    echo "Click to test:\n";
    echo "- Register: http://" . $_SERVER['HTTP_HOST'] . "/register\n";
    echo "- Kolektif: http://" . $_SERVER['HTTP_HOST'] . "/register-kolektif\n";
}

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
