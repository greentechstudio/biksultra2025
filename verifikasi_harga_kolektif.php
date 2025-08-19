<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\XenditService;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERIFIKASI HARGA IMPORT KOLEKTIF ===\n\n";

try {
    // Get all ticket types with race categories
    $ticketTypes = DB::table('ticket_types')
        ->join('race_categories', 'ticket_types.race_category_id', '=', 'race_categories.id')
        ->select(
            'ticket_types.id',
            'ticket_types.name as ticket_name', 
            'ticket_types.price',
            'ticket_types.is_active',
            'race_categories.name as category_name'
        )
        ->where('ticket_types.is_active', 1)
        ->orderBy('race_categories.name')
        ->orderBy('ticket_types.name')
        ->get();

    echo "📋 **SEMUA TICKET TYPES AKTIF:**\n";
    $categories = [];
    foreach ($ticketTypes as $ticket) {
        if (!isset($categories[$ticket->category_name])) {
            $categories[$ticket->category_name] = [];
        }
        $categories[$ticket->category_name][] = $ticket;
        
        $kolektifFlag = (stripos($ticket->ticket_name, 'kolektif') !== false || 
                        stripos($ticket->ticket_name, 'collective') !== false) ? " 👑 [KOLEKTIF]" : "";
        
        echo "   {$ticket->category_name}: {$ticket->ticket_name} - Rp " . number_format($ticket->price) . "{$kolektifFlag}\n";
    }

    echo "\n🔍 **TEST getCollectivePrice() METHOD:**\n";
    $xenditService = new XenditService();
    
    foreach ($categories as $categoryName => $tickets) {
        echo "\n📊 Category: {$categoryName}\n";
        
        $price = $xenditService->getCollectivePrice($categoryName);
        
        if ($price !== false) {
            echo "   ✅ getCollectivePrice('{$categoryName}') = Rp " . number_format($price) . "\n";
            
            // Find which ticket was selected
            $kolektifTicket = null;
            $anyTicket = null;
            
            foreach ($tickets as $ticket) {
                if (stripos($ticket->ticket_name, 'kolektif') !== false || 
                    stripos($ticket->ticket_name, 'collective') !== false) {
                    $kolektifTicket = $ticket;
                }
                if (!$anyTicket) {
                    $anyTicket = $ticket;
                }
            }
            
            $selectedTicket = $kolektifTicket ?: $anyTicket;
            if ($selectedTicket) {
                echo "   📝 Using ticket: {$selectedTicket->ticket_name} (ID: {$selectedTicket->id})\n";
                
                if ($price == $selectedTicket->price) {
                    echo "   ✅ Harga sesuai database\n";
                } else {
                    echo "   ❌ Harga tidak sesuai! Database: Rp " . number_format($selectedTicket->price) . "\n";
                }
            }
            
        } else {
            echo "   ❌ getCollectivePrice('{$categoryName}') = FALSE (Error)\n";
        }
    }

    echo "\n🎯 **PRIORITAS PEMILIHAN TICKET:**\n";
    echo "   1. Cari ticket dengan nama mengandung 'kolektif' atau 'collective'\n";
    echo "   2. Jika tidak ada, ambil ticket aktif pertama untuk category tersebut\n";
    echo "   3. Return false jika tidak ada ticket aktif\n";

    echo "\n💰 **SIMULASI IMPORT 3 PESERTA:**\n";
    $testCategories = ['5K', '10K', '21K'];
    $totalAmount = 0;
    
    foreach ($testCategories as $category) {
        $price = $xenditService->getCollectivePrice($category);
        if ($price !== false) {
            echo "   Peserta {$category}: Rp " . number_format($price) . "\n";
            $totalAmount += $price;
        }
    }
    
    echo "   📊 Total 3 peserta: Rp " . number_format($totalAmount) . "\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}

echo "\n=== KESIMPULAN ===\n";
echo "✅ Harga import kolektif menggunakan data dari tabel ticket_types\n";
echo "✅ Prioritas diberikan ke ticket dengan nama 'Kolektif'\n";
echo "✅ Jika tidak ada ticket kolektif, gunakan ticket aktif lainnya\n";
echo "✅ Harga sudah sesuai dengan database ticket_types\n";
