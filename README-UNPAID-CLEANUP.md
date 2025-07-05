# Sistem Pembersihan Registrasi Tidak Bayar

## Deskripsi
Sistem otomatis untuk menghapus user yang tidak melakukan pembayaran dalam 6 jam dan mengirim pesan pengingat WhatsApp setiap 30 menit.

## Fitur Utama

### 1. Pembersihan Otomatis
- Menghapus user yang tidak membayar setelah 6 jam
- Mengirim notifikasi WhatsApp sebelum penghapusan
- Menyimpan log untuk audit trail

### 2. Pengingat Pembayaran
- Mengirim pengingat WhatsApp setiap 30 menit
- Menghitung sisa waktu pembayaran
- Menampilkan pesan dengan countdown

### 3. Monitoring Admin
- Dashboard untuk monitoring registrasi tidak bayar
- Statistik real-time
- Kontrol manual untuk proses pembersihan

## Komponen Sistem

### Jobs
1. **CleanupUnpaidRegistrations** - Menghapus user yang sudah expired
2. **SendPaymentReminders** - Mengirim pengingat pembayaran

### Controllers
1. **UnpaidRegistrationsController** - API untuk testing dan monitoring
2. **WhatsAppQueueController** - Management queue WhatsApp

### Commands
1. **ProcessUnpaidRegistrations** - Command artisan untuk menjalankan proses

### Views
1. **admin/unpaid-registrations.blade.php** - Dashboard admin
2. **admin/whatsapp-queue.blade.php** - Monitor queue WhatsApp

## Instalasi dan Konfigurasi

### 1. Jalankan Migrasi
```bash
php artisan migrate
```

### 2. Konfigurasi Queue
```bash
# Set queue connection di .env
QUEUE_CONNECTION=database

# Jalankan queue worker
php artisan queue:work
```

### 3. Konfigurasi Scheduler
Tambahkan ke crontab:
```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Test Sistem
```bash
# Test dengan dry run
php artisan registrations:process-unpaid --dry-run

# Kirim pengingat saja
php artisan registrations:process-unpaid --reminders-only

# Pembersihan saja
php artisan registrations:process-unpaid --cleanup-only

# Jalankan semua
php artisan registrations:process-unpaid
```

## Kustomisasi Pesan WhatsApp

### Pesan Pengingat
```php
$message = "⏰ *PENGINGAT PEMBAYARAN* ⏰\n\n" .
          "Halo {$user->name},\n\n" .
          "Kami mengingatkan bahwa Anda belum menyelesaikan pembayaran registrasi.\n\n" .
          "📅 Waktu registrasi: " . $user->created_at->format('d/m/Y H:i') . "\n" .
          "⏳ Sisa waktu: " . sprintf('%d jam %d menit', $hoursLeft, $minutesLeft) . "\n\n" .
          "⚠️ Data akan dihapus otomatis jika tidak dibayar dalam 6 jam.\n\n" .
          "Silakan lakukan pembayaran segera atau hubungi admin jika ada kendala.\n\n" .
          "---\n" .
          "Tim Admin";
```

### Pesan Penghapusan
```php
$message = "⚠️ *PEMBERITAHUAN PENTING* ⚠️\n\n" .
          "Halo {$user->name},\n\n" .
          "Data registrasi Anda telah dihapus karena tidak melakukan pembayaran dalam waktu 6 jam.\n\n" .
          "Jika Anda masih ingin mendaftar, silakan lakukan registrasi ulang melalui link berikut:\n" .
          config('app.url') . "/register\n\n" .
          "Terima kasih atas pengertian Anda.\n\n" .
          "---\n" .
          "Tim Admin";
```

## Monitoring dan Logging

### Log Files
- `storage/logs/payment-reminders.log` - Log pengingat pembayaran
- `storage/logs/cleanup-unpaid.log` - Log pembersihan user
- `storage/logs/laravel.log` - Log umum Laravel

### Dashboard Admin
Akses melalui: `/admin/unpaid-registrations`

### API Endpoints
```
GET /api/unpaid-registrations/stats - Statistik
POST /api/unpaid-registrations/dry-run - Preview aksi
POST /api/unpaid-registrations/send-reminders - Kirim pengingat
POST /api/unpaid-registrations/cleanup - Bersihkan expired
GET /api/unpaid-registrations/users - Daftar user tidak bayar
```

## Testing

### File Test
1. `test-unpaid-cleanup.bat` - Script batch untuk testing
2. `public/test-unpaid-cleanup.html` - Interface web untuk testing

### Jalankan Test
```bash
# Jalankan batch file
test-unpaid-cleanup.bat

# Atau buka di browser
http://localhost/test-unpaid-cleanup.html
```

## Keamanan

### Proteksi
- Semua endpoint dilindungi dengan CSRF token
- Log semua aktivitas pembersihan
- Konfirmasi sebelum menghapus user

### Backup
Pertimbangkan untuk membuat backup user sebelum menghapus:
```php
// Backup user data sebelum hapus
$backup = $user->toArray();
\Storage::disk('local')->put('user_backups/' . $user->id . '_' . time() . '.json', json_encode($backup));
```

## Troubleshooting

### Queue Tidak Jalan
```bash
# Restart queue worker
php artisan queue:restart

# Periksa failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Scheduler Tidak Jalan
```bash
# Test scheduler
php artisan schedule:list

# Jalankan manual
php artisan schedule:run
```

### WhatsApp Tidak Terkirim
1. Periksa konfigurasi WhatsApp API
2. Cek log untuk error
3. Pastikan queue worker berjalan

## Konfigurasi Lanjutan

### Ubah Interval Waktu
```php
// Di AppServiceProvider.php
$schedule->job(new SendPaymentReminders())
    ->everyTenMinutes() // Ubah dari everyFiveMinutes()
    ->withoutOverlapping();
```

### Ubah Batas Waktu
```php
// Di Job atau Controller
$cutoffTime = Carbon::now()->subHours(12); // Ubah dari 6 jam
```

### Ubah Interval Pengingat
```php
// Di SendPaymentReminders job
if ($minutesAgo >= 60 && $minutesAgo % 60 === 0) { // Ubah dari 30 menit
    // Kirim pengingat
}
```

## Contoh Penggunaan

### Cek Status Sistem
```bash
php artisan registrations:process-unpaid --dry-run
```

### Jalankan Pembersihan Manual
```bash
php artisan registrations:process-unpaid --cleanup-only
```

### Monitor Melalui Web
1. Buka `http://localhost/admin/unpaid-registrations`
2. Lihat statistik dan daftar user
3. Gunakan tombol aksi untuk kontrol manual

## Dukungan dan Maintenance

### Log Monitoring
- Periksa log secara berkala
- Set up alert untuk error
- Archive log lama

### Performance
- Index database pada kolom yang sering diquery
- Batasi jumlah user yang diproses per batch
- Monitor memory usage untuk job besar

### Backup
- Backup database secara berkala
- Simpan log penting
- Test restore procedure
