<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CEK SEMUA DATA REGISTRASI ===\n\n";

try {
    // Cari semua user dengan invoice
    $usersWithInvoice = User::whereNotNull('xendit_invoice_id')
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();

    if ($usersWithInvoice->isEmpty()) {
        echo "❌ Tidak ditemukan user dengan invoice Xendit\n\n";
        
        // Cek user tanpa invoice
        $usersWithoutInvoice = User::whereNull('xendit_invoice_id')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        if (!$usersWithoutInvoice->isEmpty()) {
            echo "📋 Ditemukan " . $usersWithoutInvoice->count() . " user TANPA invoice:\n\n";
            foreach ($usersWithoutInvoice as $index => $user) {
                echo "   " . ($index + 1) . ". ID: {$user->id} | {$user->name} | {$user->race_category} | Status: {$user->status}\n";
                echo "      Email: {$user->email}\n";
                echo "      External ID: " . ($user->xendit_external_id ?: 'NONE') . "\n";
                echo "      Created: " . $user->created_at->format('d/m/Y H:i') . "\n\n";
            }
        }
        exit;
    }

    echo "📋 Ditemukan " . $usersWithInvoice->count() . " user dengan invoice:\n\n";
    
    foreach ($usersWithInvoice as $index => $user) {
        echo "🎯 USER " . ($index + 1) . ":\n";
        echo "   ID: {$user->id}\n";
        echo "   Nama: {$user->name}\n";
        echo "   Email: {$user->email}\n";
        echo "   Kategori: {$user->race_category}\n";
        echo "   Fee: Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n";
        echo "   Status: {$user->status}\n";
        echo "   Payment Status: {$user->payment_status}\n";
        echo "   Invoice ID: {$user->xendit_invoice_id}\n";
        echo "   External ID: " . ($user->xendit_external_id ?: 'NONE') . "\n";
        echo "   Created: " . $user->created_at->format('d/m/Y H:i') . "\n";
        
        // Cek jika ini kolektif
        if ($user->xendit_external_id && strpos($user->xendit_external_id, 'AMAZING-COLLECTIVE-') === 0) {
            $sameGroup = User::where('xendit_external_id', $user->xendit_external_id)
                ->where('id', '!=', $user->id)
                ->count();
            if ($sameGroup > 0) {
                echo "   🏃 KOLEKTIF: " . ($sameGroup + 1) . " peserta dalam grup ini\n";
            }
        }
        
        echo "\n" . str_repeat("-", 60) . "\n\n";
    }
    
    // Statistik
    $totalUsers = User::count();
    $usersWithInvoiceCount = User::whereNotNull('xendit_invoice_id')->count();
    $collectiveUsers = User::where('xendit_external_id', 'LIKE', 'AMAZING-COLLECTIVE-%')->count();
    
    echo "📊 STATISTIK:\n";
    echo "   Total Users: {$totalUsers}\n";
    echo "   Users dengan Invoice: {$usersWithInvoiceCount}\n";
    echo "   Users Registrasi Kolektif: {$collectiveUsers}\n\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
