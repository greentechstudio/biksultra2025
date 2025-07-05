# 💳 Integrasi Xendit Payment - Amazing Sultra Run

## 📋 Overview

Sistem pembayaran otomatis terintegrasi dengan Xendit untuk registrasi member Amazing Sultra Run. Ketika user registrasi, sistem akan:

1. ✅ Membuat akun user
2. 📱 Mengirim pesan WhatsApp aktivasi
3. 💳 Membuat invoice pembayaran Xendit
4. 📲 Mengirim link pembayaran via WhatsApp
5. 🔔 Menerima notifikasi pembayaran via webhook
6. ✅ Update status member + kirim konfirmasi WhatsApp

## 🚀 Cara Setup

### 1. Instalasi Dependencies

Tidak ada dependency tambahan yang diperlukan, semua menggunakan Laravel HTTP Client.

### 2. Environment Variables

Tambahkan ke file `.env`:

```env
# Xendit Configuration
XENDIT_BASE_URL=https://api.xendit.co
XENDIT_PUBLIC_KEY=xnd_public_your_public_key
XENDIT_SECRET_KEY=xnd_your_secret_key
XENDIT_WEBHOOK_TOKEN=your_webhook_verification_token
XENDIT_ENVIRONMENT=test

# Payment Settings
REGISTRATION_FEE=150000

# WhatsApp Settings (sudah ada)
WHATSAPP_API_KEY=your_whatsapp_api_key
WHATSAPP_SENDER=your_whatsapp_sender_number
```

### 3. Jalankan Setup Command

```bash
# Setup dan test
php artisan xendit:setup --test

# Atau setup saja
php artisan xendit:setup
```

### 4. Konfigurasi Webhook di Xendit Dashboard

1. Login ke [Xendit Dashboard](https://dashboard.xendit.co)
2. Masuk ke **Settings > Webhooks**
3. Tambahkan webhook URL: `https://yourdomain.com/api/xendit/webhook`
4. Pilih events: `invoice.paid`, `invoice.expired`, `invoice.failed`
5. Masukkan verification token yang sama dengan `XENDIT_WEBHOOK_TOKEN`

## 📁 File Structure

```
app/
├── Services/
│   └── XenditService.php              # Service untuk Xendit API
├── Http/Controllers/
│   ├── AuthController.php             # Update dengan integrasi Xendit
│   └── Api/
│       └── XenditWebhookController.php # Handle webhook dari Xendit
├── Console/Commands/
│   └── SetupXenditIntegration.php     # Command untuk setup
└── Models/
    └── User.php                       # Update dengan field Xendit

config/
└── xendit.php                        # Konfigurasi Xendit

database/migrations/
└── 2025_07_03_000001_add_xendit_fields_to_users_table.php

resources/views/
├── payment/
│   ├── success.blade.php             # Halaman sukses pembayaran
│   └── failed.blade.php              # Halaman gagal pembayaran
└── partials/
    └── scripts-welcome.blade.php     # Update script untuk tombol

routes/
└── web.php                          # Update dengan route webhook
```

## 🔄 Flow Pembayaran

### 1. Registrasi User

```php
// AuthController@register
1. Validasi data registrasi
2. Buat user dengan status 'pending'
3. Kirim pesan WhatsApp aktivasi
4. Buat invoice Xendit
5. Kirim link pembayaran via WhatsApp
6. Redirect ke login dengan pesan sukses
```

### 2. Webhook Pembayaran

```php
// XenditWebhookController@handleWebhook
1. Verifikasi signature webhook
2. Update status pembayaran user
3. Jika PAID: kirim konfirmasi WhatsApp
4. Jika EXPIRED/FAILED: kirim notifikasi
```

## 📱 Template WhatsApp

### Link Pembayaran
```
🎯 *LINK PEMBAYARAN REGISTRASI* 🎯

Halo *{nama}*,

Terima kasih telah mendaftar di Amazing Sultra Run! 🏃‍♂️

📋 *Detail Pembayaran:*
• Biaya Registrasi: Rp 150.000
• Berlaku selama: 24 jam

💳 *Link Pembayaran:*
{link_pembayaran}

⚠️ *Penting:*
• Link pembayaran akan kedaluwarsa dalam 24 jam
• Anda akan mendapat konfirmasi otomatis setelah pembayaran berhasil
• Membership akan aktif setelah pembayaran terkonfirmasi

📞 Butuh bantuan? Hubungi: +62811-4000-805

Terima kasih! 🙏
```

### Konfirmasi Pembayaran Berhasil
```
🎉 *PEMBAYARAN BERHASIL!* 🎉

Halo *{nama}*,

✅ Pembayaran registrasi Amazing Sultra Run Anda telah berhasil!

📋 *Detail Pembayaran:*
• Jumlah: Rp {jumlah}
• Metode: {metode}
• Waktu: {waktu}
• ID Transaksi: {id_transaksi}

🏃‍♂️ *Selamat bergabung dengan Amazing Sultra Run!*

Anda sekarang adalah member resmi kami. Silakan login ke dashboard untuk melengkapi profil dan melihat jadwal latihan.

🔗 Dashboard: {url_dashboard}

Terima kasih dan selamat berlari! 🏃‍♀️💪
```

## 🔧 API Endpoints

### Webhook Xendit
```
POST /api/xendit/webhook
Content-Type: application/json
x-callback-token: {your_webhook_token}

{
  "id": "invoice_id",
  "external_id": "AMAZING-REG-{user_id}-{timestamp}",
  "status": "PAID|EXPIRED|FAILED",
  "amount": 150000,
  "payment_method": "BCA",
  "paid_at": "2025-07-03T10:30:00.000Z"
}
```

### Halaman Redirect
```
GET /payment/success     # Halaman sukses pembayaran
GET /payment/failed      # Halaman gagal pembayaran
```

## 🗄️ Database Schema

### Tabel users (field tambahan)
```sql
xendit_invoice_id     VARCHAR(255) NULL    # ID invoice dari Xendit
xendit_invoice_url    VARCHAR(255) NULL    # URL pembayaran
xendit_external_id    VARCHAR(255) NULL    # External ID untuk tracking
payment_status        ENUM('pending','paid','expired','failed') DEFAULT 'pending'
payment_requested_at  TIMESTAMP NULL       # Waktu invoice dibuat
xendit_callback_data  JSON NULL            # Data callback dari Xendit
```

## 🧪 Testing

### Manual Testing
```bash
# 1. Test setup
php artisan xendit:setup --test

# 2. Test registrasi
# Buka /register dan lakukan registrasi lengkap

# 3. Test webhook
curl -X POST http://localhost/api/xendit/webhook \
  -H "Content-Type: application/json" \
  -H "x-callback-token: your_webhook_token" \
  -d '{
    "external_id": "AMAZIN-REG-1-1234567890",
    "status": "PAID",
    "amount": 150000,
    "payment_method": "BCA"
  }'
```

### Automated Testing
```bash
php test-xendit-integration.php
```

## 📊 Monitoring

### Log Files
```bash
# Monitor semua log
tail -f storage/logs/laravel.log

# Filter log Xendit
tail -f storage/logs/laravel.log | grep -i xendit

# Filter log WhatsApp
tail -f storage/logs/laravel.log | grep -i whatsapp
```

### Database Queries
```sql
-- Cek status pembayaran users
SELECT id, name, email, payment_status, payment_confirmed, created_at 
FROM users 
WHERE payment_status IS NOT NULL 
ORDER BY created_at DESC;

-- Cek invoice yang pending
SELECT id, name, email, xendit_invoice_id, payment_requested_at
FROM users 
WHERE payment_status = 'pending' 
AND payment_requested_at > NOW() - INTERVAL 24 HOUR;
```

## 🚨 Troubleshooting

### 1. Invoice Creation Failed
```bash
# Cek environment variables
php artisan env

# Cek koneksi ke Xendit
curl -u "xnd_your_secret_key:" https://api.xendit.co/v2/invoices
```

### 2. Webhook Not Working
```bash
# Cek route
php artisan route:list | grep xendit

# Test webhook endpoint
curl -X POST http://localhost/api/xendit/webhook \
  -H "Content-Type: application/json" \
  -H "x-callback-token: test" \
  -d '{"test": "data"}'
```

### 3. WhatsApp Not Sending
```bash
# Test WhatsApp API
curl -X POST https://wamd.system112.org/send-message \
  -d "api_key=your_key" \
  -d "sender=your_sender" \
  -d "number=628123456789" \
  -d "message=Test message"
```

## 🔒 Security

1. **Webhook Verification**: Selalu verifikasi signature webhook
2. **Environment Variables**: Jangan commit .env file
3. **HTTPS**: Gunakan HTTPS untuk webhook URL
4. **Rate Limiting**: Implement rate limiting untuk webhook endpoint

## 📈 Future Enhancements

1. **Multiple Payment Methods**: Tambah QRIS, E-wallet, dll
2. **Recurring Payments**: Untuk membership bulanan/tahunan
3. **Refund System**: Sistem refund otomatis
4. **Payment Analytics**: Dashboard analisis pembayaran
5. **Email Notifications**: Backup selain WhatsApp

## 💡 Tips & Best Practices

1. **Test Mode**: Gunakan test mode untuk development
2. **Webhook Retry**: Implement retry mechanism untuk webhook
3. **Idempotency**: Handle duplicate webhook calls
4. **Logging**: Log semua transaksi untuk audit
5. **Monitoring**: Setup alerting untuk failed payments

---

**📞 Support**: Jika ada masalah, hubungi tim development atau buka issue di repository.
