# Deployment Commands for Server

## After pulling from Git, run these commands:

```bash
# Clear all caches
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear

# Regenerate optimized files (optional, for production)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check if routes are properly registered
php artisan route:list --name=admin.recent
```

## Common Issues and Solutions:

### Route not defined error:
1. Check if route exists: `php artisan route:list | grep export`
2. Clear route cache: `php artisan route:clear`
3. If using route cache, regenerate: `php artisan route:cache`

### Environment differences:
- Local: Routes may work without cache
- Server: May require proper cache clearing/regeneration

### Debugging route issues:
```bash
php artisan route:list --name=admin
```

This will show all admin routes and verify the export route exists.
