#!/bin/bash

echo "=== REGISTER KOLEKTIF SERVER DIAGNOSTIC ==="
echo "Running on server: $(hostname)"
echo "Date: $(date)"
echo ""

echo "=== 1. CLEARING ALL CACHE ==="
php artisan cache:clear
php artisan route:clear  
php artisan config:clear
php artisan view:clear
echo "✅ Cache cleared"
echo ""

echo "=== 2. CHECKING ROUTES ==="
php artisan route:list --name=register.kolektif
echo ""

echo "=== 3. CHECKING FILE PERMISSIONS ==="
echo "App directory permissions:"
ls -la app/Http/Controllers/AuthController.php
echo ""
echo "View file permissions:"
ls -la resources/views/auth/register-kolektif.blade.php
echo ""
echo "Public directory permissions:"
ls -la public/
echo ""

echo "=== 4. CHECKING WEB SERVER CONFIG ==="
echo "Document Root should point to: $(pwd)/public"
echo "Current working directory: $(pwd)"
echo ""

echo "=== 5. TESTING .HTACCESS ==="
if [ -f "public/.htaccess" ]; then
    echo "✅ .htaccess exists in public/"
    echo "Content:"
    cat public/.htaccess
else
    echo "❌ .htaccess NOT FOUND in public/"
fi
echo ""

echo "=== 6. CHECKING PHP VERSION ==="
php -v
echo ""

echo "=== 7. CHECKING ENVIRONMENT ==="
echo "APP_ENV: $(php artisan env | grep APP_ENV || echo 'Not set')"
echo "APP_DEBUG: $(php artisan env | grep APP_DEBUG || echo 'Not set')"
echo ""

echo "=== 8. MANUAL ROUTE TEST ==="
echo "Testing if Laravel can resolve the route..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
try {
    \$url = \$app->make('url');
    echo 'Register URL: ' . \$url->route('register') . PHP_EOL;
    echo 'Kolektif URL: ' . \$url->route('register.kolektif') . PHP_EOL;
    echo '✅ Route resolution: SUCCESS' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Route resolution: FAILED - ' . \$e->getMessage() . PHP_EOL;
}
"
echo ""

echo "=== DIAGNOSIS COMPLETE ==="
echo "Next steps:"
echo "1. Check web server error logs"
echo "2. Verify document root points to /public"
echo "3. Ensure URL rewriting is enabled"
echo "4. Test with: curl -I http://your-domain/register-kolektif"
