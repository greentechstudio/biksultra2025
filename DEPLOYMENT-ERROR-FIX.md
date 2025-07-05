# 🚨 Deployment Error Fix Guide

## Error Description
```
Illuminate\Http\Client\PendingRequest::withBasicAuth(): Argument #1 ($username) must be of type string, null given
```

## Root Cause
The error occurs when the `XENDIT_SECRET_KEY` environment variable is not properly set in the production environment, causing the XenditService to try to authenticate with a null value.

## 🔧 Solution Steps

### 1. Check Environment Variables on Server
Run this command on your server:
```bash
php artisan deployment:diagnose
```

### 2. Verify .env File on Server
Make sure your production `.env` file contains:
```env
# Xendit Configuration
XENDIT_BASE_URL=https://api.xendit.co
XENDIT_SECRET_KEY=your_actual_secret_key_here
XENDIT_WEBHOOK_TOKEN=your_webhook_token_here
XENDIT_ENVIRONMENT=live

# Payment Configuration
REGISTRATION_FEE=150000
```

### 3. Clear Configuration Cache
After updating the `.env` file, run:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 4. Test Configuration
```bash
php artisan xendit:test
```

### 5. Check File Permissions
Ensure these directories are writable:
- `storage/logs`
- `storage/app`
- `storage/framework`
- `bootstrap/cache`

### 6. Environment-Specific Issues

#### For Shared Hosting:
- Make sure `.env` file is in the root directory
- Check if your hosting provider supports environment variables
- Some hosts require variables in `config/xendit.php` directly

#### For VPS/Dedicated Server:
- Check if web server user has read access to `.env` file
- Verify PHP CLI vs web server use same environment
- Check if SELinux or similar security policies are blocking access

### 7. Alternative Configuration Method
If environment variables aren't working, you can set values directly in `config/xendit.php`:

```php
<?php

return [
    'base_url' => env('XENDIT_BASE_URL', 'https://api.xendit.co'),
    'secret_key' => env('XENDIT_SECRET_KEY', 'your_fallback_key_here'),
    'webhook_token' => env('XENDIT_WEBHOOK_TOKEN', 'your_fallback_token_here'),
    // ... rest of config
];
```

⚠️ **WARNING**: Never commit actual keys to version control!

### 8. Debug Steps
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server logs (nginx/apache)
3. Enable debug mode temporarily: `APP_DEBUG=true`
4. Test with a simple registration

### 9. Security Considerations
- Use different keys for development vs production
- Set `XENDIT_ENVIRONMENT=live` for production
- Enable webhook signature verification
- Use HTTPS for webhook URLs

## 📋 Production Deployment Checklist

- [ ] `.env` file properly configured
- [ ] Database migrated: `php artisan migrate`
- [ ] Config cached: `php artisan config:cache`
- [ ] Routes cached: `php artisan route:cache`
- [ ] Views cached: `php artisan view:cache`
- [ ] Composer optimized: `composer install --optimize-autoloader --no-dev`
- [ ] File permissions set correctly
- [ ] Xendit webhook URL configured in dashboard
- [ ] WhatsApp API configured
- [ ] SSL certificate installed

## 🔍 Common Issues

### Issue 1: Environment Variables Not Loading
**Symptoms**: Config shows 'NOT SET' for all variables
**Solution**: Check `.env` file location and permissions

### Issue 2: Wrong Environment Values
**Symptoms**: Config shows values but they're incorrect
**Solution**: Clear config cache and verify `.env` file

### Issue 3: Service Instantiation Fails
**Symptoms**: XenditService constructor throws exception
**Solution**: Check if all required config values are present

### Issue 4: Database Connection Issues
**Symptoms**: Migration or user creation fails
**Solution**: Check database credentials and server connectivity

## 📞 Support
If you continue to have issues:
1. Run `php artisan deployment:diagnose` and share the output
2. Check the Laravel logs for detailed error messages
3. Verify your Xendit dashboard configuration
4. Test with a simple invoice creation

---

**Last Updated**: July 2025
**Version**: 1.0
