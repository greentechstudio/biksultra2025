<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\XenditService;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== REGENERATE COLLECTIVE INVOICE ===\n\n";

// Input primary user ID atau external ID
echo "Masukkan Primary User ID atau External ID: ";
$input = trim(fgets(STDIN));

try {
    $xenditService = new XenditService();
    
    // Find users in the collective group
    if (is_numeric($input)) {
        // Search by primary user ID
        $primaryUserId = $input;
        $users = User::where('xendit_external_id', 'LIKE', "AMAZING-COLLECTIVE-{$primaryUserId}-%")
                     ->orderBy('created_at', 'asc')
                     ->get();
    } else {
        // Search by external ID
        $users = User::where('xendit_external_id', $input)
                     ->orderBy('created_at', 'asc')
                     ->get();
        
        if ($users->isEmpty()) {
            $users = User::where('xendit_external_id', 'LIKE', "{$input}%")
                         ->orderBy('created_at', 'asc')
                         ->get();
        }
    }

    if ($users->isEmpty()) {
        echo "❌ Tidak ditemukan user dengan ID/External ID: {$input}\n";
        exit;
    }

    echo "✅ Ditemukan {$users->count()} peserta dalam grup kolektif:\n\n";
    
    $totalAmount = 0;
    $participantDetails = [];
    
    foreach ($users as $index => $user) {
        echo "   " . ($index + 1) . ". {$user->name} - {$user->race_category} - Status: {$user->status}\n";
        echo "      Email: {$user->email}\n";
        echo "      Current Invoice: " . ($user->xendit_invoice_id ?: 'NONE') . "\n";
        echo "      Payment Status: {$user->payment_status}\n\n";
        
        // Validate price for regeneration
        $validatedPrice = $xenditService->getCollectivePrice($user->race_category);
        if ($validatedPrice !== false) {
            $totalAmount += $validatedPrice;
            
            // Update user with correct price if needed
            if ($user->registration_fee != $validatedPrice) {
                echo "      ⚠️  Updating price: Rp " . number_format($user->registration_fee, 0, ',', '.') . 
                     " → Rp " . number_format($validatedPrice, 0, ',', '.') . "\n";
                $user->update(['registration_fee' => $validatedPrice]);
            }
            
            $participantDetails[] = [
                'name' => $user->name,
                'category' => $user->race_category,
                'fee' => $validatedPrice
            ];
        } else {
            echo "      ❌ Cannot validate price for category: {$user->race_category}\n";
            exit;
        }
    }
    
    echo "💰 Total Amount: Rp " . number_format($totalAmount, 0, ',', '.') . "\n\n";
    
    echo "Lanjutkan regenerasi invoice? (y/N): ";
    $confirm = trim(fgets(STDIN));
    
    if (strtolower($confirm) !== 'y') {
        echo "❌ Regenerasi dibatalkan\n";
        exit;
    }
    
    // Clear existing invoice data
    foreach ($users as $user) {
        $user->update([
            'xendit_invoice_id' => null,
            'xendit_invoice_url' => null,
            'payment_status' => 'pending'
        ]);
    }
    
    echo "🔄 Membersihkan data invoice lama...\n";
    
    // Create new collective invoice
    $primaryUser = $users->first();
    $description = "Amazing Sultra Run - Registrasi Kolektif Regenerated (" . $users->count() . " peserta)";
    
    echo "🆕 Membuat invoice baru...\n";
    
    $paymentResult = $xenditService->createCollectiveInvoice(
        $primaryUser,
        $participantDetails,
        $totalAmount,
        $description
    );
    
    if ($paymentResult['success']) {
        // Update all users with new invoice
        foreach ($users as $user) {
            $user->update([
                'xendit_invoice_id' => $paymentResult['invoice_id'],
                'xendit_invoice_url' => $paymentResult['invoice_url'],
                'payment_description' => $description,
                'status' => 'registered'
            ]);
        }
        
        echo "✅ Invoice berhasil di-regenerate!\n\n";
        echo "📋 Detail Invoice Baru:\n";
        echo "   Invoice ID: {$paymentResult['invoice_id']}\n";
        echo "   External ID: {$paymentResult['external_id']}\n";
        echo "   Amount: Rp " . number_format($paymentResult['amount'], 0, ',', '.') . "\n";
        echo "   URL: {$paymentResult['invoice_url']}\n\n";
        
        echo "📱 Link pembayaran baru telah di-update untuk semua peserta.\n";
        echo "🔗 Group Leader dapat menggunakan link: {$paymentResult['invoice_url']}\n";
        
    } else {
        echo "❌ Gagal membuat invoice baru: " . $paymentResult['message'] . "\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
