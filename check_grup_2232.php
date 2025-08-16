<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\XenditService;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CEK GRUP AMAZING-REG-2232-1755328411 ===\n\n";

$externalId = 'AMAZING-REG-2232-1755328411';

// Extract timestamp untuk mencari grup
if (preg_match('/AMAZING-REG-(\d+)-(\d+)/', $externalId, $matches)) {
    $userId = $matches[1];
    $timestamp = $matches[2];
    
    echo "🔍 Info External ID:\n";
    echo "   Sample User ID: $userId\n";
    echo "   Timestamp: $timestamp\n\n";
    
    // Cari semua user dengan timestamp yang sama (collective group)
    echo "🔍 Mencari semua peserta dengan timestamp: $timestamp\n";
    $groupUsers = User::where('xendit_external_id', 'LIKE', "AMAZING-REG-%-$timestamp")
                     ->orderBy('id')
                     ->get();
    
    if ($groupUsers->isEmpty()) {
        echo "❌ Tidak ditemukan peserta dengan timestamp: $timestamp\n";
        
        // Cek apakah sample user ada
        echo "\n🔍 Cek sample user dengan External ID exact: $externalId\n";
        $sampleUser = User::where('xendit_external_id', $externalId)->first();
        
        if ($sampleUser) {
            echo "✅ Sample user ditemukan:\n";
            echo "   ID: {$sampleUser->id}\n";
            echo "   Nama: {$sampleUser->name}\n";
            echo "   Email: {$sampleUser->email}\n";
            echo "   External ID: {$sampleUser->xendit_external_id}\n";
            echo "   Invoice ID: " . ($sampleUser->xendit_invoice_id ?: 'KOSONG') . "\n";
            echo "   Payment Status: {$sampleUser->payment_status}\n";
        } else {
            echo "❌ Sample user tidak ditemukan\n";
        }
        
    } else {
        echo "✅ Ditemukan " . $groupUsers->count() . " peserta dalam grup collective:\n\n";
        
        // Tampilkan semua peserta
        foreach ($groupUsers as $index => $user) {
            echo ($index + 1) . ". ID: {$user->id} | Nama: {$user->name}\n";
            echo "   Email: {$user->email}\n";
            echo "   External ID: {$user->xendit_external_id}\n";
            echo "   Invoice ID: " . ($user->xendit_invoice_id ?: 'KOSONG') . "\n";
            echo "   Invoice URL: " . ($user->xendit_invoice_url ? 'ADA' : 'KOSONG') . "\n";
            echo "   Payment Status: {$user->payment_status}\n";
            echo "   Race Category: {$user->race_category}\n";
            echo "   Registration Fee: Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n";
            echo "   Created: {$user->created_at}\n";
            echo "   ----------------------------------------\n";
        }
        
        // Analisis status invoice
        echo "\n=== 📊 ANALISIS INVOICE STATUS ===\n";
        $withInvoice = $groupUsers->filter(function($user) {
            return !empty($user->xendit_invoice_id);
        });
        $withoutInvoice = $groupUsers->filter(function($user) {
            return empty($user->xendit_invoice_id);
        });
        $paidUsers = $groupUsers->where('payment_status', 'paid');
        $pendingUsers = $groupUsers->where('payment_status', 'pending');
        
        echo "🧾 Status Invoice:\n";
        echo "   - Sudah ada Invoice: " . $withInvoice->count() . " peserta\n";
        echo "   - Belum ada Invoice: " . $withoutInvoice->count() . " peserta\n";
        echo "\n💰 Status Pembayaran:\n";
        echo "   - PAID: " . $paidUsers->count() . " peserta\n";
        echo "   - PENDING: " . $pendingUsers->count() . " peserta\n";
        
        if ($withoutInvoice->count() > 0) {
            echo "\n❗ PESERTA YANG BELUM ADA INVOICE:\n";
            foreach ($withoutInvoice as $user) {
                echo "   - {$user->name} (ID: {$user->id})\n";
            }
        }
        
        // Cek apakah perlu generate invoice
        if ($withoutInvoice->count() > 0 && $paidUsers->count() == 0) {
            echo "\n🎯 REKOMENDASI: Generate collective invoice untuk seluruh grup\n";
            echo "   Jalankan: php regenerate_by_timestamp.php\n";
            echo "   Input: $externalId\n";
        } elseif ($withInvoice->count() > 0 && $withoutInvoice->count() > 0) {
            echo "\n⚠️  MIXED STATUS: Ada yang sudah ada invoice, ada yang belum\n";
            echo "   Pertimbangkan regenerate untuk konsistensi\n";
        } elseif ($paidUsers->count() > 0) {
            echo "\n✅ ADA YANG SUDAH PAID: Hati-hati regenerate akan reset payment status\n";
        } else {
            echo "\n✅ SEMUA SUDAH ADA INVOICE\n";
        }
    }
} else {
    echo "❌ Format External ID tidak valid\n";
    echo "Expected: AMAZING-REG-{user_id}-{timestamp}\n";
}

echo "\n=== 🚀 CARA GENERATE INVOICE ===\n";
echo "1. Jika grup belum ada invoice sama sekali:\n";
echo "   php regenerate_by_timestamp.php\n";
echo "   Masukkan: $externalId\n\n";
echo "2. Jika ingin regenerate yang sudah ada:\n";
echo "   php regenerate_collective_invoice_v2.php\n";
echo "   Pilih opsi 1, masukkan: $externalId\n\n";
echo "3. Jika ingin generate individual saja:\n";
echo "   php regenerate_collective_invoice_v2.php\n";
echo "   Pilih opsi 2, masukkan User ID\n";
