#!/bin/bash

# FORCE CLEAR ALL CACHE FOR PRODUCTION
# Run this when routes are not being recognized

echo "🚀 FORCE CLEARING ALL LARAVEL CACHE..."

# Clear all cache types
php artisan config:clear
php artisan route:clear  
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

# Rebuild optimized files
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo "✅ All cache cleared and rebuilt!"
echo "🔄 New routes should now be available"

# Check if collective routes exist
echo ""
echo "📋 Checking collective routes..."
php artisan route:list | grep collective || echo "❌ Collective routes not found"

echo ""
echo "🎯 Cache clearing completed!"
