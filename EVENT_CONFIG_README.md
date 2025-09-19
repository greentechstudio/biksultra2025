# Event Configuration System

## Overview
Sistem konfigurasi event ini memungkinkan Anda untuk menggunakan kodebase yang sama untuk berbagai event dengan hanya mengubah file `.env`. Semua nama event, lokasi, organizer, dan konten yang bersifat hardcode telah dijadikan configurable.

## Cara Penggunaan

### 1. Edit File .env
Ubah konfigurasi event di file `.env`:

```env
# ===== EVENT CONFIGURATION =====
# Event Details
EVENT_NAME="NAMA EVENT ANDA"
EVENT_FULL_NAME="Nama Lengkap Event Anda"
EVENT_YEAR="2025"
EVENT_TAGLINE="Tagline Event Anda"
EVENT_SHORT_NAME="NAMA SINGKAT EVENT"

# Organizer Details  
EVENT_ORGANIZER="Nama Organizer"
EVENT_ORGANIZER_SHORT="ORG"
EVENT_ORGANIZER_REGION="Regional Office"

# Location Details
EVENT_PROVINCE="Provinsi Anda"
EVENT_CITY="Kota Anda"
EVENT_VENUE_MAIN="Venue Utama"
EVENT_VENUE_RUN="Venue Lari"

# Date & Time
EVENT_MONTH="Bulan"
EVENT_DATE_DISPLAY="Tampilan Tanggal"

# Description & Content
EVENT_DESCRIPTION="Deskripsi event lengkap..."
EVENT_DESCRIPTION_SHORT="Deskripsi singkat event..."

# SEO Meta
EVENT_KEYWORDS="keyword1, keyword2, keyword3"
EVENT_META_DESCRIPTION="Meta description untuk SEO..."
```

### 2. Clear Cache
Setelah mengubah `.env`, jalankan:
```bash
php artisan config:clear
php artisan view:clear
```

### 3. Restart Server
Restart Laravel development server:
```bash
php artisan serve
```

## Contoh Penggunaan untuk Event Berbeda

### Event Lari Marathon
```env
EVENT_NAME="JAKARTA MARATHON"
EVENT_FULL_NAME="Jakarta International Marathon"
EVENT_YEAR="2025"
EVENT_TAGLINE="Run for Health & Charity"
EVENT_SHORT_NAME="JAKARTA MARATHON"
EVENT_ORGANIZER="Jakarta Sports Association"
EVENT_ORGANIZER_SHORT="JSA"
EVENT_PROVINCE="DKI Jakarta"
EVENT_CITY="Jakarta"
```

### Event Seminar Bisnis
```env
EVENT_NAME="BIZCON"
EVENT_FULL_NAME="Business Innovation Conference"
EVENT_YEAR="2025"
EVENT_TAGLINE="Innovation Summit & Workshop"
EVENT_SHORT_NAME="BIZCON SUMMIT"
EVENT_ORGANIZER="Indonesia Business Chamber"
EVENT_ORGANIZER_SHORT="IBC"
EVENT_PROVINCE="Jawa Barat"
EVENT_CITY="Bandung"
```

## Files yang Telah Dikonfigurasi

### Views yang Menggunakan Config:
- `resources/views/welcome.blade.php`
- `resources/views/partials/evente/head.blade.php`
- `resources/views/partials/evente/hero.blade.php`
- `resources/views/partials/evente/about.blade.php`
- `resources/views/partials/evente/text-slider.blade.php`
- `resources/views/partials/evente/countdown.blade.php`
- `resources/views/partials/evente/partners.blade.php`
- `resources/views/partials/evente/schedule.blade.php`
- `resources/views/payment/success.blade.php`
- `resources/views/payment/failed.blade.php`

### Config Files:
- `config/event.php` - Master configuration file

## Available Configuration Variables

### Basic Usage:
- `config('event.name')` - Nama event
- `config('event.full_name')` - Nama lengkap event
- `config('event.year')` - Tahun event
- `config('event.organizer.name')` - Nama organizer
- `config('event.location.city')` - Kota event

### Generated Titles:
- `config('event.titles.main')` - Title utama halaman
- `config('event.titles.payment_success')` - Title halaman payment sukses
- `config('event.titles.payment_failed')` - Title halaman payment gagal

### Dynamic Content:
- `config('event.hero.title')` - Title di hero section
- `config('event.hero.subtitle')` - Subtitle di hero section
- `config('event.partners.title')` - Title di section partners
- `config('event.schedule.title')` - Title di section schedule

## Tips Penggunaan

1. **Konsistensi Penamaan**: Gunakan convention yang konsisten untuk semua event
2. **SEO Optimization**: Selalu update keywords dan meta description
3. **Testing**: Selalu test halaman setelah mengubah konfigurasi
4. **Backup**: Simpan konfigurasi `.env` yang sudah bekerja sebagai template

## Troubleshooting

### Config Tidak Berubah?
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

### Error "config not found"?
Pastikan file `config/event.php` ada dan dapat diakses.

### Tampilan Masih Hardcode?
Cek apakah file view yang bersangkutan sudah menggunakan `config()` helper.

## Development

Untuk menambahkan konfigurasi baru:
1. Tambahkan variable di `.env`
2. Tambahkan mapping di `config/event.php`
3. Gunakan `config('event.key')` di blade templates
4. Clear cache dan test

---

**Note**: Sistem ini dirancang untuk fleksibilitas maksimum dengan kompleksitas minimal. Cukup ubah `.env` dan semua content akan terupdate otomatis!