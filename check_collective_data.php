<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CEK DATA REGISTRASI KOLEKTIF ===\n\n";

try {
    // Cari semua grup kolektif yang sudah ada invoice
    $collectiveGroups = User::whereNotNull('xendit_invoice_id')
        ->where('xendit_external_id', 'LIKE', 'AMAZING-COLLECTIVE-%')
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('xendit_external_id');

    if ($collectiveGroups->isEmpty()) {
        echo "❌ Tidak ditemukan registrasi kolektif dengan invoice\n";
        exit;
    }

    echo "📋 Ditemukan " . $collectiveGroups->count() . " grup registrasi kolektif:\n\n";
    
    $groupNumber = 1;
    foreach ($collectiveGroups as $externalId => $users) {
        echo "🏃 GRUP {$groupNumber}: {$externalId}\n";
        echo "   Jumlah Peserta: " . $users->count() . "\n";
        echo "   Invoice ID: " . $users->first()->xendit_invoice_id . "\n";
        echo "   Payment Status: " . $users->first()->payment_status . "\n";
        echo "   Total Amount: Rp " . number_format($users->sum('registration_fee'), 0, ',', '.') . "\n";
        echo "   Tanggal Registrasi: " . $users->first()->created_at->format('d/m/Y H:i') . "\n\n";
        
        echo "   📝 Daftar Peserta:\n";
        foreach ($users as $index => $user) {
            echo "      " . ($index + 1) . ". ID: {$user->id} | {$user->name} | {$user->race_category} | Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n";
        }
        echo "\n" . str_repeat("-", 80) . "\n\n";
        
        $groupNumber++;
    }
    
    echo "💡 CARA REGENERASI:\n";
    echo "1. Catat ID peserta yang ingin diubah kategorinya\n";
    echo "2. Jalankan: php regenerate_collective_invoice.php\n";
    echo "3. Masukkan Primary User ID atau External ID grup yang ingin diregenerasi\n\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
