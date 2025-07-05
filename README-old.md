# Dashboard Event Lari

Dashboard web Laravel untuk registrasi event lari dengan **sistem role-based access control**, **fitur validasi WhatsApp real-time**, dan **edit profil terbatas**.

## Fitur Utama

### 🔐 **Role-Based Access Control (BARU!)**
Dashboard dibagi menjadi 2 level akses:

**ADMIN:**
- Dashboard lengkap dengan statistik peserta & revenue
- Kelola users (view, verify WhatsApp, confirm payment)
- Pengaturan data master (jersey, kategori, golongan darah, sumber info)
- Akses ke semua fitur sistem

**USER:**
- Dashboard personal dengan status registrasi sendiri
- Status verifikasi WhatsApp dan pembayaran
- Edit profil **HANYA SEKALI** setelah registrasi
- Akses terbatas sesuai kebutuhan peserta

### ✏️ **Edit Profil Terbatas (BARU!)**
- User dapat mengedit profil **hanya 1 kali** setelah registrasi
- Semua field yang diisi saat registrasi dapat diedit
- Wajib mengisi alasan edit profil
- Sistem tracking waktu edit dan catatan
- Setelah edit, profil terkunci permanen

### 🔑 **Reset Password via WhatsApp (BARU!)**
- Reset password menggunakan nomor WhatsApp terdaftar
- Link reset dikirim langsung ke WhatsApp pengguna
- Menggunakan API: `https://wamd.system112.org/send-message`
- Token expired dalam 1 jam untuk keamanan
- Konfirmasi otomatis setelah password berhasil direset

### ✅ **Validasi WhatsApp Real-time**
- Validasi otomatis nomor WhatsApp saat user mengetik
- Menggunakan API eksternal (wamd.system112.org) 
- Feedback visual langsung (valid/tidak valid)
- Form tidak dapat disubmit jika nomor WhatsApp tidak valid

### 📝 Form Registrasi Lengkap
Form registrasi event lari dengan field-field berikut:

**Informasi Pribadi:**
- Nama lengkap (wajib)
- Jenis kelamin: Laki-laki / Perempuan (wajib)
- Tempat lahir (wajib)
- Tanggal lahir dengan datepicker (wajib, minimal umur 10 tahun)
- Alamat lengkap (wajib)

**Informasi Lomba:**
- Kategori lomba: 5K, 10K, 21K, 42K dengan harga (wajib, bisa diatur admin)
- Ukuran jersey: XS-XXXL (wajib, bisa diatur admin)

**Informasi Kontak:**
- Email (wajib)
- No Kontak WhatsApp (wajib)
  - Format otomatis (+62)
  - Validasi langsung saat mengetik
  - Feedback visual: ✅ valid / ⚠️ tidak valid / ❌ error
  - Menggunakan API: `https://wamd.system112.org/check-number`
- No HP Alternatif (opsional)
- Kontak darurat 1 (wajib)
- Kontak darurat 2 (opsional)

**Informasi Tambahan:**
- Group Lari/Komunitas/Instansi (opsional)
- Golongan darah: A+, A-, B+, B-, AB+, AB-, O+, O- (wajib, bisa diatur admin)
- Pekerjaan (wajib)
- Riwayat penyakit (opsional)
- Sumber informasi event (wajib, bisa diatur admin)

**Akun:**
- Password dengan konfirmasi (wajib, minimal 8 karakter)

### 🔧 Pengaturan Admin
Admin bisa mengatur data master untuk:
- ✅ Ukuran jersey
- ✅ Kategori lomba (dengan harga)
- ✅ Golongan darah
- ✅ Sumber informasi event

### 📱 WhatsApp Integration
- Setelah registrasi berhasil, user diarahkan ke WhatsApp untuk konfirmasi
- Message otomatis berisi ringkasan registrasi
- Format konfirmasi: `KONFIRMASI-{USER_ID}`

### 💳 Sistem Pembayaran
- Dashboard untuk konfirmasi pembayaran
- Track status pembayaran user
- Notifikasi WhatsApp untuk konfirmasi

## Setup & Installation

### 1. Clone & Setup
```bash
cd c:\xampp\htdocs\asr\dashboard-app
composer install
npm install
```

### 2. Database Setup
```bash
# Jalankan batch file untuk setup database
setup-database.bat

# Atau manual:
php artisan migrate
php artisan db:seed --class=SettingsSeeder
php artisan db:seed --class=AdminUserSeeder
```

### 3. Admin Account
**Email:** admin@asr.com  
**Password:** admin123

**Atau buat admin baru:**
```bash
php artisan db:seed --class=AdminUserSeeder
```

### 4. Testing
```bash
# Test role-based system
php test-role-system.bat

# Test individual features
php test-role-access.php
php test-edit-profile.php
```

### 5. Environment
Copy `.env.example` ke `.env` dan sesuaikan:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# WhatsApp Settings
WHATSAPP_API_URL=https://wa.me
WHATSAPP_BUSINESS_NUMBER=6281234567890
```

### 6. Start Server
```bash
# Gunakan batch file
start-server.bat

# Atau manual:
php artisan serve
```

### 7. Check System
```bash
# Cek apakah semua dependency terpenuhi
check-system.bat
```

## URLs

### Main Access
- **Home:** http://localhost/asr/ 
- **Registrasi:** http://localhost/asr/register
- **Login:** http://localhost/asr/login
- **Reset Password:** http://localhost/asr/password/reset

### Authentication
- **Reset Password Form:** http://localhost/asr/password/reset
- **Reset Link (Example):** http://localhost/asr/password/reset/{token}?whatsapp=6281234567890

### Dashboard (Role-based)
- **Dashboard:** http://localhost/asr/dashboard 
  - Admin: Statistik lengkap + management
  - User: Status personal + edit profile
- **Profile:** http://localhost/asr/profile
- **Profile Edit:** http://localhost/asr/profile/edit (sekali saja)

### Admin Only
- **User Management:** http://localhost/asr/dashboard/users
- **WhatsApp Verification:** http://localhost/asr/dashboard/whatsapp-verification  
- **Payment Confirmation:** http://localhost/asr/dashboard/payment-confirmation
- **Admin Settings:** http://localhost/asr/admin/settings

### Testing & Debug
- **WhatsApp Debug:** http://localhost/asr/debug-whatsapp.php?number=628114000805
- **API Test:** http://localhost/asr/test-whatsapp-frontend.html
- **Laravel Debug:** http://localhost/asr/debug/whatsapp/628114000805

## User Accounts

### Admin Account
- **Email:** admin@asr.com
- **Password:** admin123
- **Features:** Full access, user management, settings

### Test User Accounts  
- **Email:** user@example.com
- **Password:** password
- **Features:** Personal dashboard, profile edit (once)

*Note: Registrasi baru otomatis mendapat role 'user'*

## Database Structure

### Tables
- `users` - Data peserta dengan semua field registrasi + role system
- `jersey_sizes` - Master ukuran jersey
- `race_categories` - Master kategori lomba
- `blood_types` - Master golongan darah
- `event_sources` - Master sumber informasi event
- `password_resets` - Token reset password via WhatsApp

### Migrations
1. `0001_01_01_000000_create_users_table.php` - Tabel users dasar
2. `2025_06_25_073446_add_fields_to_users_table.php` - Field WhatsApp & payment
3. `2025_06_25_093828_add_detailed_fields_to_users_table.php` - Field registrasi detail
4. `2025_06_25_093932_create_settings_tables.php` - Tabel setting admin

## Development

### Form Validation
- Real-time validation dengan JavaScript
- Server-side validation dengan Laravel
- Format nomor HP otomatis
- Validasi umur minimal 10 tahun

### Security
- CSRF protection
- Password hashing
- Email unique validation
- Input sanitization

### Responsive Design
- Bootstrap 5
- Mobile-friendly
- Modern UI dengan gradient
- Section-based form layout

## Support

Untuk pertanyaan atau issues, silakan hubungi developer.

---
*Dashboard Event Lari v1.0 - Built with Laravel 11*