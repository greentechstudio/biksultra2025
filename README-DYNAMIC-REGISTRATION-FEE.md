# Dynamic Registration Fee System

## Overview
Sistem registration fee telah diupdate untuk menggunakan harga yang dinamis berdasarkan kategori lomba (race category) yang dipilih user, bukan lagi menggunakan harga tetap.

## Changes Made

### 1. Database Structure
- Tabel `race_categories` sudah ada dengan kolom:
  - `id` (Primary Key)
  - `name` (Nama kategori, misal: 5K, 10K, 21K, 42K)
  - `description` (Deskripsi kategori)
  - `price` (Harga registrasi untuk kategori ini)
  - `active` (Status aktif)

### 2. Model Updates

#### User Model (`app/Models/User.php`)
- **Ditambahkan relationship**: `raceCategory()` method untuk relasi dengan `RaceCategory` model
- **Ditambahkan attribute**: `getRegistrationFeeAttribute()` untuk mendapatkan harga registrasi berdasarkan race category
- **Relasi**: `belongsTo(RaceCategory::class, 'race_category', 'name')`

#### RaceCategory Model (`app/Models/RaceCategory.php`)
- **Existing model** yang sudah ada untuk manage kategori lomba
- **Relationship**: `users()` method untuk relasi dengan User model

### 3. Service Updates

#### XenditService (`app/Services/XenditService.php`)
- **Updated method**: `createInvoice()` sekarang menggunakan `$user->registration_fee` jika parameter `$amount` adalah `null`
- **Dynamic pricing**: Harga invoice sekarang diambil dari race category user
- **Invoice item name**: Mencakup nama race category dalam item description

### 4. Controller Updates

#### AuthController (`app/Http/Controllers/AuthController.php`)
- **Updated registration flow**: Menggunakan `null` untuk amount sehingga XenditService akan menggunakan harga dari race category
- **Updated WhatsApp message**: Pesan payment link sekarang mencakup informasi race category dan harga yang sesuai

### 5. View Updates

#### Registration Form (`resources/views/auth/register.blade.php`)
- **Race Category dropdown**: Sekarang menampilkan nama kategori, deskripsi, dan harga
- **Format**: `{{ $category->name }} ({{ $category->description }}) - Rp {{ number_format($category->price, 0, ',', '.') }}`

### 6. Database Seeding

#### RaceCategorySeeder (`database/seeders/RaceCategorySeeder.php`)
- **New seeder** untuk populate race categories dengan harga default:
  - 5K: Rp 125.000
  - 10K: Rp 150.000
  - 21K: Rp 175.000
  - 42K: Rp 200.000

#### DatabaseSeeder (`database/seeders/DatabaseSeeder.php`)
- **Updated** untuk memanggil `RaceCategorySeeder`

## How It Works

1. **User Registration**: User memilih race category dari dropdown yang menampilkan harga
2. **Registration Fee Calculation**: Sistem menggunakan `$user->registration_fee` attribute yang mengambil harga dari race category
3. **Xendit Invoice**: Invoice dibuat dengan harga sesuai race category, bukan harga tetap
4. **WhatsApp Notification**: Pesan pembayaran mencakup informasi race category dan harga

## Testing

### Race Categories yang tersedia:
- 5K: Rp 125.000
- 10K: Rp 150.000
- 21K: Rp 175.000
- 42K: Rp 200.000

### Test dengan Laravel Tinker:
```php
php artisan tinker

// Test user dengan race category
$user = App\Models\User::find(1);
$user->race_category = '5K';
$user->registration_fee; // Returns 125000.0

$user->race_category = '42K';
$user->registration_fee; // Returns 200000.0
```

### Test Race Categories:
```php
// Lihat semua race categories
App\Models\RaceCategory::active()->get();

// Lihat harga specific category
App\Models\RaceCategory::where('name', '10K')->first()->price;
```

## Configuration

### Environment Variables (.env)
```
XENDIT_SECRET_KEY=your_xendit_secret_key
XENDIT_PUBLIC_KEY=your_xendit_public_key
XENDIT_WEBHOOK_TOKEN=your_webhook_token
REGISTRATION_FEE=150000  # Default fallback jika race category tidak ditemukan
```

### Xendit Config (`config/xendit.php`)
```php
'registration_fee' => env('REGISTRATION_FEE', 150000), // Default fallback
```

## Benefits

1. **Dynamic Pricing**: Harga registrasi dapat berbeda untuk setiap kategori lomba
2. **Flexible Management**: Admin dapat mengubah harga melalui database tanpa perlu update kode
3. **Accurate Billing**: Invoice dan WhatsApp notification menampilkan harga yang tepat
4. **Scalable**: Mudah menambah kategori lomba baru dengan harga berbeda

## Migration Commands

```bash
# Seed race categories
php artisan db:seed --class=RaceCategorySeeder

# Clear config cache
php artisan config:clear
```

## Error Handling

- **Fallback Price**: Jika race category tidak ditemukan, sistem menggunakan `config('xendit.registration_fee', 150000)`
- **Validation**: Form registrasi memvalidasi bahwa race category harus dipilih
- **Relationship**: Model menggunakan `unsetRelation()` untuk memastikan data terbaru
