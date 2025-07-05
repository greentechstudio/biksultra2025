# 🚀 XENDIT WEBHOOK - LANGKAH SETUP OTOMATIS

## 📋 Yang Harus Anda Lakukan di Dashboard Xendit:

### 1. Login ke Xendit Dashboard
```
https://dashboard.xendit.co
```

### 2. Navigasi ke Webhook Settings
```
Dashboard → Settings → Webhooks → Add New Webhook
```

### 3. Konfigurasi Webhook

#### URL Webhook:
**Untuk Development (localhost):**
```
https://abc123.ngrok.io/api/xendit/webhook
```
*(Ganti dengan URL ngrok Anda)*

**Untuk Production:**
```
https://yourdomain.com/api/xendit/webhook
```

#### Event Types (Centang semua):
- ✅ `invoice.paid` - Pembayaran berhasil
- ✅ `invoice.expired` - Invoice kadaluwarsa  
- ✅ `invoice.failed` - Pembayaran gagal

#### HTTP Method:
- ✅ POST

### 4. Copy Webhook Token
Setelah webhook dibuat, copy **Webhook Token** dan masukkan ke `.env`:
```env
XENDIT_WEBHOOK_TOKEN=your_webhook_token_from_xendit
```

---

## 🛠️ Setup Development dengan ngrok:

### 1. Install ngrok
```bash
# Download dari https://ngrok.com/download
# Atau install via npm:
npm install -g ngrok
```

### 2. Jalankan Laravel
```bash
php artisan serve
```

### 3. Buka Tunnel ngrok
```bash
ngrok http 8000
```

### 4. Copy URL ngrok
Akan muncul output seperti:
```
Forwarding    https://abc123.ngrok.io -> http://localhost:8000
```

### 5. Update Webhook di Xendit
Gunakan URL: `https://abc123.ngrok.io/api/xendit/webhook`

---

## ✅ Verifikasi Setup:

### 1. Test Webhook Endpoint
```bash
php test-xendit-endpoint.php
```

### 2. Test dengan Registrasi Baru
1. Buat registrasi baru di aplikasi
2. Lakukan pembayaran
3. Cek log: `storage/logs/laravel.log`

### 3. Monitor Webhook di Xendit
- Dashboard → Webhooks → [Your Webhook] → Delivery History

---

## 🔧 Troubleshooting:

### Webhook Tidak Terkirim:
- ❌ **URL tidak bisa diakses** → Pastikan ngrok berjalan
- ❌ **SSL Error** → Gunakan HTTPS (ngrok otomatis HTTPS)
- ❌ **Firewall** → Pastikan port terbuka

### Webhook Gagal Diproses:
- ❌ **Invalid signature** → Cek webhook token di .env
- ❌ **User not found** → Cek external_id di database
- ❌ **Server error** → Cek Laravel logs

### Commands untuk Debug:
```bash
# Cek status webhook
php check-webhook-status.php

# Test endpoint
php test-xendit-endpoint.php

# Manual update payment (jika webhook gagal)
php send-payment-notification.php
```

---

## 📱 Status Codes:

| Code | Meaning | Action |
|------|---------|--------|
| 200 | Success | Webhook berhasil diproses |
| 401 | Unauthorized | Cek webhook token |
| 400 | Bad Request | Cek format data |
| 500 | Server Error | Cek Laravel logs |

---

## 🎯 Quick Start:

1. **Install ngrok** → https://ngrok.com/download
2. **Run Laravel** → `php artisan serve`
3. **Run ngrok** → `ngrok http 8000`
4. **Copy ngrok URL** → `https://abc123.ngrok.io`
5. **Setup di Xendit** → Dashboard → Webhooks → Add New
6. **URL**: `https://abc123.ngrok.io/api/xendit/webhook`
7. **Events**: invoice.paid, invoice.expired, invoice.failed
8. **Copy token** → Add to `.env`
9. **Test payment** → Buat registrasi baru

**Setelah ini, webhook akan otomatis update status pembayaran! 🎉**
