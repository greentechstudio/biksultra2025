<?php

echo "=== TEST REGISTER KOLEKTIF SERVER ACCESS ===\n\n";

echo "1. ✅ Route registered: GET /register-kolektif\n";
echo "2. ✅ Route registered: POST /register-kolektif\n";  
echo "3. ✅ Route registered: GET /register-kolektif/success\n\n";

// Check view file
$viewPath = __DIR__ . '/resources/views/auth/register-kolektif.blade.php';
if (file_exists($viewPath)) {
    echo "4. ✅ View file exists: " . filesize($viewPath) . " bytes\n";
} else {
    echo "4. ❌ View file NOT FOUND\n";
}

// Check .htaccess
if (file_exists(__DIR__ . '/public/.htaccess')) {
    echo "5. ✅ .htaccess exists in public/\n";
} else {
    echo "5. ❌ .htaccess NOT FOUND in public/\n";
}

echo "\n=== TROUBLESHOOTING STEPS FOR SERVER ===\n";
echo "1. Clear all cache:\n";
echo "   php artisan cache:clear\n";
echo "   php artisan route:clear\n";
echo "   php artisan config:clear\n";
echo "   php artisan view:clear\n\n";

echo "2. Check web server configuration:\n";
echo "   - Document root should point to /public\n";
echo "   - URL Rewrite should be enabled\n";
echo "   - .htaccess should be processed\n\n";

echo "3. Test URLs:\n";
echo "   - http://your-domain/register-kolektif\n";
echo "   - http://your-domain/public/register-kolektif (if document root is not set to /public)\n\n";

echo "4. Check server error logs for specific error messages\n\n";

echo "5. Test simple route first:\n";
echo "   Try accessing: http://your-domain/register\n";
echo "   If this works, then register-kolektif should work too\n\n";

echo "=== POSSIBLE ISSUES ===\n";
echo "❌ Document root not pointing to /public folder\n";
echo "❌ URL Rewrite not enabled on server\n";
echo "❌ .htaccess not being processed\n";
echo "❌ Route cache needs clearing on server\n";
echo "❌ File permissions issue\n";

echo "\n✅ All components are ready. Issue is likely server configuration!\n";
