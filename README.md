# ASRRUN - Dashboard Aplikasi Registrasi

## 📋 Deskripsi
ASRRUN adalah sistem dashboard aplikasi registrasi yang dilengkapi dengan fitur WhatsApp automation, payment gateway integration, dan sistem manajemen pengguna yang komprehensif.

## ✨ Fitur Utama

### 🔐 Autentikasi & Keamanan
- Login/Register dengan validasi
- Reset password via email
- Role-based access control
- Session management

### 💬 WhatsApp Integration
- **WhatsApp Queue System** - Antrian pesan dengan delay 10 detik
- **Payment Reminders** - Pengingat pembayaran otomatis setiap 30 menit
- **Automated Cleanup** - Penghapusan registrasi tidak bayar setelah 6 jam
- **Message Templates** - Template pesan terstruktur
- **Queue Monitoring** - Dashboard monitoring antrian pesan

### 💳 Payment Gateway
- **Xendit Integration** - Integrasi payment gateway Xendit
- **Webhook Handling** - Penanganan webhook payment
- **Payment Status Tracking** - Tracking status pembayaran
- **Automatic Notifications** - Notifikasi otomatis setelah pembayaran

### 👥 User Management
- **User Registration** - Registrasi pengguna dengan validasi
- **Profile Management** - Manajemen profil pengguna
- **Admin Dashboard** - Dashboard admin untuk monitoring
- **User Statistics** - Statistik pengguna dan aktivitas

### 🧹 Automated Cleanup
- **Unpaid Registration Cleanup** - Pembersihan registrasi tidak bayar
- **Payment Reminders** - Pengingat pembayaran berkala
- **WhatsApp Notifications** - Notifikasi penghapusan via WhatsApp
- **Admin Monitoring** - Monitoring dan kontrol manual

## 🛠️ Teknologi yang Digunakan

### Backend
- **PHP 8.1+** - Server-side scripting
- **Laravel 10** - PHP Framework
- **MySQL** - Database
- **Redis** - Cache & Session

### Frontend
- **HTML5/CSS3** - Markup & Styling
- **JavaScript** - Client-side scripting
- **Bootstrap 5** - CSS Framework
- **Font Awesome** - Icons

### Integration
- **WhatsApp API** - Messaging integration
- **Xendit API** - Payment gateway
- **Laravel Queue** - Background job processing
- **Laravel Scheduler** - Automated tasks

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/[username]/ASRRUN.git
cd ASRRUN
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
# Edit .env file dengan database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asrrun
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate
php artisan db:seed
```

### 5. Queue Setup
```bash
# Setup queue tables
php artisan queue:table
php artisan migrate

# Setup queue connection di .env
QUEUE_CONNECTION=database
```

### 6. WhatsApp & Payment Setup
```bash
# Edit .env file
WHATSAPP_API_URL=your_whatsapp_api_url
WHATSAPP_API_TOKEN=your_whatsapp_token

XENDIT_SECRET_KEY=your_xendit_secret_key
XENDIT_WEBHOOK_TOKEN=your_xendit_webhook_token
```

## 🎮 Menjalankan Aplikasi

### Development Mode
```bash
# Jalankan semua layanan sekaligus
start-complete-system.bat

# Atau manual:
php artisan serve                    # Laravel server
php artisan queue:work --verbose     # Queue worker
php artisan schedule:work           # Scheduler worker
```

### Production Mode
```bash
# Setup cron job untuk scheduler
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1

# Setup supervisor untuk queue worker
# /etc/supervisor/conf.d/laravel-worker.conf
```

## 📊 Monitoring & Testing

### Dashboard Access
- **Admin Dashboard**: `http://localhost:8000/admin`
- **WhatsApp Queue**: `http://localhost:8000/admin/whatsapp-queue`
- **Unpaid Registrations**: `http://localhost:8000/admin/unpaid-registrations`

### Testing Interface
- **WhatsApp Queue Test**: `http://localhost:8000/test-whatsapp-queue.html`
- **Unpaid Cleanup Test**: `http://localhost:8000/test-unpaid-cleanup.html`

### Command Line Testing
```bash
# Test WhatsApp queue
php artisan whatsapp:test-queue

# Test unpaid cleanup
php artisan registrations:process-unpaid --dry-run

# Monitor logs
tail -f storage/logs/laravel.log
```

## 🔧 Konfigurasi

### WhatsApp Settings
```php
// config/whatsapp.php
'api_url' => env('WHATSAPP_API_URL'),
'api_token' => env('WHATSAPP_API_TOKEN'),
'queue_delay' => 10, // seconds
'reminder_interval' => 30, // minutes
'cleanup_hours' => 6, // hours
```

### Payment Settings
```php
// config/xendit.php
'secret_key' => env('XENDIT_SECRET_KEY'),
'webhook_token' => env('XENDIT_WEBHOOK_TOKEN'),
'currency' => 'IDR',
'environment' => env('XENDIT_ENVIRONMENT', 'development'),
```

## 🔄 Background Jobs

### WhatsApp Queue System
```php
// Dispatch WhatsApp message
SendWhatsAppMessage::dispatch($phone, $message, $priority);

// Process queue with delay
WhatsAppQueueService::queueMessage($phone, $message, 'high');
```

### Unpaid Registration Cleanup
```php
// Send payment reminders
SendPaymentReminders::dispatch();

// Cleanup expired registrations
CleanupUnpaidRegistrations::dispatch();
```

## 🚨 Troubleshooting

### Common Issues

#### Queue Not Processing
```bash
# Restart queue worker
php artisan queue:restart

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

#### Scheduler Not Running
```bash
# Check scheduled tasks
php artisan schedule:list

# Run scheduler manually
php artisan schedule:run

# Start scheduler worker
php artisan schedule:work
```

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Team

- **Developer**: [Your Name]
- **Email**: [your.email@example.com]
- **GitHub**: [your-github-username]

---

**ASRRUN** - Aplikasi Registrasi dengan Automation System 🚀
