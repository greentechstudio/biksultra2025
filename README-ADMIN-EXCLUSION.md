# Dashboard Admin - Admin Exclusion Update

## Problem
Dashboard admin sebelumnya menghitung semua user termasuk admin dalam statistik, padahal admin tidak seharusnya masuk dalam hitungan peserta event.

## Solution
Mengupdate semua query di `DashboardController` untuk mengecualikan user dengan role 'admin' dari statistik.

## Changes Made

### 1. Basic Statistics (adminDashboard method)
```php
// Before
'total_users' => User::count(),
'verified_users' => User::where('whatsapp_verified', true)->count(),
'paid_users' => User::where('payment_confirmed', true)->count(),
'pending_users' => User::where('payment_confirmed', false)->count(),

// After  
'total_users' => User::where('role', '!=', 'admin')->count(),
'verified_users' => User::where('role', '!=', 'admin')->where('whatsapp_verified', true)->count(),
'paid_users' => User::where('role', '!=', 'admin')->where('payment_confirmed', true)->count(),
'pending_users' => User::where('role', '!=', 'admin')->where('payment_confirmed', false)->count(),
```

### 2. Race Category Statistics
```php
// Before
$totalParticipants = User::where('race_category', $category->name)->count();
$confirmedParticipants = User::where('race_category', $category->name)
                           ->where('payment_confirmed', true)->count();

// After
$totalParticipants = User::where('race_category', $category->name)
                        ->where('role', '!=', 'admin')->count();
$confirmedParticipants = User::where('race_category', $category->name)
                           ->where('role', '!=', 'admin')
                           ->where('payment_confirmed', true)->count();
```

### 3. Recent Registrations
```php
// Before
$recentUsers = User::latest()->limit(5)->get();

// After
$recentUsers = User::where('role', '!=', 'admin')->latest()->limit(5)->get();
```

### 4. Users Management Page
```php
// Before
$users = User::latest()->paginate(10);

// After
$users = User::where('role', '!=', 'admin')->latest()->paginate(10);
```

## Testing Results
- Total Users: 3 (1 admin + 2 regular users)
- Dashboard shows: 2 users (admin excluded)
- Statistics properly exclude admin from all counts
- Race category statistics only count regular users

## Files Modified
- `app/Http/Controllers/DashboardController.php`

## Impact
✅ Dashboard statistics now accurately reflect only actual event participants
✅ Admin users are properly excluded from all participant counts
✅ Revenue calculations are more accurate
✅ User management page doesn't show admin users to other admins
