<?php

// Debug script untuk masalah register-kolektif
echo "=== DEBUG REGISTER KOLEKTIF ACCESS ===\n\n";

// 1. Check if Laravel app can be bootstrapped
try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Laravel bootstrap: SUCCESS\n";
} catch (Exception $e) {
    echo "❌ Laravel bootstrap: FAILED - " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Check route exists
try {
    $router = app('router');
    $routes = $router->getRoutes();
    
    $kolektifRouteExists = false;
    foreach ($routes as $route) {
        if ($route->uri() === 'register-kolektif' && in_array('GET', $route->methods())) {
            $kolektifRouteExists = true;
            echo "✅ Route GET /register-kolektif: EXISTS\n";
            echo "   - Name: " . $route->getName() . "\n";
            echo "   - Action: " . $route->getActionName() . "\n";
            break;
        }
    }
    
    if (!$kolektifRouteExists) {
        echo "❌ Route GET /register-kolektif: NOT FOUND\n";
    }
} catch (Exception $e) {
    echo "❌ Route check: FAILED - " . $e->getMessage() . "\n";
}

// 3. Check AuthController exists and method exists
try {
    $controller = new App\Http\Controllers\AuthController();
    if (method_exists($controller, 'showRegisterKolektif')) {
        echo "✅ AuthController::showRegisterKolektif: EXISTS\n";
    } else {
        echo "❌ AuthController::showRegisterKolektif: NOT FOUND\n";
    }
} catch (Exception $e) {
    echo "❌ AuthController check: FAILED - " . $e->getMessage() . "\n";
}

// 4. Check view file exists
$viewPath = resource_path('views/auth/register-kolektif.blade.php');
if (file_exists($viewPath)) {
    echo "✅ View file: EXISTS at $viewPath\n";
    echo "   - Size: " . filesize($viewPath) . " bytes\n";
} else {
    echo "❌ View file: NOT FOUND at $viewPath\n";
}

// 5. Check .htaccess or web server config
if (file_exists(__DIR__ . '/public/.htaccess')) {
    echo "✅ .htaccess: EXISTS in public/\n";
} else {
    echo "⚠️  .htaccess: NOT FOUND in public/\n";
}

// 6. Test route resolution
try {
    $url = url('/register-kolektif');
    echo "✅ Route URL generation: $url\n";
} catch (Exception $e) {
    echo "❌ Route URL generation: FAILED - " . $e->getMessage() . "\n";
}

// 7. Check if cache needs to be cleared
echo "\n=== CACHE STATUS ===\n";
try {
    Artisan::call('route:list', ['--name' => 'register.kolektif']);
    $output = Artisan::output();
    if (strpos($output, 'register.kolektif') !== false) {
        echo "✅ Route cache: OK\n";
    } else {
        echo "⚠️  Route cache: May need clearing\n";
    }
} catch (Exception $e) {
    echo "❌ Route cache check: FAILED - " . $e->getMessage() . "\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Clear all cache: php artisan cache:clear\n";
echo "2. Clear route cache: php artisan route:clear\n";
echo "3. Clear config cache: php artisan config:clear\n";
echo "4. Clear view cache: php artisan view:clear\n";
echo "5. Rebuild cache: php artisan optimize\n";
echo "\n6. Check web server error logs\n";
echo "7. Verify document root points to /public\n";
echo "8. Check file permissions\n";

echo "\n=== DIRECT TEST URL ===\n";
echo "Try accessing: " . (isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : 'http://your-domain') . "/register-kolektif\n";
