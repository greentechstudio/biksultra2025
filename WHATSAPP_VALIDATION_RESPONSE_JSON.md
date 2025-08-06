# DOKUMENTASI: RESPONSE JSON VALIDASI WHATSAPP

## 📊 OVERVIEW

Sistem validasi WhatsApp menggunakan endpoint `/validate-whatsapp` yang mengembalikan response JSON dengan berbagai status berdasarkan hasil validasi nomor WhatsApp.

## 🔧 ENDPOINT INFORMATION

### **URL:**
```
POST /validate-whatsapp
POST /api/validate-whatsapp
```

### **Request Payload:**
```json
{
    "whatsapp_number": "8114000805"
}
```

### **Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: {{ csrf_token() }}
```

## 📝 RESPONSE JSON FORMATS

### **1. SUKSES - Nomor Valid dan Terdaftar**
```json
{
    "success": true,
    "valid": true,
    "message": "Nomor WhatsApp valid dan aktif"
}
```

**Kondisi:**
- API WhatsApp berhasil merespons
- Nomor terdaftar di WhatsApp
- Response dari API: `{"status": true, "data": {"jid": "...", "exists": true}}`

---

### **2. SUKSES - Nomor Tidak Terdaftar**
```json
{
    "success": true,
    "valid": false,
    "message": "Nomor tidak terdaftar di WhatsApp"
}
```

**Kondisi:**
- API WhatsApp berhasil merespons
- Nomor tidak terdaftar di WhatsApp
- Response dari API: `{"status": false, "msg": "..."}`

---

### **3. ERROR - Input Kosong**
```json
{
    "success": false,
    "valid": false,
    "message": "Nomor WhatsApp diperlukan"
}
```

**Kondisi:**
- Request tidak menyertakan parameter `whatsapp_number`
- Parameter kosong atau null

---

### **4. BYPASS - Validasi Dinonaktifkan**
```json
{
    "success": true,
    "valid": true,
    "message": "Validasi WhatsApp dilewati (disabled)"
}
```

**Kondisi:**
- Config `app.validate_whatsapp` = false
- Sistem bypass validasi WhatsApp

---

### **5. FALLBACK - Service Tidak Tersedia**
```json
{
    "success": true,
    "valid": true,
    "message": "Nomor WhatsApp diterima (validasi service tidak tersedia)"
}
```

**Kondisi:**
- API WhatsApp timeout atau connection error
- HTTP status 5xx (server error)
- Connection timeout (> 3 detik)
- Sistem fallback untuk mengizinkan registrasi

---

### **6. ERROR - Exception/Kesalahan Sistem**
```json
{
    "success": false,
    "valid": false,
    "message": "Validasi WhatsApp gagal. Silakan coba lagi atau hubungi admin."
}
```

**Kondisi:**
- Exception dalam proses validasi
- Error yang tidak dapat diatasi
- Kesalahan parsing JSON

---

## 🔍 FLOW VALIDASI

### **Step 1: Input Processing**
```
Input: "08114000805"
Format: "628114000805"
```

### **Step 2: API Call**
```
URL: https://wamd.system112.org/check-number
Method: GET
Params: {
    api_key: "tZiKYy1sHXasOj0hDGZnRfAnAYo2Ec",
    sender: "628114040707",
    number: "628114000805"
}
Timeout: 3 seconds
```

### **Step 3: Response Processing**

**API Response Valid:**
```json
{
    "status": true,
    "data": {
        "jid": "628114000805@s.whatsapp.net",
        "exists": true
    }
}
```

**API Response Invalid:**
```json
{
    "status": false,
    "msg": "Failed to check number!"
}
```

### **Step 4: Final Response**
- Berdasarkan API response, sistem mengembalikan JSON sesuai format di atas

## 🧪 TESTING SCENARIOS

### **Test 1: Nomor Valid**
```bash
curl -X POST http://localhost/validate-whatsapp \
  -H "Content-Type: application/json" \
  -d '{"whatsapp_number":"8114000805"}'
```

**Expected Response:**
```json
{
    "success": true,
    "valid": true,
    "message": "Nomor WhatsApp valid dan aktif"
}
```

### **Test 2: Nomor Invalid**
```bash
curl -X POST http://localhost/validate-whatsapp \
  -H "Content-Type: application/json" \
  -d '{"whatsapp_number":"1234567890"}'
```

**Expected Response:**
```json
{
    "success": true,
    "valid": false,
    "message": "Nomor tidak terdaftar di WhatsApp"
}
```

### **Test 3: Input Kosong**
```bash
curl -X POST http://localhost/validate-whatsapp \
  -H "Content-Type: application/json" \
  -d '{}'
```

**Expected Response:**
```json
{
    "success": false,
    "valid": false,
    "message": "Nomor WhatsApp diperlukan"
}
```

## 💻 FRONTEND INTEGRATION

### **JavaScript Example:**
```javascript
async function validateWhatsApp(phoneNumber) {
    try {
        const response = await fetch('/validate-whatsapp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                whatsapp_number: phoneNumber
            })
        });
        
        const data = await response.json();
        
        if (data.success && data.valid) {
            console.log('✅ Valid:', data.message);
            return true;
        } else {
            console.log('❌ Invalid:', data.message);
            return false;
        }
        
    } catch (error) {
        console.error('Network error:', error);
        return false;
    }
}
```

### **Usage in Forms:**
```javascript
// Register form
if (phoneNumber.length >= 9) {
    const isValid = await validateWhatsApp(phoneNumber);
    if (isValid) {
        showSuccessMessage('Nomor WhatsApp valid');
    } else {
        showErrorMessage('Nomor WhatsApp tidak valid');
    }
}
```

## 🔒 SECURITY & ERROR HANDLING

### **Response Field Meanings:**

- **`success`**: Apakah API call berhasil dilakukan
  - `true`: API berhasil diakses dan memberikan respons
  - `false`: Terjadi error dalam proses (network, timeout, exception)

- **`valid`**: Apakah nomor WhatsApp valid
  - `true`: Nomor terdaftar dan aktif di WhatsApp
  - `false`: Nomor tidak terdaftar di WhatsApp

- **`message`**: Pesan deskriptif untuk user

### **Error Handling Strategy:**

1. **Service Down** → Fallback allow registration
2. **Invalid Number** → Block registration
3. **Network Error** → Block registration 
4. **Timeout** → Fallback allow registration

## 📊 LOGGING

### **Log Entries:**
```
INFO: Validating WhatsApp number
INFO: WhatsApp API response received
WARNING: WhatsApp validation service failed
ERROR: WhatsApp validation error
```

### **Log Data:**
- Original number
- Formatted number
- API response
- Error details

## ⚙️ CONFIGURATION

### **Environment Variables:**
```env
WHATSAPP_API_KEY=tZiKYy1sHXasOj0hDGZnRfAnAYo2Ec
WHATSAPP_SENDER=628114040707
```

### **Config Settings:**
```php
// config/app.php
'validate_whatsapp' => env('VALIDATE_WHATSAPP', true),
```

---

**Endpoint**: `/validate-whatsapp`  
**Method**: `POST`  
**Controller**: `AuthController@validateWhatsAppAjax`  
**Timeout**: 3 seconds  
**API Provider**: wamd.system112.org
