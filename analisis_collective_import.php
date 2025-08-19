<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ANALISIS PROSES COLLECTIVE IMPORT ===\n\n";

echo "📋 **JAWABAN PERTANYAAN:**\n\n";

echo "1️⃣ **SIAPA YANG MENERIMA NOTIFIKASI WHATSAPP?**\n";
echo "   ✅ User PERTAMA dalam urutan import (baris pertama di Excel setelah header)\n";
echo "   📱 Xendit mengirim notifikasi WhatsApp & Email ke primaryUser (importedUsers[0])\n";
echo "   📄 Data primaryUser digunakan untuk:\n";
echo "      - payer_email\n";
echo "      - customer.given_names\n";
echo "      - customer.email\n";
echo "      - customer.mobile_number (WhatsApp)\n\n";

echo "2️⃣ **APAKAH INVOICE DIBUAT 1 SAJA?**\n";
echo "   ✅ YA, hanya 1 invoice collective untuk seluruh grup\n";
echo "   🔗 Semua peserta mendapat xendit_invoice_id yang SAMA\n";
echo "   🔗 Semua peserta mendapat xendit_invoice_url yang SAMA\n";
echo "   💰 Total amount = sum dari semua participant fees\n\n";

echo "3️⃣ **BAGAIMANA PROSES UPDATE DATA?**\n";
echo "   📝 Setelah invoice dibuat, SEMUA participants diupdate dengan:\n";
echo "      - xendit_invoice_id: [SAMA untuk semua]\n";
echo "      - xendit_invoice_url: [SAMA untuk semua]\n";
echo "      - xendit_external_id: [SAMA untuk semua]\n";
echo "      - payment_status: 'pending'\n\n";

try {
    // Simulasi proses
    echo "4️⃣ **SIMULASI PROSES IMPORT:**\n\n";
    
    // Contoh data import (3 peserta)
    $exampleData = [
        ['John Doe', 'john@example.com', '6281234567890'],
        ['Jane Smith', 'jane@example.com', '6281234567891'], 
        ['Bob Wilson', 'bob@example.com', '6281234567892']
    ];
    
    echo "📊 Contoh Excel dengan 3 peserta:\n";
    foreach ($exampleData as $index => $data) {
        $role = $index === 0 ? " 👑 [PRIMARY USER - Penerima notifikasi]" : "";
        echo "   Row " . ($index + 2) . ": {$data[0]} - {$data[1]} - {$data[2]}{$role}\n";
    }
    
    echo "\n💳 Setelah import & generate invoice:\n";
    echo "   📧 Notifikasi WhatsApp dikirim ke: John Doe (6281234567890)\n";
    echo "   🧾 Invoice ID: SAMA untuk ketiga peserta\n";
    echo "   🔗 Invoice URL: SAMA untuk ketiga peserta\n";
    echo "   🆔 External ID: AMAZING-ADMIN-COLLECTIVE-{user_id}-{timestamp}\n\n";

    echo "5️⃣ **EXTERNAL ID PATTERN:**\n";
    $timestamp = time();
    echo "   🏷️ Format: AMAZING-ADMIN-COLLECTIVE-{primaryUser->id}-{timestamp}\n";
    echo "   📝 Contoh: AMAZING-ADMIN-COLLECTIVE-1001-{$timestamp}\n";
    echo "   ⚠️  BERBEDA dengan import individual: AMAZING-ADMIN-IMPORT-{timestamp}-{row}\n\n";

    echo "6️⃣ **PERBEDAAN DENGAN REGISTRASI NORMAL:**\n";
    echo "   🆚 Normal Collective: AMAZING-COLLECTIVE-{primaryUser->id}-{timestamp}\n";
    echo "   🆚 Admin Import: AMAZING-ADMIN-COLLECTIVE-{primaryUser->id}-{timestamp}\n";
    echo "   📊 Admin bypass minimum 10 participants rule\n";
    echo "   🔑 Admin bisa import 1-100 participants\n\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "=== KESIMPULAN ===\n";
echo "✅ User pertama (baris 1 Excel) = penerima notifikasi WhatsApp\n";
echo "✅ Invoice dibuat 1 saja untuk seluruh grup\n";
echo "✅ Semua peserta mendapat invoice_id dan invoice_url yang sama\n";
echo "✅ Hanya primaryUser yang mendapat notifikasi dari Xendit\n";
echo "✅ External ID unik per grup import\n";
