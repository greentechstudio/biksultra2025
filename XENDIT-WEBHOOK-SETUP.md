# Setup Webhook Xendit - Panduan Lengkap

## 1. Login ke Xendit Dashboard

1. Buka browser dan pergi ke [https://dashboard.xendit.co](https://dashboard.xendit.co)
2. Login dengan akun Xendit Anda
3. Pastikan Anda sudah dalam mode **Development** atau **Production** sesuai kebutuhan

## 2. Navigasi ke Pengaturan Webhook

1. Di dashboard Xendit, klik menu **"Settings"** di sidebar kiri
2. Pilih **"Webhook"** atau **"Webhooks"**
3. Anda akan melihat halaman pengaturan webhook

## 3. Tambah Webhook Baru

1. Klik tombol **"Add New Webhook"** atau **"Create Webhook"**
2. Isi form dengan informasi berikut:

### URL Webhook
```
https://yourdomain.com/api/xendit/webhook
```

**Contoh untuk development:**
```
https://yourapp.ngrok.io/api/xendit/webhook
```

**Contoh untuk production:**
```
https://amazingsultrarun.com/api/xendit/webhook
```

### Event Types yang Harus Dicentang:
✅ **invoice.paid** - Ketika invoice dibayar
✅ **invoice.expired** - Ketika invoice kadaluwarsa
✅ **invoice.failed** - Ketika pembayaran gagal
✅ **invoice.created** - Ketika invoice dibuat (opsional)

### HTTP Method
- Pilih **POST**

### Headers (Optional)
- Bisa dikosongkan atau tambahkan header khusus jika diperlukan

## 4. Konfigurasi Webhook Token

1. Setelah webhook dibuat, Xendit akan memberikan **"Webhook Token"**
2. Copy token ini dan simpan di file `.env` aplikasi Anda:

```env
XENDIT_WEBHOOK_TOKEN=your_webhook_token_here
```

## 5. Testing Webhook (Development)

### Jika menggunakan localhost, Anda perlu menggunakan ngrok:

1. **Install ngrok:**
   ```bash
   # Download dari https://ngrok.com/
   # Atau install via npm
   npm install -g ngrok
   ```

2. **Jalankan aplikasi Laravel:**
   ```bash
   php artisan serve
   ```

3. **Buka tunnel ngrok:**
   ```bash
   ngrok http 8000
   ```

4. **Copy URL ngrok dan update di Xendit:**
   ```
   https://abc123.ngrok.io/api/xendit/webhook
   ```

## 6. Verifikasi Webhook

### Test dengan Xendit Dashboard:
1. Di halaman webhook, klik **"Test"** atau **"Send Test"**
2. Pilih event type yang ingin ditest
3. Xendit akan mengirim test webhook ke URL Anda

### Test dengan Invoice Nyata:
1. Buat invoice baru dari aplikasi
2. Lakukan pembayaran test
3. Cek log aplikasi untuk memastikan webhook diterima

## 7. Monitoring Webhook

### Di Xendit Dashboard:
1. Buka halaman **"Webhooks"**
2. Klik pada webhook yang sudah dibuat
3. Anda bisa melihat:
   - **Delivery History** - Riwayat pengiriman webhook
   - **Success Rate** - Tingkat keberhasilan
   - **Failed Attempts** - Percobaan yang gagal

### Di Aplikasi Laravel:
1. Cek log file: `storage/logs/laravel.log`
2. Cari entry dengan keyword "webhook"
3. Pastikan tidak ada error

## 8. Troubleshooting

### Webhook Tidak Terkirim:
1. **Cek URL webhook** - Pastikan URL bisa diakses dari luar
2. **Cek SSL** - Untuk production, pastikan menggunakan HTTPS
3. **Cek firewall** - Pastikan tidak ada firewall yang memblokir

### Webhook Gagal Diproses:
1. **Cek log aplikasi** - Lihat error di `storage/logs/laravel.log`
2. **Cek signature verification** - Pastikan webhook token benar
3. **Cek database** - Pastikan user dengan external_id ditemukan

### Status Code yang Dikembalikan:
- **200** - Webhook berhasil diproses
- **401** - Signature tidak valid
- **400** - Data tidak valid
- **500** - Server error

## 9. Konfigurasi Production

### Untuk production, pastikan:
1. **Gunakan HTTPS** - Xendit membutuhkan SSL
2. **Domain yang valid** - Bukan localhost atau IP
3. **Uptime yang baik** - Server harus selalu online
4. **Monitoring** - Setup monitoring untuk webhook endpoint

### Contoh URL Production:
```
https://api.amazingsultrarun.com/api/xendit/webhook
```

## 10. Backup Plan

### Jika webhook gagal:
1. Anda bisa **manual check** status payment di Xendit
2. Gunakan tool **update-payment-status.php** yang sudah dibuat
3. Setup **cron job** untuk check status payment berkala

### Contoh Cron Job:
```bash
# Cek status payment setiap 5 menit
*/5 * * * * php /path/to/your/app/artisan payment:check
```

## 11. Security Best Practices

1. **Verifikasi signature** - Selalu verifikasi webhook signature
2. **Validasi data** - Validasi semua data yang masuk
3. **Rate limiting** - Batasi jumlah request ke webhook endpoint
4. **Logging** - Log semua aktivitas webhook
5. **Idempotency** - Pastikan webhook bisa diproses berulang kali

---

**Setelah setup selesai, webhook akan otomatis mengirim notifikasi ke aplikasi Anda setiap kali ada perubahan status pembayaran!**
