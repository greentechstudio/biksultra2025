<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CEK EXTERNAL ID UNTUK USER 1570 ===\n\n";

// Cari semua user dengan ID 1570
$user1570 = User::find(1570);

if ($user1570) {
    echo "✅ User ID 1570 ditemukan:\n";
    echo "   Nama: {$user1570->name}\n";
    echo "   Email: {$user1570->email}\n";
    echo "   External ID: {$user1570->xendit_external_id}\n";
    echo "   Invoice ID: " . ($user1570->xendit_invoice_id ?: 'KOSONG') . "\n";
    echo "   Payment Status: {$user1570->payment_status}\n\n";
    
    // Cari grup berdasarkan External ID yang benar
    $correctExternalId = $user1570->xendit_external_id;
    echo "🔍 Mencari grup dengan External ID: $correctExternalId\n";
    
    $groupUsers = User::where('xendit_external_id', $correctExternalId)->get();
    echo "✅ Ditemukan " . $groupUsers->count() . " peserta dalam grup:\n\n";
    
    foreach ($groupUsers as $index => $user) {
        echo ($index + 1) . ". ID: {$user->id} | Nama: {$user->name}\n";
        echo "   Email: {$user->email}\n";
        echo "   Invoice ID: " . ($user->xendit_invoice_id ?: 'KOSONG') . "\n";
        echo "   Payment Status: {$user->payment_status}\n";
        echo "   ----------------------------------------\n";
    }
    
} else {
    echo "❌ User ID 1570 tidak ditemukan\n\n";
    
    // Cari user dengan External ID pattern 1570
    echo "🔍 Mencari External ID dengan pattern 1570:\n";
    $users1570 = User::where('xendit_external_id', 'LIKE', 'AMAZING-REG-1570-%')->get();
    
    if ($users1570->count() > 0) {
        foreach ($users1570 as $user) {
            echo "- ID: {$user->id}, External ID: {$user->xendit_external_id}\n";
        }
    } else {
        echo "❌ Tidak ada External ID dengan pattern 1570\n";
    }
}

echo "\n=== CARA REGENERATE YANG BENAR ===\n";
echo "1. Gunakan External ID yang benar dari hasil di atas\n";
echo "2. Jalankan: php regenerate_collective_invoice_v2.php\n";
echo "3. Pilih opsi 1 (Regenerate collective invoice)\n";
echo "4. Masukkan External ID yang benar\n";
