<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\XenditService;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== REGENERATE COLLECTIVE BERDASARKAN TIMESTAMP ===\n\n";
echo "Script ini untuk grup collective dengan format AMAZING-REG-{user_id}-{timestamp}\n";
echo "dimana timestamp sama = 1 kelompok collective\n\n";

echo "Masukkan External ID salah satu peserta (contoh: AMAZING-REG-2232-1755328411): ";
$sampleExternalId = trim(fgets(STDIN));

if (empty($sampleExternalId)) {
    echo "❌ External ID tidak boleh kosong\n";
    exit;
}

try {
    // Extract timestamp dari External ID
    if (preg_match('/AMAZING-REG-(\d+)-(\d+)/', $sampleExternalId, $matches)) {
        $userId = $matches[1];
        $timestamp = $matches[2];
        
        echo "🔍 Extracted info:\n";
        echo "   User ID: $userId\n";
        echo "   Timestamp: $timestamp\n\n";
        
        // Cari semua user dengan timestamp yang sama
        echo "🔍 Mencari semua peserta dengan timestamp: $timestamp\n";
        $groupUsers = User::where('xendit_external_id', 'LIKE', "AMAZING-REG-%-$timestamp")
                         ->orderBy('id')
                         ->get();
        
        if ($groupUsers->isEmpty()) {
            echo "❌ Tidak ditemukan peserta dengan timestamp: $timestamp\n";
            exit;
        }
        
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
            echo "   ----------------------------------------\n";
        }
        
        // Analisis grup
        echo "\n=== 📊 ANALISIS GRUP ===\n";
        $paidUsers = $groupUsers->where('payment_status', 'paid');
        $pendingUsers = $groupUsers->where('payment_status', 'pending');
        $withInvoice = $groupUsers->filter(function($user) {
            return !empty($user->xendit_invoice_id);
        });
        
        echo "💰 Status Pembayaran:\n";
        echo "   - PAID: " . $paidUsers->count() . " peserta\n";
        echo "   - PENDING: " . $pendingUsers->count() . " peserta\n";
        echo "\n🧾 Status Invoice:\n";
        echo "   - Sudah ada Invoice: " . $withInvoice->count() . " peserta\n";
        echo "   - Belum ada Invoice: " . ($groupUsers->count() - $withInvoice->count()) . " peserta\n";
        
        // Warning jika ada yang sudah paid
        if ($paidUsers->count() > 0) {
            echo "\n⚠️  PERINGATAN:\n";
            echo "   Ada " . $paidUsers->count() . " peserta yang sudah PAID!\n";
            foreach ($paidUsers as $paidUser) {
                echo "   - {$paidUser->name} (ID: {$paidUser->id})\n";
            }
            echo "\n❗ Regenerate invoice akan RESET status pembayaran mereka!\n";
        }
        
        // Konfirmasi regenerate
        echo "\n=== 🚀 REGENERATE COLLECTIVE INVOICE ===\n";
        echo "Proses yang akan dilakukan:\n";
        echo "1. 🧹 Clear semua invoice lama untuk grup ini\n";
        echo "2. 🆕 Buat 1 collective invoice baru untuk semua peserta\n";
        echo "3. 🔗 Update semua peserta dengan invoice yang sama\n";
        echo "4. 📱 Generate link pembayaran baru\n\n";
        
        echo "Apakah Anda yakin ingin melanjutkan? (y/N): ";
        $confirm = trim(fgets(STDIN));
        
        if (strtolower($confirm) !== 'y') {
            echo "❌ Regenerate dibatalkan\n";
            exit;
        }
        
        // Mulai proses regenerate
        echo "\n🔄 Memulai proses regenerate...\n";
        
        $xenditService = new XenditService();
        
        // Step 1: Clear existing invoices
        echo "1. 🧹 Membersihkan invoice lama...\n";
        foreach ($groupUsers as $user) {
            $user->update([
                'xendit_invoice_id' => null,
                'xendit_invoice_url' => null,
                'payment_status' => 'pending'
            ]);
        }
        echo "   ✅ Invoice lama berhasil dibersihkan\n";
        
        // Step 2: Calculate total amount
        echo "2. 💰 Menghitung total amount...\n";
        $totalAmount = 0;
        $participantDetails = [];
        
        foreach ($groupUsers as $user) {
            $validatedPrice = $xenditService->getCollectivePrice($user->race_category);
            if ($validatedPrice !== false) {
                $totalAmount += $validatedPrice;
                
                // Update price if needed
                if ($user->registration_fee != $validatedPrice) {
                    echo "   💰 Updating price untuk {$user->name}: Rp " . 
                         number_format($user->registration_fee, 0, ',', '.') . 
                         " → Rp " . number_format($validatedPrice, 0, ',', '.') . "\n";
                    $user->update(['registration_fee' => $validatedPrice]);
                }
                
                $participantDetails[] = [
                    'name' => $user->name,
                    'category' => $user->race_category,
                    'fee' => $validatedPrice
                ];
            } else {
                echo "   ❌ Cannot validate price for category: {$user->race_category}\n";
                exit;
            }
        }
        
        echo "   ✅ Total Amount: Rp " . number_format($totalAmount, 0, ',', '.') . "\n";
        
        // Step 3: Create collective invoice
        echo "3. 🆕 Membuat collective invoice baru...\n";
        
        $primaryUser = $groupUsers->first();
        $description = "Amazing Sultra Run - Collective Registration (Timestamp: $timestamp)";
        
        $paymentResult = $xenditService->createCollectiveInvoice(
            $primaryUser,
            $participantDetails,
            $totalAmount,
            $description
        );
        
        if ($paymentResult['success']) {
            // Step 4: Update all users
            echo "4. 🔗 Updating semua peserta dengan invoice baru...\n";
            foreach ($groupUsers as $user) {
                $user->update([
                    'xendit_invoice_id' => $paymentResult['invoice_id'],
                    'xendit_invoice_url' => $paymentResult['invoice_url'],
                    'payment_description' => $description,
                    'status' => 'registered'
                ]);
            }
            
            // Success summary
            echo "\n🎉 REGENERATE BERHASIL!\n\n";
            echo "=== 📋 DETAIL INVOICE BARU ===\n";
            echo "📄 Invoice ID: {$paymentResult['invoice_id']}\n";
            echo "🆔 External ID: {$paymentResult['external_id']}\n";
            echo "💰 Amount: Rp " . number_format($paymentResult['amount'], 0, ',', '.') . "\n";
            echo "🔗 URL: {$paymentResult['invoice_url']}\n";
            echo "👥 Peserta: " . $groupUsers->count() . " orang\n";
            echo "⏰ Timestamp: $timestamp\n\n";
            
            echo "=== 📱 INFORMASI PENTING ===\n";
            echo "✅ Semua " . $groupUsers->count() . " peserta sekarang menggunakan invoice yang SAMA\n";
            echo "🔗 Group leader dapat menggunakan link pembayaran untuk bayar semua peserta sekaligus\n";
            echo "📧 Link pembayaran: {$paymentResult['invoice_url']}\n\n";
            
            echo "=== 📋 PESERTA YANG DIUPDATE ===\n";
            foreach ($groupUsers as $index => $user) {
                echo ($index + 1) . ". {$user->name} (ID: {$user->id}) - {$user->xendit_external_id}\n";
            }
            
        } else {
            echo "❌ Gagal membuat invoice baru!\n";
            echo "Error: " . ($paymentResult['message'] ?? 'Unknown error') . "\n";
        }
        
    } else {
        echo "❌ Format External ID tidak valid\n";
        echo "Expected format: AMAZING-REG-{user_id}-{timestamp}\n";
        echo "Contoh: AMAZING-REG-2232-1755328411\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error occurred: " . $e->getMessage() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}
