# Environment Variable Configuration Guide

## ❌ **TIDAK BISA** di file .env:

```env
# ❌ SALAH - Tidak akan bekerja
APP_NAME="{{ config('event.name') }} {{ config('event.year') }}"
APP_NAME=${EVENT_NAME} ${EVENT_YEAR}  # Juga tidak akan bekerja dengan spaces
```

## ✅ **CARA YANG BENAR:**

### **Option 1: Manual Update (Recommended)**
Di file `.env`:
```env
APP_NAME="BIK SULTRA 2025"
EVENT_NAME="BIK SULTRA"
EVENT_YEAR="2025"
```

**Ketika ganti event:**
1. Update `EVENT_NAME` dan `EVENT_YEAR`
2. Update `APP_NAME` manual juga

### **Option 2: Dynamic dalam Code**
Gunakan `config('event.app_name')` di blade templates:
```php
// Di config/event.php sudah ada:
'app_name' => env('EVENT_NAME', 'Event Name') . ' ' . env('EVENT_YEAR', date('Y')),

// Di blade templates:
{{ config('event.app_name') }}
```

### **Option 3: Helper Function**
Buat helper function:
```php
// Di app/helpers.php
function app_name() {
    return config('event.name') . ' ' . config('event.year');
}

// Usage di blade:
{{ app_name() }}
```

## 🎯 **Rekomendasi untuk Penggunaan:**

### **Untuk APP_NAME Laravel:**
```env
APP_NAME="BIK SULTRA 2025"  # Update manual saat ganti event
```

### **Untuk Dynamic Content:**
```php
// Di views gunakan:
{{ config('event.name') }} {{ config('event.year') }}
// atau
{{ config('event.app_name') }}
```

## 📋 **Template .env untuk Event Baru:**

```env
APP_NAME="[EVENT_NAME] [YEAR]"

# ===== EVENT CONFIGURATION =====
EVENT_NAME="[EVENT_NAME]"
EVENT_FULL_NAME="[FULL_EVENT_NAME]"
EVENT_YEAR="[YEAR]"
EVENT_TAGLINE="[TAGLINE]"
EVENT_SHORT_NAME="[SHORT_NAME]"

# Organizer Details
EVENT_ORGANIZER="[ORGANIZER]"
EVENT_ORGANIZER_SHORT="[ORG]"
EVENT_ORGANIZER_REGION="[REGION]"

# Location Details
EVENT_PROVINCE="[PROVINCE]"
EVENT_CITY="[CITY]"
EVENT_VENUE_MAIN="[MAIN_VENUE]"
EVENT_VENUE_RUN="[RUN_VENUE]"

# Date & Time
EVENT_MONTH="[MONTH]"
EVENT_DATE_DISPLAY="[DISPLAY_DATE]"
```

## ⚠️ **Penting:**

1. **File .env** hanya untuk static values
2. **Dynamic values** gunakan `config()` di PHP/Blade
3. **Always test** setelah update konfigurasi
4. **Always clear cache** dengan `php artisan config:clear`

---

**Kesimpulan**: File `.env` tidak mendukung template syntax. Untuk APP_NAME yang dinamis, update manual atau gunakan `config('event.app_name')` di templates.