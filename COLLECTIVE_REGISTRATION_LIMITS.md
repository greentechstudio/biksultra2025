# PANDUAN MENGUBAH BATASAN REGISTRASI KOLEKTIF

## 🎯 BATASAN SAAT INI
- **Minimum:** 5 peserta
- **Maximum:** 50 peserta

## 🔧 CARA MENGUBAH BATASAN

### 1. MENGUBAH BATASAN MAXIMUM

**File yang perlu diubah:**
- `public/js/collective-registration.js` (line 3)

```javascript
// UBAH NILAI INI:
const maxParticipants = 50;  // ← Ganti angka ini

// CONTOH: Jika ingin maksimal 100 peserta
const maxParticipants = 100;
```

### 2. MENGUBAH BATASAN MINIMUM

**File yang perlu diubah:**
- `public/js/collective-registration.js` (line 461)
- `app/Http/Controllers/AuthController.php` (line 2149)

#### Frontend JavaScript:
```javascript
// CARI FUNGSI removeParticipantForm() dan ubah:
if (participantCount <= 10) {  // ← Ganti angka 10

// CONTOH: Jika ingin minimum 5 peserta
if (participantCount <= 5) {
```

#### Backend PHP:
```php
// CARI VALIDASI MINIMUM dan ubah:
if ($validParticipants < 10) {  // ← Ganti angka 10

// CONTOH: Jika ingin minimum 5 peserta
if ($validParticipants < 5) {
```

### 3. MENGUBAH JUMLAH FORM AWAL

**File:** `public/js/collective-registration.js` (line 58)

```javascript
// UBAH JUMLAH FORM YANG MUNCUL PERTAMA KALI:
for (let i = 0; i < 10; i++) {  // ← Ganti angka 10

// CONTOH: Jika ingin 15 form awal
for (let i = 0; i < 15; i++) {
```

## 🚀 CONTOH KONFIGURASI CUSTOM

### SKENARIO 1: Event Mini (3-15 peserta)
```javascript
const maxParticipants = 15;     // Maximum 15 peserta
// Minimum: 3 peserta
// Form awal: 3 form
```

### SKENARIO 2: Event Kecil (5-20 peserta) **[CURRENT SETTING]**
```javascript
const maxParticipants = 20;     // Maximum 20 peserta
// Minimum: 5 peserta
// Form awal: 5 form
```

### SKENARIO 3: Event Sedang (10-50 peserta)
```javascript
const maxParticipants = 50;     // Maximum 50 peserta
// Minimum: 10 peserta
// Form awal: 10 form
```

### SKENARIO 4: Event Besar (15-100 peserta)
```javascript
const maxParticipants = 100;    // Maximum 100 peserta
// Minimum: 15 peserta
// Form awal: 15 form
```

### SKENARIO 5: Event Mega (20-200 peserta)
```javascript
const maxParticipants = 200;    // Maximum 200 peserta
// Minimum: 20 peserta
// Form awal: 20 form
```

## ⚠️ PERTIMBANGAN PENTING

### 1. PERFORMA
- Semakin banyak peserta = lebih lambat loading
- Recommend maksimal 100 peserta per grup
- Pertimbangkan split ke multiple grup

### 2. USER EXPERIENCE
- Terlalu banyak form = overwhelming
- Terlalu sedikit = tidak efisien
- Sweet spot: 10-50 peserta

### 3. SERVER RESOURCE
- Validasi WhatsApp real-time
- Database transaction size
- Memory usage untuk processing

## 📋 CHECKLIST SETELAH MENGUBAH

✅ Update frontend JavaScript limits
✅ Update backend PHP validation
✅ Test dengan jumlah minimum
✅ Test dengan jumlah maximum
✅ Test performance dengan load testing
✅ Update dokumentasi user

## 🔗 FILES YANG TERKAIT

1. **Frontend Limits:**
   - `public/js/collective-registration.js`

2. **Backend Validation:**
   - `app/Http/Controllers/AuthController.php`

3. **Database Design:**
   - `database/migrations/...users_table.php`

4. **UI/UX:**
   - `resources/views/auth/register-kolektif.blade.php`
