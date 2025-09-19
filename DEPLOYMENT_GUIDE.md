# DEPLOYMENT GUIDE - CPANEL

## 📁 File Structure yang di-upload
File ini berisi source code utama Laravel tanpa dependencies. Dependencies akan diinstall di server.

## 🚀 Langkah-langkah Deployment

### 1. Upload File
- Upload dan extract file zip ini ke direktori public_html atau subdomain folder
- Pastikan semua file terupload dengan benar

### 2. Setup Environment
```bash
# Copy file environment
cp .env.example .env

# Edit file .env sesuai dengan konfigurasi server Anda:
# - DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
# - APP_URL (sesuai domain Anda)
# - MAIL_* (konfigurasi email)
# - XENDIT_* (untuk payment gateway)
# - WHATSAPP_* (untuk notifikasi WhatsApp)
```

### 3. Install Dependencies
```bash
# Install Composer (jika belum ada)
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install Laravel dependencies
composer install --no-dev --optimize-autoloader

# Generate application key
php artisan key:generate
```

### 4. Setup Database
```bash
# Run database migrations
php artisan migrate

# (Optional) Seed database dengan data sample
php artisan db:seed
```

### 5. Setup Storage & Permissions
```bash
# Create storage link untuk file uploads
php artisan storage:link

# Set permissions (pastikan web server bisa write)
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 6. Optimize untuk Production
```bash
# Cache konfigurasi
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 7. Setup Cron Job (untuk background tasks)
Tambahkan di cPanel Cron Jobs:
```bash
# Jalankan setiap menit
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 8. Setup Queue Worker (jika menggunakan jobs)
```bash
# Untuk background processing
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

## 🔧 Konfigurasi Penting

### .htaccess (sudah disertakan di folder public)
Pastikan .htaccess di folder public aktif untuk URL rewriting.

### File Permissions
- storage/ : 755 atau 775
- bootstrap/cache/ : 755 atau 775
- .env : 644 (read-only untuk security)

### Database
- Buat database baru di cPanel MySQL
- Import SQL jika ada, atau jalankan php artisan migrate

## 📱 Fitur Utama Aplikasi

### Event Management
- Konfigurasi event melalui .env file
- Dynamic content berdasarkan konfigurasi
- Support multiple event dengan mudah

### Payment Integration
- Xendit payment gateway
- Automatic invoice generation
- Payment confirmation via WhatsApp

### WhatsApp Notifications
- Registration confirmations
- Payment reminders
- Custom message templates

### User Management
- Registration system
- Profile management
- Blood type & jersey size selection

## 🔧 Troubleshooting

### Error 500
- Cek file permissions storage/ dan bootstrap/cache/
- Pastikan .env sudah dikonfigurasi dengan benar
- Cek error logs di storage/logs/

### Database Connection Error
- Verifikasi konfigurasi DB_* di .env
- Pastikan database sudah dibuat
- Test koneksi database

### WhatsApp/Payment tidak berfungsi
- Cek konfigurasi API keys di .env
- Pastikan server bisa akses internet untuk API calls
- Cek logs untuk error messages

## 📧 Support
Untuk bantuan technical, hubungi developer team.

## 🔒 Security Notes
- Jangan expose file .env ke public
- Update APP_KEY setelah deployment
- Set APP_DEBUG=false di production
- Gunakan HTTPS untuk payment processing