# 🔄 Otomatis Update Status Pembayaran Xendit

## 📋 Cara Kerja Sistem

### 1. **Flow Otomatis Update Status**
```
User Registrasi → Xendit Invoice Created → Payment Made → Xendit Webhook → Status Updated
```

### 2. **Proses Detail**
1. **User melakukan registrasi** → Status: `pending`, Payment Status: `pending`
2. **Xendit invoice dibuat** → User mendapat link pembayaran
3. **User melakukan pembayaran** → Xendit memproses pembayaran
4. **Xendit mengirim webhook** → Sistem menerima notifikasi pembayaran
5. **Sistem update status** → Status: `active`, Payment Status: `paid`
6. **WhatsApp konfirmasi** → User mendapat pesan konfirmasi

## 🔧 Komponen Sistem

### 1. **Webhook Handler**
File: `app/Http/Controllers/Api/XenditWebhookController.php`
- Menerima webhook dari Xendit
- Verifikasi signature webhook
- Proses update status user

### 2. **XenditService**
File: `app/Services/XenditService.php`
- Method: `processWebhook($payload)`
- Update status user berdasarkan status pembayaran
- Logging untuk tracking

### 3. **Webhook URL**
```
POST /api/xendit/webhook
```

### 4. **Status Mapping**
| Xendit Status | User Status | Payment Status | Action |
|---------------|-------------|----------------|---------|
| `PAID` | `active` | `paid` | Aktivasi membership |
| `EXPIRED` | `expired` | `expired` | Notifikasi expired |
| `FAILED` | `failed` | `failed` | Notifikasi gagal |

## 🛠️ Setup Webhook di Xendit Dashboard

### 1. **Login ke Xendit Dashboard**
- Buka: https://dashboard.xendit.co
- Login dengan akun Anda

### 2. **Konfigurasi Webhook**
- Masuk ke **Settings** → **Webhooks**
- Klik **Add New Webhook**
- Masukkan URL: `https://yourdomain.com/api/xendit/webhook`
- Pilih Events:
  - ✅ `invoice.paid`
  - ✅ `invoice.expired`
  - ✅ `invoice.failed`
- Masukkan **Webhook Token** (sama dengan `XENDIT_WEBHOOK_TOKEN` di .env)

### 3. **Environment Variables**
```env
XENDIT_WEBHOOK_TOKEN=your_webhook_verification_token
XENDIT_SECRET_KEY=your_xendit_secret_key
APP_URL=https://yourdomain.com
```

## 🧪 Testing Sistem

### 1. **Test Command**
```bash
# Test webhook dengan user yang ada
php artisan xendit:test-webhook

# Test dengan user ID tertentu
php artisan xendit:test-webhook --user-id=1

# Test dengan external ID tertentu
php artisan xendit:test-webhook --external-id=AMAZING-REG-1-1234567890
```

### 2. **Test Script**
```bash
# Jalankan script test interaktif
test-webhook.bat
```

### 3. **Manual Test via API**
```bash
# Test webhook endpoint
curl -X POST http://localhost/api/xendit/webhook \
  -H "Content-Type: application/json" \
  -H "x-callback-token: your_webhook_token" \
  -d '{
    "external_id": "AMAZING-REG-1-1234567890",
    "status": "PAID",
    "amount": 150000,
    "payment_method": "BCA"
  }'

# Check payment status
curl -X GET "http://localhost/api/payment/status?user_id=1"

# Update payment status manually
curl -X POST "http://localhost/api/payment/status" \
  -H "Content-Type: application/json" \
  -d '{"user_id": 1, "status": "paid"}'
```

## 📊 Monitoring & Troubleshooting

### 1. **Check Logs**
```bash
# Monitor webhook logs
tail -f storage/logs/laravel.log | grep -i xendit

# Monitor payment status updates
tail -f storage/logs/laravel.log | grep -i "payment status"
```

### 2. **Check Database**
```sql
-- Cek status pembayaran users
SELECT id, name, email, status, payment_status, payment_confirmed, 
       xendit_external_id, payment_requested_at, payment_confirmed_at
FROM users 
WHERE payment_status IS NOT NULL 
ORDER BY payment_requested_at DESC;

-- Cek pending payments
SELECT id, name, email, xendit_external_id, payment_requested_at
FROM users 
WHERE payment_status = 'pending' 
AND payment_requested_at > NOW() - INTERVAL 24 HOUR;
```

### 3. **API Endpoints untuk Monitoring**
```
GET /api/payment/pending - Lihat semua pending payments
GET /api/payment/status?user_id=1 - Check status user tertentu
POST /api/payment/status - Update status manual
```

## 🔍 Common Issues & Solutions

### 1. **Webhook tidak berjalan**
**Kemungkinan penyebab:**
- URL webhook tidak accessible dari internet
- Webhook token tidak sesuai
- Signature verification gagal

**Solusi:**
- Test webhook URL dari external tool
- Periksa `XENDIT_WEBHOOK_TOKEN` di .env
- Check logs untuk error detail

### 2. **Status tidak update**
**Kemungkinan penyebab:**
- User tidak ditemukan berdasarkan external_id
- Database error
- Webhook payload tidak sesuai format

**Solusi:**
- Periksa external_id di database
- Check database connection
- Validate webhook payload format

### 3. **Signature verification gagal**
**Kemungkinan penyebab:**
- Webhook token tidak sesuai
- Raw body tidak sesuai

**Solusi:**
- Pastikan webhook token sama di Xendit dan .env
- Periksa format raw body webhook

## 🚀 Production Deployment

### 1. **Pre-deployment Checklist**
- [ ] Webhook URL accessible dari internet
- [ ] SSL certificate installed
- [ ] Environment variables configured
- [ ] Database migrations applied
- [ ] Webhook configured di Xendit Dashboard

### 2. **Post-deployment Testing**
- [ ] Test webhook endpoint
- [ ] Test payment flow end-to-end
- [ ] Check logs for errors
- [ ] Verify database updates

### 3. **Monitoring Setup**
- [ ] Log monitoring alerts
- [ ] Database monitoring
- [ ] Webhook response time monitoring
- [ ] Error rate monitoring

## 📞 Support

Jika mengalami masalah:
1. Cek logs: `storage/logs/laravel.log`
2. Jalankan diagnostic: `php artisan deployment:diagnose`
3. Test webhook: `php artisan xendit:test-webhook`
4. Check database manually

---

**Last Updated**: July 2025  
**Version**: 1.0
