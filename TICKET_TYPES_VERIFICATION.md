# VERIFIKASI: HARGA HANYA DARI TICKET_TYPES

## ✅ PERUBAHAN YANG SUDAH DILAKUKAN:

### 1. CollectiveImportController.php:
- ✅ Line 314-315: `$data['payment_amount'] = $ticketType->price;` dan `$data['registration_fee'] = $ticketType->price;`
- ✅ Line 331: `'ticket_type_id' => $ticketTypeId,` - ticket_type_id sudah diset dengan benar
- ✅ Line 291-318: Logic untuk mencari collective ticket type berdasarkan race category
- ✅ Tidak ada hardcoded prices (150000, 175000, 200000, 225000)

### 2. XenditService.php:
- ✅ Line 437: `$officialPrice = (float) $ticketType->price;` dalam validateAndGetOfficialPrice()
- ✅ Line 522: `$collectivePrice = (float) $ticketType->price;` dalam getCollectivePrice()
- ✅ Line 735-742: Diganti dari getCollectivePrice() ke validateAndGetOfficialPrice() untuk consistency
- ✅ Semua security validations tetap ada untuk mencegah price manipulation

### 3. Database Structure:
- ✅ ticket_types table sebagai single source of truth untuk harga
- ✅ race_category_id menghubungkan ticket_types dengan race_categories
- ✅ Collective tickets tersedia untuk setiap race category (5K, 10K, HM 21K)

## ✅ FLOW HARGA SEKARANG:

1. **CSV Import**: 
   - Cari ticket_type berdasarkan race_category dan nama "kolektif"
   - Set ticket_type_id, payment_amount, registration_fee dari ticket_type.price

2. **Invoice Generation**:
   - Gunakan validateAndGetOfficialPrice() yang selalu ambil dari ticket_types.price
   - Validasi security untuk mencegah price manipulation

3. **Admin Display**:
   - Semua harga ditampilkan berdasarkan relationship dengan ticket_types

## 🎯 HASIL:
- ✅ Semua harga sekarang HANYA diambil dari ticket_types table
- ✅ Tidak ada hardcoded prices
- ✅ ticket_type_id sudah terbaca dan diset dengan benar untuk collective import
- ✅ Sistem lebih maintainable - ubah harga cukup di ticket_types table saja
- ✅ Security validations tetap kuat
