# Production Deployment & Cache Issues - Troubleshooting Guide

## 🔥 EMERGENCY: Route Not Defined Error

If you see error: `Route [admin.collective-import.index] not defined`, follow these steps:

### **STEP 1: Clear Laravel Cache (PRODUCTION SERVER)**

**For Linux/Ubuntu Server:**
```bash
cd /home/amazingsultrarun/public_html
chmod +x clear-cache-production.sh
./clear-cache-production.sh
```

**For Windows Server:**
```cmd
cd C:\path\to\project
clear-cache-production.bat
```

**Manual Commands (if scripts fail):**
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan clear-compiled

# Then re-cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **STEP 2: Verify Routes Are Loaded**
```bash
php artisan route:list | grep collective-import
```

**Expected Output:**
```
GET|HEAD  admin/collective-import admin.collective-import.index
POST      admin/collective-import admin.collective-import.import  
GET|HEAD  admin/collective-import/template admin.collective-import.template
```

### **STEP 3: Check File Permissions (Linux)**
```bash
sudo chown -R www-data:www-data /home/amazingsultrarun/public_html
sudo chmod -R 755 /home/amazingsultrarun/public_html
sudo chmod -R 777 /home/amazingsultrarun/public_html/storage
sudo chmod -R 777 /home/amazingsultrarun/public_html/bootstrap/cache
```

## 🛠️ Additional Fixes Applied

### **1. Webhook Controller Fixed** ✅
- Fixed "Attempt to read property id on array" error
- XenditWebhookController now properly handles array of users
- Payment success notifications working correctly

### **2. Admin Layout Safety Check** ✅  
- Added `Route::has()` checks to prevent route errors
- Collective Import & Groups menu items will hide if routes not loaded
- Prevents entire admin panel from crashing

### **3. Route Names Corrected** ✅
- Fixed route naming consistency with `admin.` prefix
- All collective routes now properly named
- Matches expectations in view files

## 🔍 Debugging Commands

**Check if routes exist:**
```bash
php artisan route:list | grep admin.collective
```

**Check view cache:**
```bash
ls -la storage/framework/views/
```

**Check route cache:**
```bash
ls -la bootstrap/cache/
```

**Force reload without cache:**
```bash
php artisan serve --no-cache
```

## 📋 Files Modified in This Fix

1. `routes/web.php` - Fixed route names with admin prefix
2. `app/Http/Controllers/Api/XenditWebhookController.php` - Fixed array handling
3. `resources/views/layouts/admin.blade.php` - Added safety checks
4. `clear-cache-production.sh` - Cache clear script for Linux
5. `clear-cache-production.bat` - Cache clear script for Windows

## 🎯 Final Status

✅ **Route errors should be resolved** after cache clear  
✅ **Webhook processing working** correctly  
✅ **Admin panel protected** from route errors  
✅ **Production deployment** ready  

## 📞 Contact

If issues persist after cache clear, the problem may be:
1. File permissions on production server
2. PHP version compatibility  
3. Missing composer dependencies
4. Server configuration issues

Run the cache clear script first - this resolves 90% of route issues after deployment.
