<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CEK DATA REGISTRASI KOLEKTIF ===\n\n";

try {
    // Cari data berdasarkan ticket_type_id 25, 24, 23
    $targetTicketTypes = [25, 24, 23];
    
    echo "🔍 Mencari data dengan ticket_type_id: " . implode(', ', $targetTicketTypes) . "\n\n";
    
    // Cari semua user dengan ticket type tersebut
    $users = User::whereIn('ticket_type_id', $targetTicketTypes)
        ->orderBy('created_at', 'desc')
        ->get();
    
    if ($users->isEmpty()) {
        echo "❌ Tidak ditemukan data dengan ticket_type_id tersebut\n";
        exit;
    }
    
    // Group by external_id untuk melihat apakah ada yang kolektif
    $collectiveGroups = $users->where('xendit_external_id', 'LIKE', 'AMAZING-COLLECTIVE-%')
        ->groupBy('xendit_external_id');
    
    $individualUsers = $users->where('xendit_external_id', 'LIKE', 'AMAZING-REG-%');
    
    echo "📊 SUMMARY:\n";
    echo "   Total Users: " . $users->count() . "\n";
    echo "   Registrasi Kolektif: " . $collectiveGroups->count() . " grup\n";
    echo "   Registrasi Individual: " . $individualUsers->count() . " peserta\n\n";

    // Tampilkan grup kolektif jika ada
    if (!$collectiveGroups->isEmpty()) {
        echo "🏃 REGISTRASI KOLEKTIF (" . $collectiveGroups->count() . " grup):\n\n";
        
        $groupNumber = 1;
        foreach ($collectiveGroups as $externalId => $groupUsers) {
            echo "📋 GRUP {$groupNumber}: {$externalId}\n";
            echo "   Jumlah Peserta: " . $groupUsers->count() . "\n";
            echo "   Invoice ID: " . ($groupUsers->first()->xendit_invoice_id ?: 'BELUM ADA') . "\n";
            echo "   Payment Status: " . $groupUsers->first()->payment_status . "\n";
            echo "   Total Amount: Rp " . number_format($groupUsers->sum('registration_fee'), 0, ',', '.') . "\n";
            echo "   Tanggal: " . $groupUsers->first()->created_at->format('d/m/Y H:i') . "\n\n";
            
            echo "   📝 Daftar Peserta:\n";
            foreach ($groupUsers as $index => $user) {
                echo "      " . ($index + 1) . ". ID: {$user->id} | {$user->name} | Ticket: {$user->ticket_type_id} | {$user->race_category} | Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n";
            }
            echo "\n" . str_repeat("-", 100) . "\n\n";
            
            $groupNumber++;
        }
    }
    
    // Tampilkan registrasi individual
    if (!$individualUsers->isEmpty()) {
        echo "👤 REGISTRASI INDIVIDUAL (" . $individualUsers->count() . " peserta):\n\n";
        
        foreach ($individualUsers as $index => $user) {
            echo "   " . ($index + 1) . ". ID: {$user->id} | {$user->name} | Ticket: {$user->ticket_type_id} | {$user->race_category}\n";
            echo "      Email: {$user->email}\n";
            echo "      Invoice: " . ($user->xendit_invoice_id ?: 'BELUM ADA') . "\n";
            echo "      Payment: {$user->payment_status} | Status: {$user->status}\n";
            echo "      Fee: Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n";
            echo "      Tanggal: " . $user->created_at->format('d/m/Y H:i') . "\n\n";
        }
    }
    
    // Analisis ticket type
    echo "📈 ANALISIS PER TICKET TYPE:\n\n";
    foreach ($targetTicketTypes as $ticketTypeId) {
        $ticketUsers = $users->where('ticket_type_id', $ticketTypeId);
        if (!$ticketUsers->isEmpty()) {
            echo "   🎫 Ticket Type {$ticketTypeId}:\n";
            echo "      Jumlah: " . $ticketUsers->count() . " peserta\n";
            echo "      Total Fee: Rp " . number_format($ticketUsers->sum('registration_fee'), 0, ',', '.') . "\n";
            echo "      Kategori: " . $ticketUsers->pluck('race_category')->unique()->implode(', ') . "\n";
            echo "      Status Paid: " . $ticketUsers->where('payment_status', 'paid')->count() . "\n";
            echo "      Status Pending: " . $ticketUsers->where('payment_status', 'pending')->count() . "\n\n";
        }
    }
    
    echo "💡 CARA MENGUBAH KATEGORI:\n";
    echo "1. Catat ID peserta yang ingin diubah\n";
    echo "2. Update kategori manually di database atau buat script khusus\n";
    echo "3. Jika perlu regenerasi invoice, jalankan: php regenerate_collective_invoice.php\n";
    echo "4. Untuk individual: buat invoice baru dengan kategori yang benar\n\n";
    
    echo "⚠️  CATATAN:\n";
    echo "- Ticket Type 25, 24, 23 kemungkinan kategori yang akan diubah\n";
    echo "- Pastikan backup data sebelum melakukan perubahan\n";
    echo "- Koordinasi dengan peserta jika ada perubahan invoice\n\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
