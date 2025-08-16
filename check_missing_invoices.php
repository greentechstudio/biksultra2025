<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\XenditService;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CEK DATA YANG BELUM ADA INVOICE ===\n\n";

// Cari user yang belum ada invoice
$usersWithoutInvoice = User::where(function($query) {
        $query->whereNull('xendit_invoice_id')
              ->orWhere('xendit_invoice_id', '')
              ->orWhereNull('xendit_invoice_url')
              ->orWhere('xendit_invoice_url', '');
    })
    ->where('payment_status', '!=', 'paid')
    ->orderBy('created_at', 'desc')
    ->limit(20) // Limit untuk tidak terlalu banyak
    ->get();

if ($usersWithoutInvoice->isEmpty()) {
    echo "✅ Tidak ada data yang belum memiliki invoice\n\n";
} else {
    echo "🔍 Ditemukan " . $usersWithoutInvoice->count() . " peserta yang belum ada invoice (showing first 20):\n\n";
    
    foreach ($usersWithoutInvoice as $index => $user) {
        echo ($index + 1) . ". ID: {$user->id} | Nama: {$user->name}\n";
        echo "   Email: {$user->email}\n";
        echo "   External ID: {$user->xendit_external_id}\n";
        echo "   Invoice ID: " . ($user->xendit_invoice_id ?: 'KOSONG') . "\n";
        echo "   Payment Status: {$user->payment_status}\n";
        echo "   Created: {$user->created_at}\n";
        echo "   ----------------------------------------\n";
    }
}

// Cari data pending yang mungkin perlu invoice
echo "\n=== CEK DATA PENDING ===\n";
$pendingUsers = User::where('payment_status', 'pending')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

if ($pendingUsers->count() > 0) {
    echo "📋 Data pending terbaru (10 terakhir):\n\n";
    foreach ($pendingUsers as $index => $user) {
        echo ($index + 1) . ". ID: {$user->id} | Nama: {$user->name}\n";
        echo "   External ID: {$user->xendit_external_id}\n";
        echo "   Invoice ID: " . ($user->xendit_invoice_id ?: 'KOSONG') . "\n";
        echo "   Invoice URL: " . ($user->xendit_invoice_url ? 'ADA' : 'KOSONG') . "\n";
        echo "   Created: {$user->created_at}\n";
        echo "   ----------------------------------------\n";
    }
}

echo "\n=== PANDUAN GENERATE INVOICE ===\n";
echo "1. Untuk individual: php regenerate_collective_invoice_v2.php → Opsi 2\n";
echo "2. Untuk collective (timestamp sama): php regenerate_by_timestamp.php\n";
echo "3. Untuk batch process: php generate_missing_invoices.php\n";
echo "4. Untuk specific External ID: php regenerate_collective_invoice_v2.php → Opsi 1\n";
