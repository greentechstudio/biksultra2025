#!/bin/bash

# Production Cache Clear Script for Amazing Sultra Run
# Run this script on production server to clear all Laravel caches

echo "🚀 Starting Laravel Cache Clear for Production..."

# Clear route cache
echo "📍 Clearing route cache..."
php artisan route:clear

# Clear config cache  
echo "⚙️ Clearing config cache..."
php artisan config:clear

# Clear view cache
echo "👁️ Clearing view cache..."
php artisan view:clear

# Clear application cache
echo "🗂️ Clearing application cache..."
php artisan cache:clear

# Clear compiled classes
echo "🔧 Clearing compiled classes..."
php artisan clear-compiled

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Laravel cache cleared and optimized successfully!"
echo "🎯 Routes should now be working properly."

# Check if routes are properly loaded
echo ""
echo "🔍 Checking admin.collective-import routes..."
php artisan route:list | grep collective-import || echo "❌ Collective import routes not found"

echo ""
echo "✅ Cache clear completed at $(date)"
