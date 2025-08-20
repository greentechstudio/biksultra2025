# WAKAF FEATURE - TEMPORARILY DISABLED

**Tanggal Disable:** 21 Agustus 2025
**Status:** TEMPORARILY DISABLED (dapat dibuka kembali kapan saja)

## Yang Telah Dilakukan

### 1. **Routes** (`routes/web.php`)
- ✅ Routes wakaf di-comment dengan prefix `// TEMPORARILY DISABLED`
- Routes yang di-comment:
  ```php
  // Route::get('/wakaf-register', [AuthController::class, 'showWakafRegister'])->name('register.wakaf');
  // Route::post('/wakaf-register', [AuthController::class, 'registerWakaf'])->name('register.wakaf.post');
  // Route::get('/wakaf-success', [AuthController::class, 'wakafSuccess'])->name('wakaf.success');
  ```

### 2. **Controller Methods** (`app/Http/Controllers/AuthController.php`)
- ✅ Method `showWakafRegister()` di-comment dengan `/* */`
- ✅ Method `registerWakaf()` di-comment dengan `/* */`
- ✅ Method `wakafSuccess()` di-comment dengan `/* */`
- Semua method diberi komentar "TEMPORARILY DISABLED - dapat dibuka kembali jika diperlukan"

### 3. **Landing Page Button** (`resources/views/partials/landing-registration.blade.php`)
- ✅ Button "REGISTER 5K WAKAF" di-comment dengan `{{-- --}}`
- Button masih ada di code tapi tidak ditampilkan

### 4. **Database**
- ✅ Wakaf ticket type set `is_active = false`
- ID: 12, Name: wakaf, Price: Rp 100.000
- Race Category: 5K
- Registered Count: 0 (tidak ada user yang terdaftar)

### 5. **View Files Backup**
- ✅ `register-wakaf.blade.php` → `register-wakaf.blade.php.backup`
- ✅ `wakaf-success.blade.php` → `wakaf-success.blade.php.backup`

### 6. **Scripts untuk Management**
- ✅ `disable_wakaf_temporarily.php` - Script untuk disable
- ✅ `enable_wakaf_temporarily.php` - Script untuk re-enable

## Cara Re-Enable Wakaf

### Langkah Otomatis:
1. Jalankan: `php enable_wakaf_temporarily.php`

### Langkah Manual:
1. **Uncomment Routes** di `routes/web.php` (hapus tanda //)
2. **Uncomment Controller Methods** di `AuthController.php` (hapus /* */)
3. **Uncomment Button** di `landing-registration.blade.php` (hapus {{-- --}})
4. **Clear Cache:**
   ```bash
   php artisan route:clear
   php artisan view:clear
   php artisan config:clear
   ```

## Notes Penting

- ⚠️ **Data Preserved**: Semua data wakaf (ticket type, user registrations) tetap tersimpan
- 🔄 **Reversible**: Proses disable bisa di-reverse kapan saja tanpa kehilangan data
- 📁 **Backup Available**: File view di-backup, bisa dipulihkan jika diperlukan
- 🚫 **User Impact**: User tidak bisa akses halaman wakaf (akan dapat 404 error)

## Current Status
- Wakaf ticket type: INACTIVE
- Routes: COMMENTED OUT
- Controller methods: COMMENTED OUT
- Landing page button: HIDDEN
- View files: BACKED UP

## Files Yang Dimodifikasi
1. `routes/web.php`
2. `app/Http/Controllers/AuthController.php`
3. `resources/views/partials/landing-registration.blade.php`
4. Database: `ticket_types` table (is_active = false untuk wakaf)

---
**Contact:** Jika perlu re-enable wakaf, ikuti instruksi di atas atau jalankan `enable_wakaf_temporarily.php`
